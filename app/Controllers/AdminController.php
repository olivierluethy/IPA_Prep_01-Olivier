<?php

class AdminController
{
    public function useroverview()
	{
		require_once 'app/Views/general/config.php';

		if (!isset($_SESSION['user_token'])) {
			header("Location: login");
			die();
		}

		if ($_SESSION['role'] != 2) {
			header("Location: home");
			die();
		}

		$Admin = new Admin();
		$arrayUsers = $Admin->getAllUsers()->fetchAll();
		require 'app/Views/useroverview.view.php';
	}


	public function editUser() {
		if (!isset($_SESSION['user_token'])) {
			header("Location: login");
			die();
		}
	
		if ($_SESSION['role'] == 0 || $_SESSION['role'] == 1) {
			header("Location: home");
			die();
		}
	
		require_once 'app/Views/general/config.php';
	
		$id = $_GET['id'];
	
		$Admin = new Admin();
	
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$role = e(post('role'));
			$Admin->editUser($id, $role);
			header('Location: useroverview');
			die();
		}
	
		/* Get Data to edit */
		$getUser = $Admin->getUser($id)->fetchAll();
	
		require 'app/Views/editUser.view.php';
	}

	public function deleteUser() {
		if (!isset($_SESSION['user_token'])) {
			header("Location: login");
			die();
		}
	
		if ($_SESSION['role'] == 0 || $_SESSION['role'] == 1) {
			header("Location: home");
			die();
		}
	
		require_once 'app/Views/general/config.php';
	
		$Admin = new Admin();
		$pdo = connectDatabase();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
		$id = $_GET['id'];
		$Admin->deleteUser($id);
	
		header('Location: useroverview');
	}	
}