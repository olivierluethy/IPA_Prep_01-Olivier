<?php
class DailyReport
{
    public $db;

    public function __construct()
    {
        $this->db = connectDatabase();
    }

    public function add_dailyjournal($text, $status){
		$statement = $this->db->prepare('INSERT INTO `journal` (text, status, fk_benutzerId) VALUES (:text, :status, :id)');
		$statement->bindParam(':text', $text, PDO::PARAM_STR);
		$statement->bindParam(':status', $status, PDO::PARAM_STR);
		$statement->bindParam(':id', $_SESSION["id"], PDO::PARAM_STR);
		$statement->execute();

		$journalId = $this->db->lastInsertId();
        return $journalId;
	}

	public function editDailyReport($id, $text, $status){
		$statement = $this->db->prepare('UPDATE journal SET text = :text, status = :status WHERE journalId = :id');
		$statement->bindParam(':text', $text, PDO::PARAM_STR);
		$statement->bindParam(':status', $status, PDO::PARAM_STR);
		$statement->bindParam(':id', $id, PDO::PARAM_STR);
		$statement->execute();

		$statement = $this->db->prepare('DELETE FROM `ausgewaehlte_themen` WHERE fk_journalId = :id');
    	$statement->bindParam(':id', $id, PDO::PARAM_STR);
    	$statement->execute();
	}

    public function add_ausgewaehlte_themen($topic, $journalId){
		if($topic != NULL){
			$statement = $this->db->prepare('INSERT INTO ausgewaehlte_themen (fk_themaId, fk_journalId) VALUES (:themaId, :journalId)');
			$statement->bindParam(':themaId', $topic, PDO::PARAM_STR);
			$statement->bindParam(':journalId', $journalId, PDO::PARAM_STR);
			$statement->execute();
		}
	}

    public function deleteDailyReport($id){
		$statement = $this->db->prepare('DELETE FROM `ausgewaehlte_themen` WHERE fk_journalId = :id');
        $statement->bindParam(':id', $id, PDO::PARAM_STR);
        $statement->execute();

		$statement = $this->db->prepare('DELETE FROM `journal` WHERE journalId = :id');
        $statement->bindParam(':id', $id, PDO::PARAM_STR);
        $statement->execute();
	}

    public function releaseDailyReport($id){
		$statement = $this->db->prepare('UPDATE journal SET status = 1 WHERE journalId = :id');
		$statement->bindParam(':id', $id, PDO::PARAM_STR);
		$statement->execute();
	}

    public function getDailyReport($id){
		$statement = $this->db->prepare('SELECT * FROM journal WHERE journalId = :id');
		$statement->bindParam(':id', $id, PDO::PARAM_STR);
		$statement->execute();
        return $statement;
	}

	public function seeDaily($id){
		$statement = $this->db->prepare('SELECT * FROM journal WHERE journalId = :id');
		$statement->bindParam(':id', $id, PDO::PARAM_STR);
		$statement->execute();
        return $statement;
	}
}