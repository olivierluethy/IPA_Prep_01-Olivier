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
    <title>Journal - Weekly Raport</title>
</head>

<body>
    <button id="burger" onclick="toggleSidebar()">&#9776;</button>

    <?php
$actual_link = basename(__FILE__); // aktueller dateiname (wird für header.php benötigt)
include(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "General" . DIRECTORY_SEPARATOR . "navside.view.php");
?>

    <main>
        <div class="withData">
            <?php 
            if(count($arrayWeeklyInProcess) > 0 || count($arrayWeeklyIsReleased) > 0){
                if (count($arrayWeeklyInProcess) > 0){
                    echo "<h2>Still in process</h2>";
                    foreach ($arrayWeeklyInProcess as $weeklyIsProcess){
                        $date = date('dS M Y', strtotime($weeklyIsProcess['datum']));
                        echo "
                        <div class='grid-container'>
                            <div>Report from the " . $date . "</div>
                            <div><button class='btn1' onclick='editWeeklyReport(" . $weeklyIsProcess["wochenreportId"] . ")'><i class='fas fa-edit'></i> Edit</button></div>
                            <div><button class='btn2' onclick='deleteWeeklyReport(" . $weeklyIsProcess["wochenreportId"] . ")'><i class='fas fa-trash'></i> Delete</button></div>
                            <div><button class='btn3' onclick='releaseWeeklyReport(" . $weeklyIsProcess["wochenreportId"] . ")'><i class='fas fa-upload'></i> Release</button></div>
                        </div>
                    ";}
                }else {
                    echo "<button class='addReport' onclick='navigateTo(\"addweeklyjournal\")'>Write your next weekly report</button>";
                }
                if (count($arrayWeeklyIsReleased) > 0){
                    echo "<h2>Released</h2>";
                    foreach ($arrayWeeklyIsReleased as $weeklyIsReleased){
                        $date = date('dS M Y', strtotime($weeklyIsReleased['datum']));
                        echo "
                        <div class='grid-container'>
                            <div>Report from the " . $date . "</div>
                        </div>
                    ";}
                }
            }else {
                echo "<h1>There are no weekly reports</h1>";
                echo "<button class='addReport' onclick='navigateTo(\"addweeklyjournal\")'>Write your first Weekly Raport</button>";
            }     
        ?>
        </div>

    </main>


    <script src="public/js/app.js"></script>
    <script src="public/js/route.js"></script>

</body>

</html>