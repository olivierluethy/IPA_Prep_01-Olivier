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
    <title>Journal - Edit Daily Report</title>
</head>

<body>
    <?php
$actual_link = basename(__FILE__); // aktueller dateiname (wird für header.php benötigt)
include ("general/navside.view.php");
?>

    <main>
        <form action="editDailyReport?id=<?= $getDailyReport[0][0] ?>" method="POST">
            <h1>Edit Daily Report</h1>
            <h2>Text:</h2><br>
            <textarea name="text" id="text" cols="30" rows="10"
                placeholder="For example: I have completed ..."><?php echo $getDailyReport[0][1] ?></textarea>

            <h2>Keywords:</h2>
            <?php 
            $pickedKeywordsIds = array_column($getPickedKeywords, 'themaId');
            foreach ($getKeywords as $keyword) {
                $checked = in_array($keyword['themaId'], $pickedKeywordsIds);
                echo "<input type='checkbox' name='topics[]' id=" . $keyword['themaId'] . " " . ($checked ? "checked" : "") . " value=" . $keyword['themaId'] .">";
                echo "<label for=" . $keyword['themaId'] . ">" . $keyword['thema'] . "</label>";
            }
            echo "<br><input type='submit'>";
            ?>
        </form>
    </main>

    <script src="public/js/app.js"></script>
    <script src="public/js/route.js"></script>
    <script src="ckeditor/ckeditor.js"></script>

    <script>
    CKEDITOR.replace('text');
    </script>

</body>

</html>