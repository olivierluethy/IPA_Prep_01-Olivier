<?php

class AdminController
{
    public function useroverview()
    {
        requireRole([2]);

        $arrayUsers = (new Admin())->getAllUsers()->fetchAll(PDO::FETCH_ASSOC);

        require 'app/Views/admin/useroverview.view.php';
    }

    public function editUser()
    {
        requireRole([2]);

        $id = (int) ($_GET['id'] ?? 0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $role = (int) post('role');

            if (!in_array($role, [0, 1, 2], true)) {
                setFlash('error', 'Ungültige Rolle ausgewählt.');
                header('Location: useroverview');
                exit;
            }

            (new Admin())->editUser($id, $role);
            setFlash('success', 'Rolle aktualisiert.');
            header('Location: useroverview');
            exit;
        }

        $getUser = (new Admin())->getUser($id)->fetchAll(PDO::FETCH_ASSOC);
        if (empty($getUser)) {
            setFlash('error', 'Dieser Benutzer wurde nicht gefunden.');
            header('Location: useroverview');
            exit;
        }

        require 'app/Views/admin/editUser.view.php';
    }

    public function deleteUser()
    {
        requireRole([2]);

        $id = (int) ($_GET['id'] ?? 0);
        (new Admin())->deleteUser($id);

        setFlash('success', 'Benutzer gelöscht.');
        header('Location: useroverview');
        exit;
    }
}
