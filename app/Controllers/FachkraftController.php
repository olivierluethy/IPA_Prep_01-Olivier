<?php

use Google\Service\Classroom\Topic;

class FachkraftController
{
    public function overview(){		
		require_once 'app/Views/general/config.php';

		if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
			if($_SESSION['role'] == 1){
				$Fachkraft = new Fachkraft();
				$Keyword = new Keyword();
						
				/* Get all daily rapports */
				$arrayDailyRaports = $Fachkraft->getDailyRaports()->fetchAll();

				/* Get all weekly rapports */
				$arrayWeeklyRaports = $Fachkraft->getWeeklyRaports()->fetchAll();

				/* Get all apprentices */
				$arrayLernende = $Fachkraft->getAllLernende()->fetchAll();

				/* Get all keywords */
				$arrayTopics = $Keyword->getAllKeywords()->fetchAll();
				
				require 'app/Views/fachkraft/overview.view.php';
			}
		}
	}
}