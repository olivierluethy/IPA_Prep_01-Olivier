<?php

class FachkraftController
{
    public function overview()
    {
        requireRole([1]);

        $q    = trim((string) ($_GET['q'] ?? ''));
        $type = $_GET['type'] ?? 'all';
        if (!in_array($type, ['all', 'daily', 'weekly'], true)) {
            $type = 'all';
        }

        $Fachkraft = new Fachkraft();
        $arrayDailyRaports  = $Fachkraft->getDailyRaports()->fetchAll(PDO::FETCH_ASSOC);
        $arrayWeeklyRaports = $Fachkraft->getWeeklyRaports()->fetchAll(PDO::FETCH_ASSOC);
        $arrayLernende      = $Fachkraft->getAllLernende()->fetchAll(PDO::FETCH_ASSOC);

        if ($q !== '') {
            $needle = mb_strtolower($q);

            $arrayDailyRaports = array_values(array_filter($arrayDailyRaports, function ($r) use ($needle) {
                $name = mb_strtolower((string) ($r['full_name'] ?? ''));
                $text = mb_strtolower((string) ($r['text'] ?? ''));
                return strpos($name, $needle) !== false || strpos($text, $needle) !== false;
            }));

            $arrayWeeklyRaports = array_values(array_filter($arrayWeeklyRaports, function ($r) use ($needle) {
                $name = mb_strtolower((string) ($r['full_name'] ?? ''));
                $work = mb_strtolower((string) ($r['erledigte_arbeiten'] ?? ''));
                return strpos($name, $needle) !== false || strpos($work, $needle) !== false;
            }));
        }

        require 'app/Views/fachkraft/overview.view.php';
    }
}
