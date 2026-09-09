<?php

class SearchController
{
    public function search()
    {
        requireLogin();

        $q    = trim((string) ($_GET['q'] ?? ''));
        $role = currentRole();

        if ($q !== '') {
            $search = new Search();

            if ($role === 0) {
                $daily    = $search->dailyForUser(currentUserId(), $q);
                $weekly   = $search->weeklyForUser(currentUserId(), $q);
                $keywords = $search->keywordsForUser(currentUserId(), $q);
            } else {
                $daily    = $search->releasedDaily($q);
                $weekly   = $search->releasedWeekly($q);
                $keywords = [];
            }

            $total = count($daily) + count($weekly) + count($keywords);
        }

        require 'app/Views/general/search.view.php';
    }
}
