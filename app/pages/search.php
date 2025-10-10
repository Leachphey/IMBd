<?php include 'includes/header.php'; ?>
<?php require_once '../app/core/init.php'; ?>

<?php
$find            = $_GET['find'] ?? null;
$find_top_movie  = $_GET['find_top_movie'] ?? null;
$watchlist       = $_GET['watchlist'] ?? null;
$recent_movies   = $_GET['recent_movies'] ?? null;
$top_series      = $_GET['top_series'] ?? null;
$recent_series   = $_GET['recent_series'] ?? null;

$movies = [];
$page_title = "Sonuçlar";

$watchlist_ids = [];
if (logged_in()) {
    $user_id = user('id');
    $watchlist_movies = query("SELECT movie_id FROM watchlist WHERE user_id = :uid", ['uid' => $user_id]);
    $watchlist_ids = array_column($watchlist_movies, 'movie_id');
}

if ($watchlist && logged_in()) {
    $movies = query("SELECT m.* FROM movies m JOIN watchlist w ON m.id = w.movie_id WHERE w.user_id = :uid ORDER BY m.rating DESC", ['uid' => $user_id]);
    $page_title = "İzleme Listeniz";
} elseif ($top_series) {
    $movies = query("SELECT * FROM movies WHERE type = 'series' ORDER BY rating DESC LIMIT 100");
    $page_title = "En Çok Beğenilen Diziler";
} elseif ($recent_series) {
    $movies = query("SELECT * FROM movies WHERE type = 'series' ORDER BY id DESC LIMIT 100");
    $page_title = "En Son Eklenen Diziler";
} elseif ($recent_movies) {
    $movies = query("SELECT * FROM movies WHERE type = 'movie' ORDER BY id DESC LIMIT 100");
    $page_title = "En Son Eklenen Filmler";
} elseif ($find_top_movie) {
    $movies = query("SELECT * FROM movies WHERE type = 'movie' ORDER BY rating DESC LIMIT 100");
    $page_title = "En Çok Beğenilen Filmler";
} elseif ($find) {
    $page_title = "Arama Sonuçları: " . htmlspecialchars($find);
    $find = "%$find%";
    $movies = query("SELECT * FROM movies WHERE title LIKE :find ORDER BY rating DESC LIMIT 100", ['find' => $find]);
} else {
    $movies = query("SELECT * FROM movies ORDER BY rating DESC LIMIT 100");
    $page_title = "Tüm Filmler / Diziler";
}
?>

<div class="container my-4">
    <h2 class="mb-4 text-center"><?= $page_title ?></h2>

    <div class="list-group">
        <?php if (!empty($movies)): ?>
            <?php foreach ($movies as $movie): ?>
                <div class="list-group-item bg-white border border-secondary-subtle mb-3 rounded shadow-sm d-flex align-items-start">

                    <!-- Poster (Detay sayfasına yönlendirme) -->
                    <a href="<?= ROOT ?>/movie_detail/<?= $movie['slug'] ?>">
                        <img src="<?= esc($movie['poster_url'] ?? 'https://placehold.co/100x150') ?>"
                            alt="<?= esc($movie['title']) ?>"
                            class="rounded me-3 border border-dark-subtle"
                            style="width: 100px; height: 150px; object-fit: cover;">
                    </a>

                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h5 class="mb-0"><?= esc($movie['title']) ?></h5>
                            <span class="text-warning fw-bold">★ <?= esc(number_format($movie['rating'], 1)) ?></span>
                        </div>
                        <div class="text-muted small mb-2">
                            <?= date('Y', strtotime($movie['release_year'])) ?> · <?= esc($movie['duration_minutes']) ?> dk
                        </div>
                        <div>
                            <?php if (logged_in()): ?>
                                <?php if (in_array($movie['id'], $watchlist_ids)): ?>
                                    <form method="post" action="<?= ROOT ?>/watchlist_toggle" style="display:inline;">
                                        <input type="hidden" name="movie_id" value="<?= $movie['id'] ?>">
                                        <input type="hidden" name="action" value="remove"> <button class="btn btn-sm btn-outline-warning me-2" type="submit">− İzleme listesinden kaldır</button>
                                    </form>
                                <?php else: ?>
                                    <form method="post" action="<?= ROOT ?>/watchlist_toggle" style="display:inline;"> <input type="hidden" name="movie_id" value="<?= $movie['id'] ?>">
                                        <input type="hidden" name="action" value="add"> <button class="btn btn-sm btn-outline-dark me-2" type="submit">+ İzleme listesi</button>
                                    </form>
                                <?php endif; ?>
                            <?php endif; ?>

                            <a href="<?= ROOT ?>/movie_detail/<?= $movie['slug'] ?>" class="btn btn-sm btn-outline-dark">▶ Detay</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-warning text-center">Hiçbir sonuç bulunamadı.</div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>