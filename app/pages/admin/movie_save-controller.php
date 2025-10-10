<?php

if ($section === 'movie_save') {

    if ($action === 'add') {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $errors = [];

            if (empty($_POST['title'])) {
                $errors['title'] = "Başlık gerekli";
            }

            if (empty($_POST['description'])) {
                $errors['description'] = "Açıklama gerekli";
            }

            if (empty($_POST['release_year'])) {
                $errors['release_year'] = "Çıkış tarihi gerekli";
            }

            if (empty($_POST['duration_minutes'])) {
                $errors['duration_minutes'] = "Süre gerekli";
            }

            if (empty($_FILES['poster']['name'])) {
                $errors['poster'] = "Poster görseli yükleyin";
            }

            if (empty($_POST['genre_ids'])) {
                $errors['genre_ids'] = "En az bir kategori seçin";
            }

            if (empty($errors)) {
                $poster_url = '';
                if (!empty($_FILES['poster']['name'])) {
                    $folder = "assets/images/";
                    if (!file_exists($folder)) {
                        mkdir($folder, 0777, true);
                    }

                    $ext = pathinfo($_FILES['poster']['name'], PATHINFO_EXTENSION);
                    $filename = time() . "_" . str_to_url($_FILES['poster']['name']) . "." . $ext;
                    $destination = $folder . $filename;

                    if (move_uploaded_file($_FILES['poster']['tmp_name'], $destination)) {
                        $poster_url = $destination;
                    }
                }

                $data = [
                    'title' => $_POST['title'],
                    'slug' => str_to_url($_POST['title']),
                    'description' => $_POST['description'],
                    'release_year' => $_POST['release_year'],
                    'duration_minutes' => $_POST['duration_minutes'],
                    'poster_url' => $poster_url,
                    'type' => $_POST['type'],
                ];

                $query = "INSERT INTO movies (title, slug, description, release_year, duration_minutes, poster_url, type)
                          VALUES (:title, :slug, :description, :release_year, :duration_minutes, :poster_url, :type)";
                query($query, $data);

                $movie_id = query_row("SELECT id FROM movies WHERE slug = :slug ORDER BY id DESC LIMIT 1", [
                    'slug' => $data['slug']
                ], true);

                foreach ($_POST['genre_ids'] as $genre_id) {
                    if (is_numeric($genre_id)) {
                        query("INSERT INTO movie_genres (movie_id, genre_id) VALUES (:movie_id, :genre_id)", [
                            'movie_id' => $movie_id,
                            'genre_id' => $genre_id
                        ]);
                    }
                }

                if (!empty($_POST['actor_ids'])) {
                    foreach ($_POST['actor_ids'] as $actor_id) {
                        if (is_numeric($actor_id)) {
                            query("INSERT INTO movie_actors (movie_id, actor_id) VALUES (:movie_id, :actor_id)", [
                                'movie_id' => $movie_id,
                                'actor_id' => $actor_id
                            ]);
                        }
                    }
                }

                redirect('admin/movie_save');
            }
        }
    }

    if ($action === 'edit') {
        if (empty($id) || !is_numeric($id)) {
            echo "Geçersiz ID.";
            exit;
        }

        $row = query_row("SELECT * FROM movies WHERE id = :id LIMIT 1", ['id' => $id]);

        if ($_SERVER['REQUEST_METHOD'] == "POST" && $row) {
            $errors = [];

            if (empty($_POST['title'])) {
                $errors['title'] = "Başlık gerekli";
            }

            if (empty($_POST['description'])) {
                $errors['description'] = "Açıklama gerekli";
            }

            if (empty($_POST['release_year'])) {
                $errors['release_year'] = "Çıkış tarihi gerekli";
            }

            if (empty($_POST['duration_minutes'])) {
                $errors['duration_minutes'] = "Süre gerekli";
            }

            if (empty($_POST['type'])) {
                $errors['type'] = "Tür seçilmeli";
            }

            $poster_url = $row['poster_url'];
            if (!empty($_FILES['poster']['name'])) {
                $folder = "assets/images/";
                if (!file_exists($folder)) mkdir($folder, 0777, true);

                $ext = pathinfo($_FILES['poster']['name'], PATHINFO_EXTENSION);
                $filename = time() . "_" . str_to_url($_FILES['poster']['name']) . "." . $ext;
                $destination = $folder . $filename;

                if (move_uploaded_file($_FILES['poster']['tmp_name'], $destination)) {
                    if (file_exists($poster_url) && $poster_url !== $destination) {
                        unlink($poster_url);
                    }
                    $poster_url = $destination;
                }
            }

            if (empty($errors)) {
                $data = [
                    'id' => $id,
                    'title' => $_POST['title'],
                    'slug' => str_to_url($_POST['title']),
                    'description' => $_POST['description'],
                    'release_year' => $_POST['release_year'],
                    'duration_minutes' => $_POST['duration_minutes'],
                    'poster_url' => $poster_url,
                    'type' => $_POST['type'],
                ];

                $query = "UPDATE movies SET 
                            title = :title, 
                            slug = :slug, 
                            description = :description, 
                            release_year = :release_year, 
                            duration_minutes = :duration_minutes, 
                            poster_url = :poster_url,
                            type = :type
                          WHERE id = :id";
                query($query, $data);

                query("DELETE FROM movie_genres WHERE movie_id = :id", ['id' => $id]);
                foreach ($_POST['genre_ids'] as $genre_id) {
                    if (is_numeric($genre_id)) {
                        query("INSERT INTO movie_genres (movie_id, genre_id) VALUES (:movie_id, :genre_id)", [
                            'movie_id' => $id,
                            'genre_id' => $genre_id
                        ]);
                    }
                }

                query("DELETE FROM movie_actors WHERE movie_id = :id", ['id' => $id]);
                if (!empty($_POST['actor_ids'])) {
                    foreach ($_POST['actor_ids'] as $actor_id) {
                        if (is_numeric($actor_id)) {
                            query("INSERT INTO movie_actors (movie_id, actor_id) VALUES (:movie_id, :actor_id)", [
                                'movie_id' => $id,
                                'actor_id' => $actor_id
                            ]);
                        }
                    }
                }

                redirect('admin/movie_save');
            }
        }
    }

    if ($action === 'delete') {
        $row = query_row("SELECT * FROM movies WHERE id = :id", ['id' => $id]);

        if ($_SERVER['REQUEST_METHOD'] == "POST" && $row) {
            query("DELETE FROM movie_actors WHERE movie_id = :id", ['id' => $id]);
            query("DELETE FROM movie_genres WHERE movie_id = :id", ['id' => $id]);
            query("DELETE FROM movies WHERE id = :id", ['id' => $id]);

            redirect('admin/movie_save');
        }
    }
}
