<div class="d-flex align-items-start mb-3 video-item">
    <div class="video-thumb me-2">
        <img src="<?= esc($row['poster_url'] ?? 'https://placehold.co/120x80') ?>" alt="<?= esc($row['title']) ?>" width="120" height="80" style="object-fit:cover;">
    </div>
    <div>
        <p class="mb-1 fw-bold small"><?= esc($row['title'] ?? 'null') ?></p>
        <p class="text-muted mb-1 small"><?= esc(substr($row['description'] ?? '', 0, 50)) ?>...</p>
        <small><?= esc(date('Y', strtotime($row['release_year'] ?? ''))) ?></small>
    </div>
</div>