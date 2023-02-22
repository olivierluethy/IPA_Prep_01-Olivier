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
    <title>Journal - Released Reports</title>
</head>

<body>
    <button id="burger" onclick="toggleSidebar()">&#9776;</button>

    <nav id="sidebar">
        <h2>Journal <br>
            Web-App</h2>
        <hr>
        <input type="text" placeholder="Search">
        <button onclick="navigateTo('home')" class="active">Home</button>
        <button onclick="navigateTo('overview')">Overview</button>
    </nav>

    <header>
        <div class="grid-container">
            <div>
                <img src="images/daily-report.png" alt="">
                <h1>Released reports</h1>
            </div>
            <div></div>
            <div>
                <img src="" alt="">
                <h2>Hey, user!</h2>
            </div>
        </div>
    </header>

    <main>
        <h2>Daily reports</h2>
        <div class="grid-container">
            <div>Report from the {Date} written on the {Date}</div>
            <div><button class="btn1"><i class="fas fa-edit"></i> See</button></div>
        </div>

        <h2>Weekly reports</h2>
        <div class="grid-container">
            <div>Report from the {Date} written on the {Date}</div>
            <div><button class="btn1"><i class="fas fa-edit"></i> See</button></div>
        </div>
    </main>

    <script src="public/js/app.js"></script>
    <script src="public/js/route.js"></script>

</body>

</html>