<?php
require_once __DIR__ . '/../core/init.php';

if (!logged_in()) redirect('signin');

$comment_id = $_POST['comment_id'] ?? null;
$slug = $_POST['movie_slug'] ?? 'home';

$comment = query_row("SELECT * FROM comments WHERE id = :id", ['id' => $comment_id]);

if ($comment && $comment['user_id'] == user('id')) {
    query("DELETE FROM comments WHERE id = :id", ['id' => $comment_id]);

    $new_avg = query_row("SELECT AVG(rating) FROM comments WHERE movie_id = :mid", ['mid' => $comment['movie_id']], true);
    query("UPDATE movies SET rating = :r WHERE id = :id", ['r' => $new_avg, 'id' => $comment['movie_id']]);
}

redirect("movie_detail/$slug");
