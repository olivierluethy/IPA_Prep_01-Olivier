<?php

class JournalController
{
	/* Für Lernender */
	public function home(){
		require_once 'app/Views/general/config.php';

		if (!isset($_SESSION['token'])) {
			header("Location: login");
			die();
		}else {
			$Journal = new Journal();
			$arrayJournalsInRelease = $Journal->getAllDailyJournalsInRelease()->fetchAll();

			$Wochenrapport = new WeeklyReport();
			$arrayWeeklyIsInRelease = $Wochenrapport->getAllWeeklyInRelease()->fetchAll();

			require 'app/Views/home.view.php';
		}
	}

	public function releasedreports(){		
		require_once 'app/Views/general/config.php';

		require 'app/Views/fachkraft/releasedreports.view.php';
	}
}