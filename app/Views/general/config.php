<?php
// Require the autoload file from the composer package
require_once 'vendor/autoload.php';

// Start a session to store the access token
session_start();

// Create a new Google Client object
$client = new Google_Client();

// Set the client ID and client secret for the Google API
$client->setClientId('299537301506-l3upbv160vq5uhtddc0evgnnrkt5i0ps.apps.googleusercontent.com');
$client->setClientSecret('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');

// Set the redirect URI to redirect the user after logging in
$client->setRedirectUri('http://localhost/01-Olivier/home');

// Add the required scopes for the Google API
$client->addScope('https://www.googleapis.com/auth/plus.login');
$client->addScope('https://www.googleapis.com/auth/userinfo.email');
$client->addScope('https://www.googleapis.com/auth/userinfo.profile');

// Check if the code parameter is set in the URL
if (isset($_GET['code'])) {
  // Fetch the access token with the auth code
  $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

  // Set the access token in the client object
  $client->setAccessToken($token);

  // Store the access token in the session
  $_SESSION['access_token'] = $token;

  // Redirect the user back to the index page
  header('Location: http://localhost/01-Olivier/home');
  exit;
}
?>