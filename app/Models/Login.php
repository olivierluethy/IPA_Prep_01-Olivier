<?php
class Login
{
    public $db;

    public function __construct()
    {
        $this->db = connectDatabase();
    }

    // Check if the user exists in the database
	public function doesUserExist($email){
        $statement = $this->db->prepare('SELECT * FROM benutzer WHERE email = :email');
        $statement->bindParam(':email', $email, PDO::PARAM_STR);
        $statement->execute();
        return $statement->rowCount() > 0;
    }

    // Gibt den kompletten Benutzerdatensatz zu einer E-Mail zurück (oder false).
    public function getUserByEmail($email){
        $statement = $this->db->prepare('SELECT * FROM benutzer WHERE email = :email LIMIT 1');
        $statement->bindParam(':email', $email, PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    // Legt einen normal registrierten Nutzer (E-Mail/Passwort) an und gibt dessen ID zurück.
    public function registerUser($firstName, $lastName, $email, $passwordHash, $role){
        $fullName = trim($firstName . ' ' . $lastName);
        $statement = $this->db->prepare(
            "INSERT INTO benutzer (email, first_name, last_name, gender, full_name, picture, verifiedEmail, token, password, role)
             VALUES (:email, :first_name, :last_name, '', :full_name, '', 0, '', :password, :role)"
        );
        $statement->bindParam(':email', $email);
        $statement->bindParam(':first_name', $firstName);
        $statement->bindParam(':last_name', $lastName);
        $statement->bindParam(':full_name', $fullName);
        $statement->bindParam(':password', $passwordHash);
        $statement->bindValue(':role', (int) $role, PDO::PARAM_INT);
        $statement->execute();
        return $this->db->lastInsertId();
    }

    // If the user is not in the database, add them
    public function addUser($email, $firstName, $lastName, $gender, $name, $profileImageUrl, $verifiedEmail, $token){
        $statement = $this->db->prepare("INSERT INTO benutzer (email, first_name, last_name, gender, full_name, picture, verifiedEmail, token, role) 
        VALUES (:email, :first_name, :last_name, :gender, :full_name, :picture, :verifiedEmail, :token, 0)");
        $statement->bindParam(':email', $email);
        $statement->bindParam(':first_name', $firstName);
        $statement->bindParam(':last_name', $lastName);
        $statement->bindParam(':gender', $gender);
        $statement->bindParam(':full_name', $name);
        $statement->bindParam(':picture', $profileImageUrl);
        $statement->bindParam(':verifiedEmail', $verifiedEmail);
        $statement->bindParam(':token', $token);
        $statement->execute();
    }
}