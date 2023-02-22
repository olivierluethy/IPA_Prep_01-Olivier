<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/css/dailyweeklyreport.css">
    <link rel="stylesheet" href="public/css/navside.css">
    <link rel="stylesheet" href="public/fontawesome/css/all.css">
    <link rel="shortcut icon" href="images/favicon.ico">
    <title>Journal - User Overview</title>
</head>

<body>
    <button id="burger" onclick="toggleSidebar()">&#9776;</button>

    <?php
$actual_link = basename(__FILE__); // aktueller dateiname (wird für header.php benötigt)
include ("navside.view.php");
?>

    <main>
        <div class="withData">
            <h2>User</h2>
            <?php
            if (count($arrayUsers) > 0) {
                foreach ($arrayUsers as $user) {
                    $date = date('dS M Y', strtotime($user['created_at']));
            ?>
                    <div class='grid-container'>
                        <div><?php echo $user['email']; ?></div>
                        <div><?php echo $user['full_name']; ?></div>
                        <div><?php echo $date; ?></div>
                        <div><button class='btn1' onclick='editUser(<?php echo $user["benutzerId"]; ?>)'><i class='fas fa-edit'></i> Edit</button></div>
                        <div><button class='btn2' onclick='deleteUser(<?php echo $user["benutzerId"]; ?>)'><i class='fas fa-trash'></i> Delete</button></div>
                    </div>
            <?php
                }
            } else {
            ?>
                <div class='noData'>
                    <h1>There are no learners or specialists</h1>
                    <p onclick='copyLink()' id='copyIt'>Send this link to your people to login</p>
                </div>
            <?php } ?>
        </div>
    </main>

    <script src="public/js/app.js"></script>
    <script src="public/js/route.js"></script>

</body>

</html>