// Define a single function to handle all navigation
function navigateTo(page) {
    // change the current location to the specified page
    window.location = page;
}

/* For keywords */
function editKeyword(id){
    location.href = "editkeyword?id=" + id;
}

function deleteKeyword(id){
    location.href = "deleteKeyword?id=" + id;
}

/* For dailyreport */
function editDailyReport(id){
    location.href = "editDailyReport?id=" + id;
}

function deleteDailyReport(id){
    location.href = "deleteDailyReport?id=" + id;
}

function releaseDailyReport(id){
    location.href = "releaseDailyReport?id=" + id;
}

/* For weeklyreport */
function editWeeklyReport(id){
    location.href = "editWeeklyRaport?id=" + id;
}

function deleteWeeklyReport(id){
    location.href = "deleteWeeklyRaport?id=" + id;
}

function releaseWeeklyReport(id){
    location.href = "releaseWeeklyReport?id=" + id;
}

/* For users in overview */
function editUser(id){
    location.href = "editUser?id=" + id;
}

function deleteUser(id){
    location.href = "deleteUser?id=" + id;
}

/* To see all of daily and weekly for fachkraft */
function seeDaily(id){
    location.href = "seeDaily?id=" + id;
}

function seeWeekly(id){
    location.href = "seeWeekly?id=" + id;
}