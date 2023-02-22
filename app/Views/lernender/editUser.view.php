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
    <title>Journal - Edit Keyword</title>
</head>

<body>
    <?php
$actual_link = basename(__FILE__); // aktueller dateiname (wird für header.php benötigt)
include(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "General" . DIRECTORY_SEPARATOR . "navside.view.php");
?>

    <main>
        <form action="editUser?id=<?= $getUser[0][0] ?>" method="POST">
            <h2>Edit User</h2>
            <label for="title">Role:</label><br>
            <input type="text" id="title" name="role" id="title" value="<?= $getUser[0][9] ?>"><br><br><br><br><br><br>
            <input type="submit" class="send" name="addTask" value="Edit User"><br><br>
        </form>
    </main>

    <script src="public/js/app.js"></script>
    <script src="public/js/route.js"></script>

</body>

</html>