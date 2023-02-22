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
    <title>Journal - Add Weekly Journal</title>
</head>

<body>
    <?php
$actual_link = basename(__FILE__); // aktueller dateiname (wird für header.php benötigt)
include(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "General" . DIRECTORY_SEPARATOR . "navside.view.php");
?>

    <main>
        <form action="addweeklyjournal" method="POST">
            <h2>Calendar week:</h2>
            <input type="number" name="calendar_week" id="calendar_week" min="1" max="52"><br><br><br><br>

            <h2>Completed tasks:</h2>
            <textarea name="completed_tasks" id="completed_tasks" cols="30" rows="10"
                placeholder="For example: I have completed ..."></textarea>

            <h2>Still in work:</h2>
            <textarea name="still_in_work" id="still_in_work" cols="30" rows="10"
                placeholder="For example: I'm still working on ..."></textarea>

            <h2>Reflection:</h2>
            <textarea name="reflection" id="reflection" cols="30" rows="10" placeholder="For example: It was ..."></textarea>

            <h2>Issues:</h2>
            <textarea name="issues" id="issues" cols="30" rows="10"
                placeholder="For example: I had problems with ..."></textarea><br>
            <input type="submit" class="send" value="+ Add Weekly Report">
        </form>
    </main>

    <script src="public/js/app.js"></script>
    <script src="public/js/route.js"></script>
    <script src="ckeditor/ckeditor.js"></script>

    <script>
    CKEDITOR.replace('completed_tasks');
    CKEDITOR.replace('still_in_work');
    CKEDITOR.replace('reflection');
    CKEDITOR.replace('issues');
    </script>

</body>

</html>