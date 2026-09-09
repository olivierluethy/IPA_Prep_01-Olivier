<?php
require 'core/bootstrap.php';

$routes = [
	'' => 'JournalController@home',

	/* Für Lernender*/
	'home' => 'JournalController@home',
	'dailyraport' => 'DailyReportController@dailyraport',
	'weeklyraport' => 'WeeklyReportController@weeklyraport',

	'keywords' => 'KeywordController@keywords',
	'editKeyword' => 'KeywordController@editkeywords',
	'deleteKeyword' => 'KeywordController@deleteKeyword',

	'editWeeklyRaport' => 'WeeklyReportController@editWeeklyRaport',
	'deleteWeeklyRaport' => 'WeeklyReportController@deleteWeeklyRaport',
	'releaseWeeklyReport' => 'WeeklyReportController@releaseWeeklyReport',

	'editUser' => 'AdminController@editUser',
	'deleteUser' => 'AdminController@deleteUser',

	'editDailyReport' => 'DailyReportController@editDailyReport',
	'deleteDailyReport' => 'DailyReportController@deleteDailyReport',
	'releaseDailyReport' => 'DailyReportController@releaseDailyReport',

	'adddailyjournal' => 'DailyReportController@adddailyjournal',
	'addweeklyjournal' => 'WeeklyReportController@addweeklyjournal',
	'addkeyword' => 'KeywordController@addkeyword',

	/* Für Fachkraft */
	'overview' => 'FachkraftController@overview',
	'releasedreports' => 'JournalController@releasedreports',
	'seeDaily' => 'DailyReportController@seeDaily',
	'seeWeekly' => 'WeeklyReportController@seeWeekly',

	/* Für Admin */
	'useroverview' => 'AdminController@useroverview',

	/* Für alle */
	'logout' => 'LoginController@logout',
	'login' => 'LoginController@login',

	/* Normale E-Mail/Passwort-Anmeldung */
	'register' => 'LoginController@register',
	'authenticate' => 'LoginController@authenticate',

	'addUser' => 'LoginController@addUser',
	'doesUserExist' => 'LoginController@doesUserExist',
];

$db = [
	'name'     => 'journal',
	'username' => 'root',
	'password' => '',
];

$router = new Router($routes);
$router->run($_GET['url'] ?? '');