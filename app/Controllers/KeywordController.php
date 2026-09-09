<?php

class KeywordController
{
    public function keywords()
    {
        requireRole([0]);

        $arrayKeywords = (new Keyword())->getAllKeywords()->fetchAll(PDO::FETCH_ASSOC);

        require 'app/Views/lernender/mykeywords.view.php';
    }

    public function addkeyword()
    {
        requireRole([0]);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $thema = trim((string) post('thema'));

            if ($thema === '') {
                setFlash('error', 'Bitte gib einen Namen für das Keyword ein.');
                header('Location: addkeyword');
                exit;
            }

            (new Keyword())->add_keywords($thema);

            setFlash('success', 'Keyword hinzugefügt.');
            header('Location: keywords');
            exit;
        }

        require 'app/Views/lernender/addkeyword.view.php';
    }

    public function editkeywords()
    {
        requireRole([0]);

        $id = (int) ($_GET['id'] ?? 0);
        $Keyword = new Keyword();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titel = trim((string) post('thema'));

            if ($titel === '') {
                setFlash('error', 'Der Keyword-Name darf nicht leer sein.');
                header('Location: editKeyword?id=' . $id);
                exit;
            }

            $Keyword->editKeyword($id, $titel);

            setFlash('success', 'Keyword aktualisiert.');
            header('Location: keywords');
            exit;
        }

        $getKeyword = $Keyword->getKeyword($id)->fetchAll(PDO::FETCH_ASSOC);
        if (empty($getKeyword)) {
            setFlash('error', 'Dieses Keyword wurde nicht gefunden.');
            header('Location: keywords');
            exit;
        }

        require 'app/Views/lernender/editKeyword.view.php';
    }

    public function deleteKeyword()
    {
        requireRole([0]);
        $id = (int) ($_GET['id'] ?? 0);
        (new Keyword())->deleteKeyword($id);

        setFlash('success', 'Keyword gelöscht.');
        header('Location: keywords');
        exit;
    }
}
