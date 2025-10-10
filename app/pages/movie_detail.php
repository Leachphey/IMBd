<?php include 'includes/header.php'; ?>

<?php
$slug = $url[1] ?? null;
if (!$slug) {
    echo "<div class='container'><h3>Film bulunamadı.</h3></div>";
    return;
}

$row = query_row("SELECT * FROM movies WHERE slug = :slug", ['slug' => $slug]);
if (!$row) {
    echo "<div class='container'><h3>Film bulunamadı.</h3></div>";
    return;
}


$genres = query("SELECT g.name FROM movie_genres mg JOIN genres g ON g.id = mg.genre_id WHERE mg.movie_id = :id", ['id' => $row['id']]);
if (!is_array($genres)) {
    $genres = [];
}

$actors = query("SELECT a.name FROM movie_actors ma JOIN actors a ON a.id = ma.actor_id WHERE ma.movie_id = :mid", ['mid' => $row['id']]);
if (!is_array($actors)) {
    $actors = [];
}

$watchlist_ids = [];
if (logged_in()) {
    $user_id = user('id');
    $watchlist_check = query_row("SELECT * FROM watchlist WHERE user_id = :uid AND movie_id = :mid", [
        'uid' => $user_id,
        'mid' => $row['id']
    ]);
    $in_watchlist = $watchlist_check ? true : false;
} else {
    $in_watchlist = false;
}

$editing_comment = null;
$edit_id = $_GET['edit_comment_id'] ?? null;
if ($edit_id && logged_in()) {
    $editing_comment = query_row("SELECT * FROM comments WHERE id = :id AND user_id = :uid", [
        'id' => $edit_id,
        'uid' => user('id')
    ]);
}

$poster_url = $row['poster_url'];
//assets/images/1749067568_duck-png.png
$poster_url = ROOT . '/' . substr($poster_url, 0, 14)  .  substr($poster_url, 14);

?>

<div class="container py-5">
    <div class="row g-4">
        <div class="col-md-4">
            <img src="<?php echo $poster_url; ?>"
                class="img-fluid border rounded shadow-sm"
                alt="<?= esc($row['title']) ?>">
        </div>
        <div class="col-md-8">
            <h1 class="mb-3"><?= esc($row['title']) ?></h1>
            <p><strong>Çıkış Tarihi:</strong> <?= date("d/m/Y", strtotime($row['release_year'])) ?></p>
            <p><strong>Süre:</strong> <?= esc($row['duration_minutes']) ?> dakika</p>
            <p><strong>Puan:</strong> <span class="text-warning">★</span> <?= esc(number_format($row['rating'], 1)) ?></p>
            <p><strong>Kategoriler:</strong>
                <?php foreach ($genres as $genre): ?>
                    <span class="badge bg-dark me-1"><?= esc($genre['name']) ?></span>
                <?php endforeach; ?>
            </p>
            <p><strong>Oyuncular:</strong>
                <?php foreach ($actors as $actor): ?>
                    <span class="badge bg-secondary me-1"><?= esc($actor['name']) ?></span>
                <?php endforeach; ?>
            </p>

            <?php if (logged_in()): ?>
                <form method="post" action="<?= ROOT ?>/<?= $in_watchlist ? 'watchlist_toggle' : 'add_to_watchlist' ?>">
                    <input type="hidden" name="movie_id" value="<?= $row['id'] ?>">
                    <button class="btn btn-outline-<?= $in_watchlist ? 'danger' : 'dark' ?>">
                        <?= $in_watchlist ? '- İzleme listesinden çıkart' : '+ İzleme listesi' ?>
                    </button>
                </form>
            <?php endif; ?>

            <hr>
            <p><?= nl2br(esc($row['description'])) ?></p>
        </div>
    </div>

    <h4 class="mt-5"><?= $editing_comment ? "Yorumu Düzenle" : "Yorum Yap" ?></h4>
    <?php if (logged_in()): ?>
        <form method="post" action="<?= ROOT ?>/save_comment" class="mb-4">
            <input type="hidden" name="movie_id" value="<?= $row['id'] ?>">
            <?php if ($editing_comment): ?>
                <input type="hidden" name="comment_id" value="<?= $editing_comment['id'] ?>">
            <?php endif; ?>
            <div class="mb-2">
                <label>Puan (1–10)</label>
                <input type="number" name="rating" class="form-control" min="1" max="10" required value="<?= $editing_comment['rating'] ?? '' ?>">
            </div>
            <div class="mb-2">
                <label>Yorum</label>
                <textarea name="comment_text" class="form-control" required><?= esc($editing_comment['comment_text'] ?? '') ?></textarea>
            </div>
            <button class="btn btn-success">Kaydet</button>
        </form>
    <?php else: ?>
        <div class="alert alert-warning">Yorum yapabilmek için <a href="<?= ROOT ?>/signin">giriş yapmalısınız</a>.</div>
    <?php endif; ?>

    <h4 class="mt-5">Yorumlar</h4>
    <?php
    $comments = query("SELECT c.*, u.username FROM comments c JOIN users u ON u.id = c.user_id WHERE c.movie_id = :mid ORDER BY c.created_at DESC", ['mid' => $row['id']]);
    ?>
    <?php if ($comments): ?>
        <?php foreach ($comments as $c): ?>
            <div class="border rounded p-3 mb-3">
                <div class="d-flex justify-content-between">
                    <strong><?= esc($c['username']) ?></strong>
                    <span class="text-warning">★ <?= esc($c['rating']) ?></span>
                </div>
                <p class="mb-1"><?= esc($c['comment_text']) ?></p>
                <small class="text-muted"><?= date('d.m.Y H:i', strtotime($c['created_at'])) ?></small>

                <?php if (logged_in() && user('id') == $c['user_id']): ?>
                    <div class="mt-2">
                        <form method="get" action="<?= ROOT ?>/movie_detail/<?= $row['slug'] ?>" style="display:inline;">
                            <input type="hidden" name="edit_comment_id" value="<?= $c['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-outline-primary">Düzenle</button>
                        </form>
                        <form method="post" action="<?= ROOT ?>/delete_comment" style="display:inline;">
                            <input type="hidden" name="comment_id" value="<?= $c['id'] ?>">
                            <input type="hidden" name="movie_slug" value="<?= $row['slug'] ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger">Sil</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="text-muted">Henüz yorum yapılmamış.</div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>