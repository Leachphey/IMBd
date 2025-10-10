<?php
if ($section === 'actor_save') {

    if ($action === 'add') {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $errors = [];

            if (empty($_POST['name'])) {
                $errors['name'] = "Ad Soyad gerekli";
            }

            if (empty($errors)) {
                $data = [
                    'name' => $_POST['name'],
                ];

                $query = "INSERT INTO actors (name) VALUES (:name)";
                query($query, $data);

                redirect('admin/actor_save');
            }
        }
    }

    if ($action === 'edit' && is_numeric($id)) {
        $row = query_row("SELECT * FROM actors WHERE id = :id", ['id' => $id]);

        if ($_SERVER['REQUEST_METHOD'] == "POST" && $row) {
            $errors = [];

            if (empty($_POST['name'])) {
                $errors['name'] = "Ad Soyad gerekli";
            }

            if (empty($errors)) {
                $data = [
                    'name' => $_POST['name'],
                    'id' => $id
                ];
                query("UPDATE actors SET name = :name WHERE id = :id", $data);
                redirect('admin/actor_save');
            }
        }
    }


    if ($action === 'delete' && is_numeric($id)) {
        $row = query_row("SELECT * FROM actors WHERE id = :id", ['id' => $id]);

        if ($_SERVER['REQUEST_METHOD'] == "POST" && $row) {
            query("DELETE FROM movie_actors WHERE actor_id = :id", ['id' => $id]);
            query("DELETE FROM actors WHERE id = :id", ['id' => $id]);
            redirect('admin/actor_save');
        }
    }
}
