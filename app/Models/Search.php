<?php

class Search
{
    public $db;

    public function __construct()
    {
        $this->db = connectDatabase();
    }

    /** Eigene Tagesberichte des Lernenden durchsuchen. */
    public function dailyForUser($userId, $q)
    {
        $stmt = $this->db->prepare(
            'SELECT journalId, text, status, datum
               FROM journal
              WHERE fk_benutzerId = :id
                AND text LIKE :q
              ORDER BY datum DESC'
        );
        $stmt->execute([':id' => $userId, ':q' => '%' . $q . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Eigene Wochenberichte des Lernenden durchsuchen. */
    public function weeklyForUser($userId, $q)
    {
        $stmt = $this->db->prepare(
            'SELECT wochenreportId, kalenderwoche, erledigte_arbeiten, status, datum
               FROM wochenreport
              WHERE fk_benutzerId = :id
                AND (erledigte_arbeiten LIKE :q
                     OR laufende_arbeiten LIKE :q
                     OR reflexion LIKE :q
                     OR aufgetretene_probleme LIKE :q)
              ORDER BY datum DESC'
        );
        $stmt->execute([':id' => $userId, ':q' => '%' . $q . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Eigene Keywords (Themen) des Lernenden durchsuchen. */
    public function keywordsForUser($userId, $q)
    {
        $stmt = $this->db->prepare(
            'SELECT themaId, thema
               FROM thema
              WHERE fk_benutzerId = :id
                AND thema LIKE :q'
        );
        $stmt->execute([':id' => $userId, ':q' => '%' . $q . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Freigegebene Tagesberichte aller Lernenden durchsuchen (Fachkraft/Admin). */
    public function releasedDaily($q)
    {
        $stmt = $this->db->prepare(
            'SELECT journal.journalId, journal.text, journal.datum, benutzer.full_name
               FROM journal
         INNER JOIN benutzer ON benutzer.benutzerId = journal.fk_benutzerId
              WHERE journal.status = 1
                AND (journal.text LIKE :q OR benutzer.full_name LIKE :q)
              ORDER BY journal.datum DESC'
        );
        $stmt->execute([':q' => '%' . $q . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Freigegebene Wochenberichte aller Lernenden durchsuchen (Fachkraft/Admin). */
    public function releasedWeekly($q)
    {
        $stmt = $this->db->prepare(
            'SELECT wochenreport.wochenreportId, wochenreport.kalenderwoche,
                    wochenreport.erledigte_arbeiten, wochenreport.datum, benutzer.full_name
               FROM wochenreport
         INNER JOIN benutzer ON benutzer.benutzerId = wochenreport.fk_benutzerId
              WHERE wochenreport.status = 1
                AND (wochenreport.erledigte_arbeiten LIKE :q OR benutzer.full_name LIKE :q)
              ORDER BY wochenreport.datum DESC'
        );
        $stmt->execute([':q' => '%' . $q . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
