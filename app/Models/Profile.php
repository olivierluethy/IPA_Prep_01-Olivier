<?php

class Profile
{
    public $db;

    public function __construct()
    {
        $this->db = connectDatabase();
    }

    /** Einzelnen Benutzer anhand seiner ID laden. */
    public function getById(int $id)
    {
        $stmt = $this->db->prepare('SELECT * FROM benutzer WHERE benutzerId = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /** Prüft, ob die E-Mail bereits einem anderen Benutzer gehört. */
    public function emailTakenByOther(string $email, int $id): bool
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM benutzer WHERE email = :email AND benutzerId <> :id');
        $stmt->execute([':email' => $email, ':id' => $id]);
        return (int) $stmt->fetchColumn() > 0;
    }

    /** Kontodaten aktualisieren (Vorname, Nachname, E-Mail, voller Name). */
    public function updateAccount(int $id, string $first, string $last, string $email): bool
    {
        $fullName = trim($first . ' ' . $last);
        $stmt = $this->db->prepare(
            'UPDATE benutzer
                SET first_name = :first,
                    last_name  = :last,
                    email      = :email,
                    full_name  = :full_name
              WHERE benutzerId = :id'
        );
        return $stmt->execute([
            ':first'     => $first,
            ':last'      => $last,
            ':email'     => $email,
            ':full_name' => $fullName,
            ':id'        => $id,
        ]);
    }

    /** Passwort-Hash aktualisieren. */
    public function updatePassword(int $id, string $hash): bool
    {
        $stmt = $this->db->prepare('UPDATE benutzer SET password = :hash WHERE benutzerId = :id');
        return $stmt->execute([':hash' => $hash, ':id' => $id]);
    }

    /** Profilbild-Pfad aktualisieren. */
    public function updatePicture(int $id, string $path): bool
    {
        $stmt = $this->db->prepare('UPDATE benutzer SET picture = :path WHERE benutzerId = :id');
        return $stmt->execute([':path' => $path, ':id' => $id]);
    }
}
