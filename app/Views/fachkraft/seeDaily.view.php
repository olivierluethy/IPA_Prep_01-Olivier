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
    <title>Journal - Overview</title>
</head>

<body>
    <?php
$actual_link = basename(__FILE__); // aktueller dateiname (wird für header.php benötigt)
include ("general/navside.view.php");
?>

    <main>
        <h1>Text:</h1>
        <textarea name="" id="text" cols="30" rows="10" readonly><?php echo $dayArray[0][1] ?></textarea>

        <h1>Selected topics:</h1>
        <?php 
            $pickedKeywordsIds = array_column($getPickedKeywords, 'themaId');
            foreach ($getKeywords as $keyword) {
                $checked = in_array($keyword['themaId'], $pickedKeywordsIds);
                echo "<input type='checkbox' name='topics[]' id=" . $keyword['themaId'] . " " . ($checked ? "checked" : "") . " disabled value=" . $keyword['themaId'] .">";
                echo "<label for=" . $keyword['themaId'] . ">" . $keyword['thema'] . "</label>";
            }
            ?>
    </main>

    <script src="public/js/app.js"></script>
    <script src="ckeditor/ckeditor.js"></script>
    <script src="public/js/route.js"></script>

    <script>
    CKEDITOR.replace('text');
    </script>

</body>

</html>