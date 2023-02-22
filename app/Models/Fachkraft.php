<?php
class Fachkraft
{
    public $db;

    public function __construct()
    {
        $this->db = connectDatabase();
    }

	public function getDailyRaports(){
		$statement = $this->db->prepare('SELECT journal.journalId, journal.text, journal.datum, benutzer.full_name FROM journal 
		INNER JOIN benutzer ON benutzer.benutzerId = journal.fk_benutzerId WHERE journal.status = 1');
		$statement->execute();
        return $statement;
	}

	public function getWeeklyRaports(){
		$statement = $this->db->prepare('SELECT wochenreport.wochenreportId, wochenreport.datum, benutzer.full_name FROM wochenreport 
		INNER JOIN benutzer ON benutzer.benutzerId = wochenreport.fk_benutzerId WHERE wochenreport.status = 1');
		$statement->execute();
        return $statement;
	}

    public function getAllLernende(){
        $statement = $this->db->prepare('SELECT * FROM benutzer
        WHERE benutzer.role = 0');
		$statement->execute();
        return $statement;
    }

	// Sort Algorithm
    public function sortTask($sort_option){
        // $statement = $this->db->prepare("SELECT * FROM aufgabe WHERE fk_benutzerId = :benutzerId AND status = 0 ORDER BY $sort_option");
        // $statement->bindParam(':benutzerId', $_SESSION["id"], PDO::PARAM_STR);
        // $statement->execute();
        // return $statement;
    }
}