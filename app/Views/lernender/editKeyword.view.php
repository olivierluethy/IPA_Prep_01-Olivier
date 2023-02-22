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
include ("general/navside.view.php");
?>

    <main>
        <form action="editKeyword?id=<?= $getKeyword[0][0] ?>" method="POST">
            <h2>Edit Keyword</h2>
            <label for="title">Thema:</label><br>
            <input type="text" id="title" name="thema" id="title" value="<?= $getKeyword[0][1] ?>"><br>
            <input type="submit" class="" name="addTask" value="Edit Keyword"><br><br>
        </form>
    </main>

    <script src="public/js/app.js"></script>
    <script src="public/js/route.js"></script>

</body>

</html>