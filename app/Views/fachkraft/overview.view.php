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

<button id="burger" onclick="toggleSidebar()">&#9776;</button>

    <?php
$actual_link = basename(__FILE__); // aktueller dateiname (wird für header.php benötigt)
include(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "General" . DIRECTORY_SEPARATOR . "navside.view.php");
?>

<main>
    <?php
    if (count($arrayDailyRaports) > 0 || count($arrayWeeklyRaports) > 0): ?>
        
        <table class="order">
            <tr>
                <td>
                    <h2>Sorted by:</h2>
                </td>
                <td>
                    <!-- Choose sorting option -->
                    <form action="" method="GET">
                        
                        <?php
                        if(count($arrayLernende) > 0){
                            echo "<select name='sort'>
                            <option value=''>-- Employer --</option>";
                            foreach($arrayLernende as $lernende){
                                echo "<option value='" . $lernende["full_name"] . "'></option>";
                            }
                            echo "</select>";
                        }
                        ?>

                        <select name="sort">
                            <option value="">-- Date --</option>
                            <option value="dateNewest"
                                <?php if (isset($_GET['sort']) && $_GET['sort'] == "dateNewest"){ echo "selected"; }?>>
                                Date Newest</option>
                            <option value="dateOldest"
                                <?php if (isset($_GET['sort']) && $_GET['sort'] == "dateOldest"){ echo "selected"; }?>>
                                Date Oldest</option>
                        </select>

                        <?php
                        if(count($arrayTopics) > 0){
                            echo "<select name='sort'>
                                <option value=''>-- Topic --</option>";
                                echo "<option value='" . $topic["thema"] . "'></option>";
                                foreach($arrayTopics as $topic){
                                    echo "<option value='" . $topic["thema"] . "'></option>";
                                }
                            echo "</select>";
                        }
                        ?>

                        <select name="sort">
                            <option value="">-- Wochen / Journal --</option>
                            <option value="dateNewest"
                                <?php if (isset($_GET['sort']) && $_GET['sort'] == "dateNewest"){ echo "selected"; }?>>
                                Date Newest</option>
                            <option value="dateOldest"
                                <?php if (isset($_GET['sort']) && $_GET['sort'] == "dateOldest"){ echo "selected"; }?>>
                                Date Oldest</option>
                        </select>
                        <button title='Sort all tasks' type='submit'>Sort <i class='fa fa-sort'></i></button>
                    </form>
                </td>
            </tr>
        </table>
<?php
            $sort_option = "datum DESC";
            if (isset($_GET['sort']))
            {
                if ($_GET['sort'] == "dateNewest")
                {
                    $sort_option = "datum ASC";
                }
                else if ($_GET['sort'] == "dateOldest")
                {
                    $sort_option = "datum DESC";
                }
            }

            // $fachkraft = new Fachkraft();
            // $getObjects = $fachkraft->sortTask($sort_option);
            // $getObjects = $getObjects->fetchAll();


        if($dailyRaportsAmount > 0): ?>
            <h2>Daily reports</h2>
            <?php
            foreach($arrayDailyRaports as $dailyreports):
                $date = date('dS M Y', strtotime($dailyreports['datum']));
                ?>
                <div class='grid-container'>
                    <div><?php echo "Report from " . $dailyreports['full_name'] . " written on the " . $date; ?></div>
                    <div><button class='btn1' onclick='seeDaily(<?php echo $dailyreports['journalId']; ?>)'><i class='fas fa-eye'></i> See</button></div>
                </div>
            <?php endforeach;
        endif;
        if($weeklyraportsAmount > 0): ?>
            <h2>Weekly reports</h2>
            <?php
            foreach($arrayWeeklyRaports as $weeklyraports):
                $date = date('dS M Y', strtotime($weeklyraports['datum']));
                ?>
                <div class='grid-container'>
                    <div><?php echo "Report from " . $weeklyraports['full_name'] . " written on the " . $date; ?></div>
                    <div><button class='btn1' onclick='seeWeekly(<?php echo $weeklyraports['wochenreportId']; ?>)'><i class='fas fa-eye'></i> See</button></div>
                </div>
            <?php endforeach;
        endif;
    else:
        echo "<h1>There're no reports available</h1>";
    endif;
    ?>
</main>

    <script src="public/js/app.js"></script>
    <script src="public/js/route.js"></script>

</body>

</html>