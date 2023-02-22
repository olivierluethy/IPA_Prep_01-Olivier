<?php

class JournalController
{
	/* When person is not logged in */
	public function login(){
		require_once 'app/Views/general/config.php';

		require 'app/Views/general/login.view.php';
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

	/* Für Lernender */
	public function home(){
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
				$conn = new PDO("mysql:host=localhost;dbname=journal", "root", "");
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
				// Check if the user exists in the database
				$query = "SELECT * FROM benutzer WHERE email = :email";
				$stmt = $conn->prepare($query);
				$stmt->bindParam(':email', $email);
				$stmt->execute();
				$user = $stmt->fetch();

				// If the user exists, get all the information from the database
				if ($user) {
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
				}

				// If the user is not in the database, add them
				else {
					try {
						$query = "INSERT INTO benutzer (email, first_name, last_name, gender, full_name, picture, verifiedEmail, token, role) VALUES (':email', ':first_name', ':last_name', ':gender', ':full_name', ':picture', ':verifiedEmail', ':token', 0)";
						$stmt = $conn->prepare($query);
						$stmt->bindParam(':email', $email);
						$stmt->bindParam(':first_name', $firstName);
						$stmt->bindParam(':last_name', $lastName);
						$stmt->bindParam(':gender', $gender);
						$stmt->bindParam(':full_name', $name);
						$stmt->bindParam(':picture', $profileImageUrl);
						$stmt->bindParam(':verifiedEmail', $verifiedEmail);
						$stmt->bindParam(':token', $token);
						$stmt->execute();

						
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
			header("location: login");
		}

		$Journal = new Journal();
		$arrayJournalsInRelease = $Journal->getAllDailyJournalsInRelease()->fetchAll();

		$Wochenrapport = new WeeklyReport();
		$arrayWeeklyIsInRelease = $Wochenrapport->getAllWeeklyInRelease()->fetchAll();

		require 'app/Views/home.view.php';
	}

	public function releasedreports(){		
		require_once 'app/Views/general/config.php';

		require 'app/Views/fachkraft/releasedreports.view.php';
	}
}