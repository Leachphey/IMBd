<div class="col">
    <div class="card h-100 text-center bg-dark text-white border-0">
        <div class="ratio-2x3-custom rounded-top">
            <img src="<?= esc($row['poster_url'] ?? 'https://placehold.co/800x800?text=Görsel+Yüklenemedi&font=Montserrat') ?>" class="card-img-top w-100 h-100 object-fit-cover" alt="...">
        </div>
        <div class="card-body p-2">
            <h6 class="mb-1"><i class="text-warning">★</i> <?= esc($row['rating'] ?? '0.0') ?></h6>
            <h5 class="card-title fw-bold fs-6"><?= esc($row['title'] ?? 'null') ?></h5>

            <!-- İzleme listesi kontrolü -->
            <?php if (logged_in()): ?>
                <?php
                $user_id = user('id');
                $watchlist_check = query_row("SELECT 1 FROM watchlist WHERE user_id = :uid AND movie_id = :mid", [
                    'uid' => $user_id,
                    'mid' => $row['id']
                ]);

                ?>

                <!-- Ekle / Kaldır butonu -->
                <form method="post" action="<?= ROOT ?>/watchlist_toggle" class="mb-1">
                    <input type="hidden" name="movie_id" value="<?= $row['id'] ?>">
                    <?php if ($watchlist_check): ?>
                        <input type="hidden" name="action" value="remove">
                        <button type="submit" class="btn btn-sm btn-outline-warning w-100">- İzleme listesinden kaldır</button>
                    <?php else: ?>
                        <input type="hidden" name="action" value="add">
                        <button type="submit" class="btn btn-sm btn-outline-light w-100">+ İzleme listesi</button>
                    <?php endif; ?>
                </form>
            <?php endif; ?>

            <a href="<?= ROOT ?>/movie_detail/<?= $row['slug'] ?>" class="btn btn-sm btn-outline-light w-100">▶ Detay</a>
        </div>
    </div>
</div>