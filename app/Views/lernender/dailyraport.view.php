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
    <title>Journal - Daily Raport</title>
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
if ($_SESSION['role'] == 0) {
    // Lernender view
    if (count($arrayJournalsInProcess) > 0 || count($arrayJournalIsReleased) > 0) {
        if (count($arrayJournalsInProcess) > 0) {
            echo "<h2>Still in process</h2>";
            foreach ($arrayJournalsInProcess as $journalInProcess) {
                $date = date('dS M Y', strtotime($journalInProcess['datum']));
                echo "<div class='grid-container'>
                          <div>Report from the $date</div>
                          <div><button class='btn1' onclick='editDailyReport({$journalInProcess["journalId"]})'><i class='fas fa-edit'></i> Edit</button></div>
                          <div><button class='btn2' onclick='deleteDailyReport({$journalInProcess["journalId"]})'><i class='fas fa-trash'></i> Delete</button></div>
                          <div><button class='btn3' onclick='releaseDailyReport({$journalInProcess["journalId"]})'><i class='fas fa-upload'></i> Release</button></div>
                      </div>";
            }
        } else {
            echo "<button class='addReport' onclick='navigateTo(\"adddailyjournal\")'>Write your next daily report</button>";
        }
        if (count($arrayJournalIsReleased) > 0) {
            echo "<h2>Released</h2>";
            foreach ($arrayJournalIsReleased as $journalIsReleased) {
                $date = date('dS M Y', strtotime($journalIsReleased['datum']));
                echo "<div class='grid-container'>
                          <div>Released on the $date</div>
                      </div>";
            }
        }
    } else {
        echo "<h1>There are no reports</h1>";
        echo "<button class='addReport' onclick='navigateTo(\"adddailyjournal\")'>Write your first Daily Report</button>";
    }
} else if ($_SESSION['role'] == 1) {
    // Fachkraft view
    // TODO: implement Fachkraft view
}
?>
        </div>
    </main>

    <script src="public/js/app.js"></script>
    <script src="public/js/route.js"></script>

</body>

</html>