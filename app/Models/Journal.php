<?php
class Journal
{
    public $db;

    public function __construct()
    {
        $this->db = connectDatabase();
    }

	public function getAllDailyJournalsInProcess(){
		$statement = $this->db->prepare('SELECT journal.journalId, journal.text, journal.datum, benutzer.full_name FROM journal 
		INNER JOIN benutzer ON benutzer.benutzerId = journal.fk_benutzerId WHERE journal.status = 0 AND journal.fk_benutzerId = :id');
		$statement->bindParam(':id', $_SESSION["id"], PDO::PARAM_STR);
		$statement->execute();
        return $statement;
	}

	public function getAllDailyJournalsInRelease(){
		$statement = $this->db->prepare('SELECT journal.journalId, journal.text, journal.datum, benutzer.full_name FROM journal 
		INNER JOIN benutzer ON benutzer.benutzerId = journal.fk_benutzerId WHERE journal.status = 1 AND journal.fk_benutzerId = :id');
		$statement->bindParam(':id', $_SESSION["id"], PDO::PARAM_STR);
		$statement->execute();
        return $statement;
	}
}