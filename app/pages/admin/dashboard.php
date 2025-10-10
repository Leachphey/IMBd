<?php
require_once '../app/core/init.php';
if (!is_admin()) redirect('home');

$user_count      = query_row("SELECT COUNT(*) FROM users", [], true);
$admin_count     = query_row("SELECT COUNT(*) FROM users WHERE role = 'admin'", [], true);
$movie_count     = query_row("SELECT COUNT(*) FROM movies WHERE type = 'movie'", [], true);
$series_count    = query_row("SELECT COUNT(*) FROM movies WHERE type = 'series'", [], true);
$comment_count   = query_row("SELECT COUNT(*) FROM comments", [], true);
$watchlist_count = query_row("SELECT COUNT(*) FROM watchlist", [], true);

$latest_movies = query("SELECT id, title, created_at FROM movies ORDER BY id DESC LIMIT 5");
?>

<div class="container py-4">
    <h2 class="mb-4">📊 Yönetici Paneli - Özet</h2>

    <div class="row g-4">
        <!-- Kullanıcılar -->
        <div class="col-md-4">
            <div class="card shadow border-0 h-100">
                <div class="card-body">
                    <h5 class="card-title">👥 Kullanıcılar</h5>
                    <p class="display-6"><?= $user_count ?></p>
                    <small class="text-muted">Toplam kullanıcı</small><br>
                    <small class="text-muted">👑 Yöneticiler: <?= $admin_count ?></small>
                </div>
            </div>
        </div>

        <!-- Filmler -->
        <div class="col-md-4">
            <div class="card shadow border-0 h-100">
                <div class="card-body">
                    <h5 class="card-title">🎬 Filmler</h5>
                    <p class="display-6"><?= $movie_count ?></p>
                    <small class="text-muted">Veritabanındaki filmler</small>
                </div>
            </div>
        </div>

        <!-- Diziler -->
        <div class="col-md-4">
            <div class="card shadow border-0 h-100">
                <div class="card-body">
                    <h5 class="card-title">📺 Diziler</h5>
                    <p class="display-6"><?= $series_count ?></p>
                    <small class="text-muted">Veritabanındaki diziler</small>
                </div>
            </div>
        </div>

        <!-- Yorumlar -->
        <div class="col-md-4">
            <div class="card shadow border-0 h-100">
                <div class="card-body">
                    <h5 class="card-title">💬 Yorumlar</h5>
                    <p class="display-6"><?= $comment_count ?></p>
                    <small class="text-muted">Tüm kullanıcı yorumları</small>
                </div>
            </div>
        </div>

        <!-- İzleme Listesi -->
        <div class="col-md-4">
            <div class="card shadow border-0 h-100">
                <div class="card-body">
                    <h5 class="card-title">📌 İzleme Listesi</h5>
                    <p class="display-6"><?= $watchlist_count ?></p>
                    <small class="text-muted">Toplam izleme listesi öğesi</small>
                </div>
            </div>
        </div>
    </div>

    <!-- En Son Eklenenler -->
    <div class="mt-5">
        <h5>🆕 En Son Eklenenler</h5>
        <ul class="list-group">
            <?php foreach ($latest_movies as $m): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?= esc($m['title']) ?>
                    <small class="text-muted"><?= date('d.m.Y H:i', strtotime($m['created_at'])) ?></small>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>