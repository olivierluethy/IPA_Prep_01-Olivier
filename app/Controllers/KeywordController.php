<?php

class KeywordController
{
    public function keywords(){		
		require_once 'app/Views/general/config.php';

		if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
			$Keyword = new Keyword();
		
			$arrayKeywords = $Keyword->getAllKeywords()->fetchAll();
			
			require 'app/Views/lernender/mykeywords.view.php';	
		}else {
			header("Location: login");
		}
	}

	public function addkeyword(){
		require_once 'app/Views/general/config.php';

		if (!isset($_SESSION['user_token'])) {
			header("Location: login");
			die();
		}else{
			$Keyword = new Keyword();

			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				$thema = e(post('thema'));

				$Keyword->add_keywords($thema);
	
				header('Location: keywords');
			}

			require 'app/Views/lernender/addkeyword.view.php';
		}
	}

	public function editkeywords(){
		require_once 'app/Views/general/config.php';

		if (!isset($_SESSION['user_token'])) {
			header("Location: login");
			die();
		}else{
			$id = $_GET['id'];

			$Keyword = new Keyword();

			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				$titel = e(post('thema'));
			
				$Keyword->editKeyword($id, $titel);

				header('Location: keywords');	
			}else{
				/* Get Data to edit */
				$getKeyword = $Keyword -> getKeyword($id)->fetchAll();
			}
			require 'app/Views/lernender/editKeyword.view.php';
		}
	}

	public function deleteKeyword(){
		require_once 'app/Views/general/config.php';

		if (!isset($_SESSION['user_token'])) {
			header("Location: login");
			die();
		}else{
			$Keyword = new Keyword();
			$pdo = connectDatabase();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$id = $_GET['id'];

			$Keyword->deleteKeyword($id);
			
			header('Location: keywords');
		}
	}
}