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