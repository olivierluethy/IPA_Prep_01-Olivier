<?php

class DailyReportController
{
    public function dailyraport()
    {
        requireRole([0]);

        $Journal = new Journal();
        $arrayJournalsInProcess = $Journal->getAllDailyJournalsInProcess()->fetchAll(PDO::FETCH_ASSOC);
        $arrayJournalIsReleased = $Journal->getAllDailyJournalsInRelease()->fetchAll(PDO::FETCH_ASSOC);

        require 'app/Views/lernender/dailyraport.view.php';
    }

    public function adddailyjournal()
    {
        requireRole([0]);

        $Keyword = new Keyword();
        $arrayTopics = $Keyword->getAllKeywords()->fetchAll(PDO::FETCH_ASSOC);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $text   = trim((string) post('text'));
            $topics = !empty($_POST['topics']) && is_array($_POST['topics']) ? $_POST['topics'] : [];

            if ($text === '' || $text === '<p>&nbsp;</p>') {
                setFlash('error', 'Bitte schreibe zuerst etwas in deinen Tagesbericht.');
                header('Location: adddailyjournal');
                exit;
            }

            $DailyReport = new DailyReport();
            $journalId = $DailyReport->add_dailyjournal($text, 0);
            foreach ($topics as $topic) {
                $DailyReport->add_ausgewaehlte_themen($topic, $journalId);
            }

            setFlash('success', 'Tagesbericht als Entwurf gespeichert.');
            header('Location: dailyraport');
            exit;
        }

        require 'app/Views/lernender/adddailyjournal.view.php';
    }

    public function editDailyReport()
    {
        requireRole([0]);

        $id = (int) ($_GET['id'] ?? 0);
        $dailyReport = new DailyReport();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $text   = trim((string) post('text'));
            $topics = post('topics', []);
            if (!is_array($topics)) {
                $topics = [$topics];
            }

            $dailyReport->editDailyReport($id, $text, 0);
            foreach ($topics as $topic) {
                $dailyReport->add_ausgewaehlte_themen($topic, $id);
            }

            setFlash('success', 'Tagesbericht aktualisiert.');
            header('Location: dailyraport');
            exit;
        }

        $getDailyReport = $dailyReport->getDailyReport($id)->fetchAll(PDO::FETCH_ASSOC);
        if (empty($getDailyReport) || (int) $getDailyReport[0]['fk_benutzerId'] !== currentUserId()) {
            setFlash('error', 'Dieser Tagesbericht wurde nicht gefunden.');
            header('Location: dailyraport');
            exit;
        }

        $keyword = new Keyword();
        $getKeywords       = $keyword->getAllKeywords()->fetchAll(PDO::FETCH_ASSOC);
        $getPickedKeywords = $keyword->getSelectedKeywords($id)->fetchAll(PDO::FETCH_ASSOC);

        require 'app/Views/lernender/editDailyJournal.view.php';
    }

    public function deleteDailyReport()
    {
        requireRole([0]);
        $id = (int) ($_GET['id'] ?? 0);
        (new DailyReport())->deleteDailyReport($id);

        setFlash('success', 'Tagesbericht gelöscht.');
        header('Location: dailyraport');
        exit;
    }

    public function releaseDailyReport()
    {
        requireRole([0]);
        $id = (int) ($_GET['id'] ?? 0);
        (new DailyReport())->releaseDailyReport($id);

        setFlash('success', 'Tagesbericht an die Fachkraft freigegeben.');
        header('Location: dailyraport');
        exit;
    }

    public function seeDaily()
    {
        requireLogin();
        $id = (int) ($_GET['id'] ?? 0);

        $DailyReport = new DailyReport();
        $dayArray = $DailyReport->seeDaily($id)->fetchAll(PDO::FETCH_ASSOC);

        $Keyword = new Keyword();
        $getKeywords       = $Keyword->getSelectedKeywords($id)->fetchAll(PDO::FETCH_ASSOC);
        $getPickedKeywords = $getKeywords;

        require 'app/Views/fachkraft/seeDaily.view.php';
    }
}
