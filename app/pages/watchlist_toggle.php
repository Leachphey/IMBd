<?php
require_once __DIR__ . "/../core/init.php";

if (!logged_in()) {
    redirect('signin');
}

$user_id = user('id');
$movie_id = $_POST['movie_id'] ?? null;
$action = $_POST['action'] ?? '';

if (!$movie_id || !is_numeric($movie_id)) {
    redirect('home?error=invalid_movie_id');
}

$movie = query_row("SELECT id, slug FROM movies WHERE id = :id", ['id' => $movie_id]);
if (!$movie) {
    redirect('home?error=movie_not_found');
}

if ($action === 'add') {
    $exists = query_row("SELECT * FROM watchlist WHERE user_id = :uid AND movie_id = :mid", [
        'uid' => $user_id,
        'mid' => $movie_id
    ]);
    if (!$exists) {
        query("INSERT INTO watchlist (user_id, movie_id) VALUES (:uid, :mid)", [
            'uid' => $user_id,
            'mid' => $movie_id
        ]);
    }
} elseif ($action === 'remove') {
    query("DELETE FROM watchlist WHERE user_id = :uid AND movie_id = :mid", [
        'uid' => $user_id,
        'mid' => $movie_id
    ]);
}

redirect("movie_detail/{$movie['slug']}");
