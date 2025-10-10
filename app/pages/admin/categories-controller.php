<?php

if ($section === 'categories') {

    switch ($action) {
        case 'add':
            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                $errors = [];

                if (empty($_POST['name'])) {
                    $errors['name'] = "Tür adı gerekli";
                }

                if (empty($errors)) {
                    $data = [
                        'name' => $_POST['name'],
                        'slug' => str_to_url($_POST['name']),
                    ];

                    $query = "INSERT INTO genres (name, slug) VALUES (:name, :slug)";
                    query($query, $data);

                    redirect('admin/categories');
                }
            }
            break;

        case 'edit':
            $query = "SELECT * FROM genres WHERE id = :id LIMIT 1";
            $row = query_row($query, ['id' => $id]);

            if ($_SERVER['REQUEST_METHOD'] == "POST" && $row) {
                $errors = [];

                if (empty($_POST['name'])) {
                    $errors['name'] = "Tür adı gerekli";
                }

                if (empty($errors)) {
                    $data = [
                        'name' => $_POST['name'],
                        'slug' => str_to_url($_POST['name']),
                        'id' => $id,
                    ];

                    $query = "UPDATE genres SET name = :name, slug = :slug WHERE id = :id LIMIT 1";
                    query($query, $data);

                    redirect('admin/categories');
                }
            }
            break;

        case 'delete':
            $query = "SELECT * FROM genres WHERE id = :id LIMIT 1";
            $row = query_row($query, ['id' => $id]);

            if ($_SERVER['REQUEST_METHOD'] == "POST" && $row) {
                $query = "DELETE FROM genres WHERE id = :id LIMIT 1";
                query($query, ['id' => $id]);

                redirect('admin/categories');
            }
            break;
    }
}
