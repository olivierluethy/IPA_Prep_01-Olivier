<?php
// Require the autoload file from the composer package
require_once 'vendor/autoload.php';

// Start a session to store the access token
session_start();

// Zugangsdaten laden: bevorzugt aus Umgebungsvariablen, sonst aus der
// nicht versionierten Datei secrets.local.php (siehe secrets.local.example.php).
$secretsFile = __DIR__ . '/secrets.local.php';
$secrets     = is_file($secretsFile) ? require $secretsFile : [];

$clientId     = getenv('GOOGLE_CLIENT_ID')     ?: ($secrets['client_id']     ?? '');
$clientSecret = getenv('GOOGLE_CLIENT_SECRET') ?: ($secrets['client_secret'] ?? '');
$redirectUri  = getenv('GOOGLE_REDIRECT_URI')  ?: ($secrets['redirect_uri']  ?? 'http://localhost/01-Olivier/home');

if ($clientId === '' || $clientSecret === '') {
    http_response_code(500);
    exit('Google-OAuth-Zugangsdaten fehlen. Lege app/Views/general/secrets.local.php an '
       . '(Vorlage: secrets.local.example.php) oder setze die Umgebungsvariablen '
       . 'GOOGLE_CLIENT_ID und GOOGLE_CLIENT_SECRET.');
}

// Create a new Google Client object
$client = new Google_Client();

// Set the client ID and client secret for the Google API
$client->setClientId($clientId);
$client->setClientSecret($clientSecret);

// Set the redirect URI to redirect the user after logging in
$client->setRedirectUri($redirectUri);

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