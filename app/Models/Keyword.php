<?php
class Keyword
{
    public $db;

    public function __construct()
    {
        $this->db = connectDatabase();
    }

    public function getAllKeywords(){
		$statement = $this->db->prepare('SELECT * FROM thema WHERE fk_benutzerId = :id');
		$statement->bindParam(':id', $_SESSION["id"], PDO::PARAM_STR);
		$statement->execute();
        return $statement;
	}

	public function deleteKeyword($id){
		$statement = $this->db->prepare('DELETE FROM `ausgewaehlte_themen` WHERE fk_themaId = :id');
        $statement->bindParam(':id', $id, PDO::PARAM_STR);
        $statement->execute();

		$statement = $this->db->prepare('DELETE FROM `thema` WHERE themaId = :id');
        $statement->bindParam(':id', $id, PDO::PARAM_STR);
        $statement->execute();
	}

    public function add_keywords($thema){
		$statement = $this->db->prepare('INSERT INTO `thema` (thema, fk_benutzerId) VALUES (:thema, :id)');
		$statement->bindParam(':thema', $thema, PDO::PARAM_STR);
		$statement->bindParam(':id', $_SESSION["id"], PDO::PARAM_STR);
		$statement->execute();
	}

    public function getKeyword($id){
		$statement = $this->db->prepare('SELECT * FROM thema WHERE themaId = :id');
		$statement->bindParam(':id', $id, PDO::PARAM_STR);
		$statement->execute();
        return $statement;
	}

	public function editKeyword($id, $titel){
		$statement = $this->db->prepare('UPDATE thema SET thema = :thema WHERE themaId = :id');
		$statement->bindParam(':thema', $titel, PDO::PARAM_STR);
		$statement->bindParam(':id', $id, PDO::PARAM_STR);
		$statement->execute();
	}

	public function getSelectedKeywords($journalId){
		$statement = $this->db->prepare('SELECT DISTINCT thema.themaId, thema.thema FROM thema
		INNER JOIN ausgewaehlte_themen ON fk_themaId = thema.themaId WHERE ausgewaehlte_themen.fk_journalId = :id');
		$statement->bindParam(':id', $journalId, PDO::PARAM_STR);
		$statement->execute();
        return $statement;
	}
}