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
    <title>Journal - My Keywords</title>
</head>

<body>
    <button id="burger" onclick="toggleSidebar()">&#9776;</button>

    <?php
$actual_link = basename(__FILE__); // aktueller dateiname (wird für header.php benötigt)
include(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "General" . DIRECTORY_SEPARATOR . "navside.view.php");
?>

    <main>
        <?php if (count($arrayKeywords) > 0){?>
        <div class="withData">
            <div class="dataTitle">
                <h2>My keywords</h2>
                <button onclick="navigateTo('addkeyword')" title="Add a keyword">+</button>
            </div>
            <?php foreach ($arrayKeywords as $keyword){ ?>
            <div class="grid-container">
                <div><?= $keyword['thema'] ?></div>
                <div><button class="btn1" title="Edit this keyword"
                        onclick='editKeyword(<?php echo $keyword["themaId"] ?>)'><i class="fas fa-edit"></i>
                        Edit</button></div>
                <div><button class="btn2" title="Delete this keyword"
                        onclick='deleteKeyword(<?php echo $keyword["themaId"] ?>)'><i class="fas fa-trash"></i>
                        Delete</button></div>
            </div>
            <?php } ?>
        </div>
        <?php } else { ?>
        <h1>There are no keywords</h1>
        <button onclick="navigateTo('addkeyword')" title="Click to add a keyword" class="addReport">Create your first
            keyword!</button>
        <?php }?>
    </main>


    <script src="public/js/app.js"></script>
    <script src="public/js/route.js"></script>

</body>

</html>