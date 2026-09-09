<?php

class WeeklyReportController
{
    public function weeklyraport()
    {
        requireRole([0]);

        $WeeklyReport = new WeeklyReport();
        $arrayWeeklyInProcess  = $WeeklyReport->getAllWeeklyInProcess()->fetchAll(PDO::FETCH_ASSOC);
        $arrayWeeklyIsReleased = $WeeklyReport->getAllWeeklyInRelease()->fetchAll(PDO::FETCH_ASSOC);

        require 'app/Views/lernender/weeklyraport.view.php';
    }

    public function addweeklyjournal()
    {
        requireRole([0]);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $calendar_week   = (int) post('calendar_week');
            $completed_tasks = trim((string) post('completed_tasks'));
            $still_in_work   = trim((string) post('still_in_work'));
            $reflection      = trim((string) post('reflection'));
            $issues          = trim((string) post('issues'));

            if ($calendar_week < 1 || $calendar_week > 53) {
                setFlash('error', 'Bitte gib eine gültige Kalenderwoche (1–53) an.');
                header('Location: addweeklyjournal');
                exit;
            }

            (new WeeklyReport())->add_weeklyraport($calendar_week, $completed_tasks, $still_in_work, $reflection, $issues, 0);

            setFlash('success', 'Wochenbericht als Entwurf gespeichert.');
            header('Location: weeklyraport');
            exit;
        }

        require 'app/Views/lernender/addweeklyjournal.view.php';
    }

    public function editWeeklyRaport()
    {
        requireRole([0]);

        $id = (int) ($_GET['id'] ?? 0);
        $WeeklyReport = new WeeklyReport();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $calendar_week   = (int) post('calendar_week');
            $completed_tasks = trim((string) post('completed_tasks'));
            $still_in_work   = trim((string) post('still_in_work'));
            $reflection      = trim((string) post('reflection'));
            $issues          = trim((string) post('issues'));

            $WeeklyReport->editWeeklyReport($calendar_week, $completed_tasks, $still_in_work, $reflection, $issues, $id);

            setFlash('success', 'Wochenbericht aktualisiert.');
            header('Location: weeklyraport');
            exit;
        }

        $getWeeklyReport = $WeeklyReport->getWeeklyReport($id)->fetchAll(PDO::FETCH_ASSOC);
        if (empty($getWeeklyReport) || (int) $getWeeklyReport[0]['fk_benutzerId'] !== currentUserId()) {
            setFlash('error', 'Dieser Wochenbericht wurde nicht gefunden.');
            header('Location: weeklyraport');
            exit;
        }

        require 'app/Views/lernender/editWeeklyJournal.view.php';
    }

    public function deleteWeeklyRaport()
    {
        requireRole([0]);
        $id = (int) ($_GET['id'] ?? 0);
        (new WeeklyReport())->deleteWeeklyReport($id);

        setFlash('success', 'Wochenbericht gelöscht.');
        header('Location: weeklyraport');
        exit;
    }

    public function releaseWeeklyReport()
    {
        requireRole([0]);
        $id = (int) ($_GET['id'] ?? 0);
        (new WeeklyReport())->releaseWeeklyReport($id);

        setFlash('success', 'Wochenbericht an die Fachkraft freigegeben.');
        header('Location: weeklyraport');
        exit;
    }

    public function seeWeekly()
    {
        requireLogin();
        $id = (int) ($_GET['id'] ?? 0);

        $weekArray = (new WeeklyReport())->seeWeekly($id)->fetchAll(PDO::FETCH_ASSOC);

        require 'app/Views/fachkraft/seeWeekly.view.php';
    }
}
