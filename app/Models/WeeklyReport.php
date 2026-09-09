<?php
class WeeklyReport
{
    public $db;

    public function __construct()
    {
        $this->db = connectDatabase();
    }

    /* Alle Wochenberichte des aktuellen Lernenden im Status "Entwurf" (0). */
    public function getAllWeeklyInProcess(){
        $statement = $this->db->prepare('SELECT wochenreport.wochenreportId, wochenreport.kalenderwoche, wochenreport.erledigte_arbeiten,
        wochenreport.laufende_arbeiten, wochenreport.reflexion, wochenreport.aufgetretene_probleme,
        wochenreport.status, wochenreport.datum, benutzer.full_name FROM wochenreport
        INNER JOIN benutzer ON benutzer.benutzerId = wochenreport.fk_benutzerId
        WHERE wochenreport.status = 0 AND wochenreport.fk_benutzerId = :id
        ORDER BY wochenreport.datum DESC');
        $statement->bindParam(':id', $_SESSION["id"], PDO::PARAM_INT);
        $statement->execute();
        return $statement;
    }

    /* Alle freigegebenen (1) Wochenberichte des aktuellen Lernenden. */
    public function getAllWeeklyInRelease(){
        $statement = $this->db->prepare('SELECT wochenreport.wochenreportId, wochenreport.kalenderwoche, wochenreport.erledigte_arbeiten,
        wochenreport.laufende_arbeiten, wochenreport.reflexion, wochenreport.aufgetretene_probleme,
        wochenreport.status, wochenreport.datum, benutzer.full_name FROM wochenreport
        INNER JOIN benutzer ON benutzer.benutzerId = wochenreport.fk_benutzerId
        WHERE wochenreport.status = 1 AND wochenreport.fk_benutzerId = :id
        ORDER BY wochenreport.datum DESC');
        $statement->bindParam(':id', $_SESSION["id"], PDO::PARAM_INT);
        $statement->execute();
        return $statement;
    }

    public function add_weeklyraport($calendar_week, $completed_tasks, $still_in_work, $reflection, $issues, $status){
        $statement = $this->db->prepare('INSERT INTO `wochenreport` (kalenderwoche, erledigte_arbeiten, laufende_arbeiten, reflexion, aufgetretene_probleme, status, fk_benutzerId)
        VALUES (:kalenderwoche, :erledigte_arbeiten, :laufende_arbeiten, :reflexion, :aufgetretene_probleme, :status, :id)');
        $statement->bindParam(':kalenderwoche', $calendar_week, PDO::PARAM_STR);
        $statement->bindParam(':erledigte_arbeiten', $completed_tasks, PDO::PARAM_STR);
        $statement->bindParam(':laufende_arbeiten', $still_in_work, PDO::PARAM_STR);
        $statement->bindParam(':reflexion', $reflection, PDO::PARAM_STR);
        $statement->bindParam(':aufgetretene_probleme', $issues, PDO::PARAM_STR);
        $statement->bindParam(':status', $status, PDO::PARAM_STR);
        $statement->bindParam(':id', $_SESSION["id"], PDO::PARAM_INT);
        $statement->execute();
        return $this->db->lastInsertId();
    }

    /* Löschen – nur eigene Berichte. */
    public function deleteWeeklyReport($id){
        $statement = $this->db->prepare('DELETE FROM `wochenreport` WHERE wochenreportId = :id AND fk_benutzerId = :owner');
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->bindParam(':owner', $_SESSION["id"], PDO::PARAM_INT);
        $statement->execute();
    }

    /* Freigeben – nur eigene Berichte. */
    public function releaseWeeklyReport($id){
        $statement = $this->db->prepare('UPDATE wochenreport SET status = 1 WHERE wochenreportId = :id AND fk_benutzerId = :owner');
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->bindParam(':owner', $_SESSION["id"], PDO::PARAM_INT);
        $statement->execute();
    }

    /* Bearbeiten – nur eigene Berichte. */
    public function editWeeklyReport($calendar_week, $completed_tasks, $still_in_work, $reflection, $issues, $id){
        $statement = $this->db->prepare('UPDATE wochenreport SET kalenderwoche = :kalenderwoche, erledigte_arbeiten = :erledigte_arbeiten, laufende_arbeiten = :laufende_arbeiten, reflexion = :reflexion, aufgetretene_probleme = :aufgetretene_probleme WHERE wochenreportId = :id AND fk_benutzerId = :owner');
        $statement->bindParam(':kalenderwoche', $calendar_week, PDO::PARAM_STR);
        $statement->bindParam(':erledigte_arbeiten', $completed_tasks, PDO::PARAM_STR);
        $statement->bindParam(':laufende_arbeiten', $still_in_work, PDO::PARAM_STR);
        $statement->bindParam(':reflexion', $reflection, PDO::PARAM_STR);
        $statement->bindParam(':aufgetretene_probleme', $issues, PDO::PARAM_STR);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->bindParam(':owner', $_SESSION["id"], PDO::PARAM_INT);
        $statement->execute();
    }

    public function getWeeklyReport($id){
        $statement = $this->db->prepare('SELECT * FROM wochenreport WHERE wochenreportId = :id');
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();
        return $statement;
    }

    public function seeWeekly($id){
        $statement = $this->db->prepare('SELECT wochenreport.*, benutzer.full_name FROM wochenreport
        INNER JOIN benutzer ON benutzer.benutzerId = wochenreport.fk_benutzerId
        WHERE wochenreportId = :id');
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();
        return $statement;
    }
}
