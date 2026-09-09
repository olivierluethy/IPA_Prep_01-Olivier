<?php
class Admin
{
    public $db;

    public function __construct()
    {
        $this->db = connectDatabase();
    }

	public function getAllUsers(){
		$statement = $this->db->prepare('SELECT * FROM benutzer
        WHERE benutzer.role != 2');
		$statement->execute();
        return $statement;
	}

    public function editUser($id, $role){
        $statement = $this->db->prepare('UPDATE benutzer SET role = :role WHERE benutzerId = :id');
        $statement->bindValue(':role', (int) $role, PDO::PARAM_INT);
		$statement->bindValue(':id', (int) $id, PDO::PARAM_INT);
		$statement->execute();
    }

    public function deleteUser($id){
        $statement = $this->db->prepare('DELETE FROM `benutzer` WHERE benutzerId = :id');
        $statement->bindParam(':id', $id, PDO::PARAM_STR);
        $statement->execute();
    }

    public function getUser($id){
        $statement = $this->db->prepare('SELECT * FROM benutzer
        WHERE benutzer.benutzerId = :id');
        $statement->bindParam(':id', $id, PDO::PARAM_STR);
		$statement->execute();
        return $statement;
    }
}