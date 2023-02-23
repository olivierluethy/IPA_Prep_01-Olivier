<?php
$navigationFiller = "/";
$url = "$_SERVER[HTTP_HOST]"; // gibt die URL
$havePort = preg_match('/[0-9]/', $url); // die Seite Localhost hat einen bestimmtem Port, daher auch einen Root Ordner

if (!$havePort) {
    $navigationFiller .= "PA_Journal_Webapplikation/";
}
echo "<button id='burger' onclick='toggleSidebar()'>&#9776;</button>";

echo "<nav id='sidebar'>
        <h2>Journal <br>
            Web-App</h2>
        <hr>";

if(isset($_SESSION['role'])){
    if ($_SESSION['role'] == 0 || $_SESSION['role'] == 1) {
        echo "<input type='text' placeholder='Search'>";
        
        $a = '<button title="Go To Home" onclick="navigateTo(\'home\')"';
        if (preg_match("/home/i", $actual_link)) {
            $a .= ' class="active"';
        }
        $a .= '>Home</button>';
        echo $a;
    }
    if($_SESSION['role'] == 0){
        $a = '<button title="Go To Daily Raports" onclick="navigateTo(\'dailyraport\')"';
        if (preg_match("/dailyraport/i", $actual_link)) {
            $a .= ' class="active"';
        }
        $a .= '>Daily reports</button>';
        echo $a;
    
        $a = '<button title="Go To Daily Raports" onclick="navigateTo(\'weeklyraport\')"';
        if (preg_match("/weeklyraport/i", $actual_link)) {
            $a .= ' class="active"';
        }
        $a .= '>Weekly reports</button>';
        echo $a;
    
        $a = '<button title="Go To My Keywords" onclick="navigateTo(\'keywords\')"';
        if (preg_match("/keywords/i", $actual_link)) {
            $a .= ' class="active"';
        }
        $a .= '>My keywords</button>';
        echo $a;
    }
    if($_SESSION['role'] == 1){
        $a = '<button title="Go To Overview" onclick="navigateTo(\'overview\')"';
        if (preg_match("/overview/i", $actual_link)) {
            $a .= ' class="active"';
        }
        $a .= '>Overview</button>';
        echo $a;
    }
    if($_SESSION['loggedin'] == true){
        $a = '<button title="Go To Logout" onclick="navigateTo(\'logout\')"';
        $a .= '>Logout</button>';
        echo $a;
    }
}
?>
</nav>

<header>
    <div class="grid-container">
        <div>
            <?php
                $links = [
                    'home' => '<h1>Home</h1>',

                    'dailyraport' => '<h1>Daily reports</h1>',
                    'editDailyReport' => '<h1>Edit reports</h1>',

                    'weeklyraport' => '<h1>Weekly reports</h1>',

                    'keywords' => '<h1>Keywords</h1>',
                    'editKeyword' => '<h1>Edit Keyword</h1>',
                    'deleteKeyword' => '<h1>Delete Keyword</h1>',
                    'login' => '<h1>Login</h1>',
                    'editUser' => '<h1>Edit User</h1>',
                    
                    'overview' => '<h1>Overview</h1>',
                    'releasedreports' => '<h1>Released Reports</h1>',

                    'useroverview' => '<h1>User Overview</h1>',

                    'adddailyjournal' => '<h1>Add Daily Report</h1>',
                    'addweeklyjournal' => '<h1>Add Weekly Report</h1>',
                    'addkeyword' => '<h1>Add Keyword</h1>',

                    'editWeeklyRaport' => '<h1>Edit Weekly Reports</h1>',
                ];
                
                $found = false;
                foreach($links as $link => $title) {
                    if (preg_match("/$link/i", $actual_link)) {
                        echo $title;
                        $found = true;
                        break;
                    }
                }
                if(!$found) {
                    echo '<h1>Unknown page</h1>';
                }
            ?>
            
        </div>
        <div></div>
        <div>
            <?php
            if(isset($_SESSION['role'])){
                echo '<img src="' . $_SESSION['profileImageUrl'] . '" class="user-image" />';
                ?>
                <h2>Hey, <?= $_SESSION['full_name'] ?>!</h2>
            <?php } ?>
        </div>
    </div>
</header>