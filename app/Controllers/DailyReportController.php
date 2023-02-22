<?php

class DailyReportController
{
    public function dailyraport() {
		require_once 'app/Views/general/config.php';
	
		if (!isset($_SESSION['access_token']) || empty($_SESSION['access_token'])) {
			header("Location: login");
			return;
		}
	
		$Journal = new Journal();
		$arrayJournalsInProcess = $Journal->getAllDailyJournalsInProcess()->fetchAll();
		$arrayJournalIsReleased = $Journal->getAllDailyJournalsInRelease()->fetchAll();
	
		require 'app/Views/lernender/dailyraport.view.php';
	}
	
	public function adddailyjournal() {
		require_once 'app/Views/general/config.php';
	
		// Redirect to login page if user is not logged in
		if (!isset($_SESSION['access_token']) || empty($_SESSION['access_token'])) {
			header("Location: login");
			die();
		}
	
		$Keyword = new Keyword();
		$arrayTopics = $Keyword->getAllKeywords()->fetchAll();
	
		// If the form has been submitted
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			// Sanitize and validate input
			$text = e(post('text'));
			$topics = !empty($_POST['topics']) ? $_POST['topics'] : array();
	
			// Noch am laufen
			$status = 0;
	
			$DailyReport = new DailyReport();
			$journalId = $DailyReport->add_dailyjournal($text, $status);
	
			foreach ($topics as $topic) {
				$DailyReport->add_ausgewaehlte_themen($topic, $journalId);
			}
	
			header('Location: dailyraport');
		}
	
		require 'app/Views/lernender/adddailyjournal.view.php';
	}	

    public function editDailyReport(){
		require_once 'app/Views/general/config.php';
		if (!isset($_SESSION['user_token'])) {
			header("Location: login");
			die();
		}
		
		$id = $_GET['id'];
		$dailyReport = new DailyReport();
		
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$text = e(post('text'));
			$topics = post('topics', []);
		
			if (!is_array($topics)) {
				$topics = [$topics];
			}
			
			$status = 0;
			$dailyReport->editDailyReport($id, $text, $status);
			
			foreach ($topics as $topic) {
				$dailyReport->add_ausgewaehlte_themen($topic, $id);
			}
			
			header('Location: dailyraport');
		} else {
			$getDailyReport = $dailyReport->getDailyReport($id)->fetchAll();
		
			$keyword = new Keyword();
			$getKeywords = $keyword->getAllKeywords()->fetchAll();
			$getPickedKeywords = $keyword->getSelectedKeywords($id)->fetchAll();
		}
		
		require 'app/Views/lernender/editDailyJournal.view.php';
	}		

	public function deleteDailyReport(){
		require_once 'app/Views/general/config.php';

		// Redirect to login page if user is not logged in
		if (!isset($_SESSION['access_token']) || empty($_SESSION['access_token'])) {
			header("Location: login");
			die();
		}else{
			$DailyReport = new DailyReport();
			$pdo = connectDatabase();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$id = $_GET['id'];

			$DailyReport->deleteDailyReport($id);
			
			header('Location: dailyraport');
		}
	}

	public function releaseDailyReport(){
		require_once 'app/Views/general/config.php';

		// Redirect to login page if user is not logged in
		if (!isset($_SESSION['access_token']) || empty($_SESSION['access_token'])) {
			header("Location: login");
			die();
		}else{
			$DailyReport = new DailyReport();
			$pdo = connectDatabase();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$id = $_GET['id'];

			$DailyReport->releaseDailyReport($id);
			
			header('Location: dailyraport');
		}
	}

	public function seeDaily(){
		require_once 'app/Views/general/config.php';

		$id = $_GET['id'];

		$DailyReport = new DailyReport();
		$dayArray = $DailyReport->seeDaily($id)->fetchAll();

		$Keyword = new Keyword();
		$getKeywords = $Keyword->getAllKeywords()->fetchAll();

		$getPickedKeywords = $Keyword->getSelectedKeywords($id)->fetchAll();

		require 'app/Views/fachkraft/seeDaily.view.php';
	}
}