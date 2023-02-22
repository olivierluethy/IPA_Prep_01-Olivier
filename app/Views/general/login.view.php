<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/css/navside.css">
    <link rel="stylesheet" href="public/fontawesome/css/all.css">
    <link rel="stylesheet" href="public/css/home.css">
    <link rel="shortcut icon" href="images/favicon.ico">
    <title>Journal - Login</title>
</head>

<body>
<?php
$actual_link = basename(__FILE__); // aktueller dateiname (wird für header.php benötigt)
include ("navside.view.php");
?>

    <main>
        <!-- main content goes here -->
        <h1>Welcome To Journal <br> Web-App</h1>

        <?php
            if (isset($_SESSION['user_token'])) {
            header("Location: home");
            } else {
            echo "<a href='" . $client->createAuthUrl() . "'><button class='loginBtn'>Login with Google</button></a>";
            }
        ?>
    </main>

    <script src="public/js/app.js"></script>

</body>

</html>