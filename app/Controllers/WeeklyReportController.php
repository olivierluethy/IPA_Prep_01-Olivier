<?php

class WeeklyReportController
{
    public function weeklyraport(){		
		require_once 'app/Views/general/config.php';

		if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
			$WeeklyReport = new WeeklyReport();
			
			$arrayWeeklyInProcess = $WeeklyReport->getAllWeeklyInProcess()->fetchAll();
			$arrayWeeklyIsReleased = $WeeklyReport->getAllWeeklyInRelease()->fetchAll();

			require 'app/Views/lernender/weeklyraport.view.php';
		}else{
			header("Location: login");
		}
	}

	public function addweeklyjournal(){
		require_once 'app/Views/general/config.php';

		// Redirect to login page if user is not logged in
		if (!isset($_SESSION['access_token']) || empty($_SESSION['access_token'])) {
			header("Location: login");
			die();
		}else{
			$WeeklyReport = new WeeklyReport();

			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				$calendar_week = e(post('calendar_week'));
				$completed_tasks = e(post('completed_tasks'));
				$still_in_work = e(post('still_in_work'));
				$reflection = e(post('reflection'));
				$issues = e(post('issues'));

				$status = 0;

				$WeeklyReport->add_weeklyraport($calendar_week, $completed_tasks, $still_in_work, $reflection, $issues, $status);
	
				header('Location: weeklyraport');
			}

			require 'app/Views/lernender/addweeklyjournal.view.php';
		}
	}

    public function editWeeklyRaport(){
		require_once 'app/Views/general/config.php';

		// Redirect to login page if user is not logged in
		if (!isset($_SESSION['access_token']) || empty($_SESSION['access_token'])) {
			header("Location: login");
			die();
		}else{
			$id = $_GET['id'];

			$WeeklyReport = new WeeklyReport();

			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				$calendar_week = e(post('calendar_week'));
				$completed_tasks = e(post('completed_tasks'));
				$still_in_work = e(post('still_in_work'));
				$reflection = e(post('reflection'));
				$issues = e(post('issues'));
			
				$WeeklyReport->editWeeklyReport($calendar_week, $completed_tasks, $still_in_work, $reflection, $issues, $id);

				header('Location: weeklyraport');	
			}else{
				/* Get Data to edit */
				$getWeeklyReport = $WeeklyReport -> getWeeklyReport($id)->fetchAll();
			}
			require 'app/Views/lernender/editWeeklyJournal.view.php';
		}
	}

	public function deleteWeeklyRaport(){
		require_once 'app/Views/general/config.php';

		// Redirect to login page if user is not logged in
		if (!isset($_SESSION['access_token']) || empty($_SESSION['access_token'])) {
			header("Location: login");
			die();
		}else{
			$WeeklyReport = new WeeklyReport();
			$pdo = connectDatabase();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$id = $_GET['id'];

			$WeeklyReport->deleteWeeklyReport($id);
			
			header('Location: weeklyraport');
		}
	}

	public function releaseWeeklyReport(){
		require_once 'app/Views/general/config.php';

		// Redirect to login page if user is not logged in
		if (!isset($_SESSION['access_token']) || empty($_SESSION['access_token'])) {
			header("Location: login");
			die();
		}else{
			$WeeklyReport = new WeeklyReport();
			$pdo = connectDatabase();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$id = $_GET['id'];

			$WeeklyReport->releaseWeeklyReport($id);
			
			header('Location: weeklyraport');
		}
	}

	public function seeWeekly(){
		require_once 'app/Views/general/config.php';

		$id = $_GET['id'];

		$WeeklyReport = new WeeklyReport();
		$WeeklyReport = $WeeklyReport->seeWeekly($id)->fetchAll();

		require 'app/Views/lernender/seeWeekly.view.php';
	}
}