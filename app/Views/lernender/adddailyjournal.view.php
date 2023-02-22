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
    <title>Journal - Add Daily Report</title>
</head>

<body>
    <?php
$actual_link = basename(__FILE__); // aktueller dateiname (wird für header.php benötigt)
include(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "General" . DIRECTORY_SEPARATOR . "navside.view.php");
?>

    <main>
        <form action="adddailyjournal" method="POST">
            <h2>Write here your text ...</h2>
            <textarea name="text" id="text" cols="30" rows="10" placeholder="For example: My day was ..."></textarea>

            <h2>Topics:</h2>
            <?php 
            if(count($arrayTopics) > 0){
                foreach ($arrayTopics as $topic) {
                    echo "<input id=" . $topic['themaId'] ." type='checkbox' name='topics[]' value=" . $topic['themaId'] .">";
                    echo "<label for=" . $topic['themaId'] .">" . $topic['thema'] . "</label><br>";
                }
                echo "<br><input type='submit' class='send' value='+ Add your daily report'>";
            }else{?>
            <div class="noData">
                <h1>No keywords</h1>
                <h3>Please enter under the 'my keywords' section a keyword to proceed</h3>
            </div>
            <?php } ?>
        </form>
    </main>

    <script src="public/js/app.js"></script>
    <script src="ckeditor/ckeditor.js"></script>
    <script src="public/js/route.js"></script>

    <script>
    CKEDITOR.replace('text');
    </script>

</body>

</html>