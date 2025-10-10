<?php

require_once __DIR__ . '/../core/init.php';

if (!logged_in()) redirect('signin');

$movie_id = $_POST['movie_id'] ?? null;
$rating = $_POST['rating'] ?? null;
$comment_text = $_POST['comment_text'] ?? '';
$comment_id = $_POST['comment_id'] ?? null;

if (!$movie_id || !$rating || trim($comment_text) == '') {
    redirect('movie_detail/' . ($_POST['slug'] ?? ''));
}

$existing_comment = query_row("SELECT * FROM comments WHERE movie_id = :mid AND user_id = :uid", [
    'mid' => $movie_id,
    'uid' => user('id')
]);

if ($existing_comment && !$comment_id) {
    $slug = query_row("SELECT slug FROM movies WHERE id = :id", ['id' => $movie_id], true);
    redirect("movie_detail/$slug");
}

if ($comment_id) {
    $existing = query_row("SELECT * FROM comments WHERE id = :id AND user_id = :uid", [
        'id' => $comment_id,
        'uid' => user('id')
    ]);

    if ($existing) {
        query("UPDATE comments SET comment_text = :text, rating = :rating WHERE id = :id", [
            'text' => $comment_text,
            'rating' => $rating,
            'id' => $comment_id
        ]);
    }
} else {
    query("INSERT INTO comments (movie_id, user_id, comment_text, rating)
           VALUES (:mid, :uid, :text, :rating)", [
        'mid' => $movie_id,
        'uid' => user('id'),
        'text' => $comment_text,
        'rating' => $rating
    ]);
}

$new_avg = query_row("SELECT AVG(rating) FROM comments WHERE movie_id = :mid", ['mid' => $movie_id], true);
query("UPDATE movies SET rating = :r WHERE id = :id", ['r' => $new_avg, 'id' => $movie_id]);

$slug = query_row("SELECT slug FROM movies WHERE id = :id", ['id' => $movie_id], true);
redirect("movie_detail/$slug");
