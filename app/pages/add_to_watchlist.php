<?php
require_once __DIR__ . "/../core/init.php";

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!logged_in()) {
    redirect('signin');
}

$user_id = user('id');
$movie_id = $_POST['movie_id'] ?? null;

if (!$user_id) {
    die("Hata: Kullanıcı ID alınamadı.");
}

if ($movie_id && is_numeric($movie_id)) {
    $movie = query_row("SELECT id, slug FROM movies WHERE id = :mid", ['mid' => $movie_id]);
    if (!$movie) {
        die("Hata: Geçersiz film ID.");
    }

    $slug = $movie['slug'] ?? null;
    if (!$slug) {
        die("Hata: Film için slug tanımlı değil.");
    }

    $exists = query_row("SELECT * FROM watchlist WHERE user_id = :uid AND movie_id = :mid", [
        'uid' => $user_id,
        'mid' => $movie_id
    ]);

    if (!$exists) {
        try {
            query("INSERT INTO watchlist (user_id, movie_id) VALUES (:uid, :mid)", [
                'uid' => $user_id,
                'mid' => $movie_id
            ]);
            redirect("movie_detail/$slug");
        } catch (Exception $e) {
            die("Veritabanı hatası: " . $e->getMessage());
        }
    } else {
        redirect("movie_detail/$slug?error=already_in_watchlist");
    }
} else {
    redirect('home?error=invalid_movie_id');
}
