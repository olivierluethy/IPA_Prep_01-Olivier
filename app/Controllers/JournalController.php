<?php

class JournalController
{
    public function home()
    {
        requireLogin();
        $role = currentRole();

        if ($role === 0) {
            $Journal = new Journal();
            $dailyDrafts   = $Journal->getAllDailyJournalsInProcess()->fetchAll(PDO::FETCH_ASSOC);
            $dailyReleased = $Journal->getAllDailyJournalsInRelease()->fetchAll(PDO::FETCH_ASSOC);

            $Weekly = new WeeklyReport();
            $weeklyDrafts   = $Weekly->getAllWeeklyInProcess()->fetchAll(PDO::FETCH_ASSOC);
            $weeklyReleased = $Weekly->getAllWeeklyInRelease()->fetchAll(PDO::FETCH_ASSOC);

            $keywordCount = count((new Keyword())->getAllKeywords()->fetchAll());

            // Letzte Einträge (Tages- + Wochenberichte) zusammenführen und nach Datum sortieren.
            $recent = [];
            $addDaily = function ($rows, $status) use (&$recent) {
                foreach ($rows as $d) {
                    $recent[] = [
                        'type'   => 'daily',
                        'id'     => $d['journalId'],
                        'title'  => 'Tagesbericht',
                        'text'   => $d['text'],
                        'datum'  => $d['datum'],
                        'status' => $status,
                    ];
                }
            };
            $addDaily($dailyDrafts, 0);
            $addDaily($dailyReleased, 1);
            foreach (array_merge($weeklyDrafts, $weeklyReleased) as $w) {
                $recent[] = [
                    'type'   => 'weekly',
                    'id'     => $w['wochenreportId'],
                    'title'  => 'Wochenbericht · KW ' . $w['kalenderwoche'],
                    'text'   => $w['erledigte_arbeiten'],
                    'datum'  => $w['datum'],
                    'status' => (int) $w['status'],
                ];
            }
            usort($recent, fn($a, $b) => strtotime($b['datum']) <=> strtotime($a['datum']));

            $stats = [
                'dailyTotal'  => count($dailyDrafts) + count($dailyReleased),
                'weeklyTotal' => count($weeklyDrafts) + count($weeklyReleased),
                'drafts'      => count($dailyDrafts) + count($weeklyDrafts),
                'keywords'    => $keywordCount,
            ];

            require 'app/Views/lernender/dashboard.view.php';
            return;
        }

        if ($role === 1) {
            $Fachkraft = new Fachkraft();
            $daily    = $Fachkraft->getDailyRaports()->fetchAll(PDO::FETCH_ASSOC);
            $weekly   = $Fachkraft->getWeeklyRaports()->fetchAll(PDO::FETCH_ASSOC);
            $lernende = $Fachkraft->getAllLernende()->fetchAll(PDO::FETCH_ASSOC);

            require 'app/Views/fachkraft/dashboard.view.php';
            return;
        }

        // Admin
        $users = (new Admin())->getAllUsers()->fetchAll(PDO::FETCH_ASSOC);
        require 'app/Views/admin/dashboard.view.php';
    }

    public function releasedreports()
    {
        requireLogin();
        header('Location: overview');
        exit;
    }
}
