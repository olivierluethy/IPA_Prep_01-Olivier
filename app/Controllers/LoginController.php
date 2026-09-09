<?php

class LoginController
{
    public function login(){		
		require_once 'app/Views/general/config.php';

		// Check if the user has an access token in the session and it is valid
        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
            // If the access token is present, set it in the client object
            $client->setAccessToken($_SESSION['access_token']);
        
            // Create a new Google_Service_Oauth2 object
            $oauth = new Google_Service_Oauth2($client);
        
            // Get the user information from the OAuth service
            $user = $oauth->userinfo->get();
        
            // Get the user's name, email and profile image URL
			$email = $user->getEmail();
			$firstName = $user->getGivenName();
			$lastName = $user->getFamilyName();
			$gender = $user->getGender();
            $name = $user->getName();
			$profileImageUrl = $user->getPicture();
			$verifiedEmail = $user->getVerifiedEmail();
			$token = $client->getAccessToken();
        
            // Store the user's name and email in the session
            $_SESSION['email'] = $email;
			$_SESSION['first_name'] = $firstName;
			$_SESSION['last_name'] = $lastName;
			$_SESSION['gender'] = $gender;
			$_SESSION['name'] = $name;
			$_SESSION['profileImageUrl'] = $profileImageUrl;
			$_SESSION['verifiedEmail'] = $verifiedEmail;
			$_SESSION['token'] = $token;
        
            // Connect to the database
			try {
				$login = new Login();
                $userExists = $login->doesUserExist($email);

                error_reporting(E_ALL);
                ini_set('display_errors', '1');

				// If the user exists, get all the information from the database
				if ($userExists) {
					$firstName = $user['first_name'];
					$lastName = $user['last_name'];
					$gender = $user['gender'];
					$fullName = $user['full_name'];
					$profileImageUrl = $user['picture'];
					$verifiedEmail = $user['verifiedEmail'];
					$token = $user['token'];
					$role = $user['role'];

					$_SESSION['first_name'] = $firstName;
					$_SESSION['last_name'] = $lastName;
					$_SESSION['gender'] = $gender;
					$_SESSION['full_name'] = $fullName;
					$_SESSION['profileImageUrl'] = $profileImageUrl;
					$_SESSION['verifiedEmail'] = $verifiedEmail;
					$_SESSION['token'] = $token;
					$_SESSION['role'] = $role;
					$_SESSION['loggedin'] = true;

                    header("Location: home");
                    exit;
				}

				// If the user is not in the database, add them
				else {
					try {

						$login->addUser($email, $firstName, $lastName, $gender, $name, $profileImageUrl, $verifiedEmail, $token);

                        header("Location: home");
                        exit();
					} catch (PDOException $e) {
						// If there is an error, display the error message
						echo "Error: " . $e->getMessage();
					}
				}
			}catch (PDOException $e) {
				// If there is an error, display the error message
				echo "Error: " . $e->getMessage();
			}
        } else {
            require 'app/Views/general/login.view.php';
        }
	}

    /* Normale Registrierung (E-Mail/Passwort) */
    public function register(){
        $this->startSession();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: login');
            exit;
        }
        if (!$this->checkCsrf()) {
            $this->authFail('register', 'Sitzung abgelaufen. Bitte erneut versuchen.');
        }

        $firstName = trim(post('first_name'));
        $lastName  = trim(post('last_name'));
        $email     = trim(post('email'));
        $password  = (string) post('password');
        $role      = (string) post('role', '0');

        if ($firstName === '' || $lastName === '' || $email === '' || $password === '') {
            $this->authFail('register', 'Bitte alle Felder ausfüllen.');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->authFail('register', 'Bitte eine gültige E-Mail-Adresse eingeben.');
        }
        if (mb_strlen($password) < 6) {
            $this->authFail('register', 'Das Passwort muss mindestens 6 Zeichen lang sein.');
        }
        if (!in_array($role, ['0', '1', '2'], true)) {
            $role = '0';
        }

        try {
            $login = new Login();
            if ($login->doesUserExist($email)) {
                $this->authFail('register', 'Diese E-Mail-Adresse ist bereits registriert.');
            }

            $hash = password_hash($password, PASSWORD_DEFAULT);
            $login->registerUser($firstName, $lastName, $email, $hash, (int) $role);

            $user = $login->getUserByEmail($email);
            $this->establishSession($user);

            header('Location: home');
            exit;
        } catch (PDOException $e) {
            $this->authFail('register', 'Registrierung fehlgeschlagen. Bitte später erneut versuchen.');
        }
    }

    /* Normaler Login (E-Mail/Passwort) */
    public function authenticate(){
        $this->startSession();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: login');
            exit;
        }
        if (!$this->checkCsrf()) {
            $this->authFail('login', 'Sitzung abgelaufen. Bitte erneut versuchen.');
        }

        $email    = trim(post('email'));
        $password = (string) post('password');

        if ($email === '' || $password === '') {
            $this->authFail('login', 'Bitte E-Mail und Passwort eingeben.');
        }

        try {
            $login = new Login();
            $user  = $login->getUserByEmail($email);

            if (!$user || $user['password'] === '' || !password_verify($password, $user['password'])) {
                $this->authFail('login', 'Falsche E-Mail oder falsches Passwort.');
            }

            $this->establishSession($user);

            header('Location: home');
            exit;
        } catch (PDOException $e) {
            $this->authFail('login', 'Anmeldung fehlgeschlagen. Bitte später erneut versuchen.');
        }
    }

    /* Startet die Session, falls noch keine läuft. */
    private function startSession(){
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /* Prüft den CSRF-Token des Formulars gegen die Session. */
    private function checkCsrf(){
        $token = (string) post('csrf');
        return !empty($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $token);
    }

    /* Merkt sich die Fehlermeldung + den betroffenen Tab und leitet zur Login-Seite zurück. */
    private function authFail($mode, $message){
        $_SESSION['auth_error'] = $message;
        $_SESSION['auth_mode']  = $mode; // 'login' oder 'register'
        header('Location: login');
        exit;
    }

    /* Setzt alle Session-Variablen, die die bestehenden Seiten/Guards erwarten. */
    private function establishSession(array $user){
        $fullName = $user['full_name'] !== '' ? $user['full_name'] : trim($user['first_name'] . ' ' . $user['last_name']);
        $picture  = $user['picture'] !== '' ? $user['picture'] : avatarDataUri($fullName);

        $_SESSION['id']              = (int) $user['benutzerId'];
        $_SESSION['email']           = $user['email'];
        $_SESSION['first_name']      = $user['first_name'];
        $_SESSION['last_name']       = $user['last_name'];
        $_SESSION['gender']          = $user['gender'];
        $_SESSION['full_name']       = $fullName;
        $_SESSION['name']            = $fullName;
        $_SESSION['profileImageUrl'] = $picture;
        $_SESSION['verifiedEmail']   = $user['verifiedEmail'];
        $_SESSION['token']           = bin2hex(random_bytes(16));
        $_SESSION['role']            = (int) $user['role'];
        $_SESSION['loggedin']        = true;

        // CSRF-Token nach erfolgreicher Anmeldung erneuern.
        unset($_SESSION['csrf'], $_SESSION['auth_error'], $_SESSION['auth_mode']);
    }

    /* When person wants to log out */
	public function logout(){
		// Initialize the session
		session_start();
		
		// Unset all of the session variables
		$_SESSION = array();

		// Destroy the session.
		session_destroy();
		
		// Redirect to login page
		header("location: login");
		exit;
	}
}