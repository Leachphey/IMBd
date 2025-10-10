<?php
$page = get_pagination_vars();
?>


<?php if ($action == 'add'): ?>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <div class="col-md-8 mx-auto">
        <form method="post" enctype="multipart/form-data">
            <h1 class="h3 mb-3 fw-normal">Oyuncu Ekle</h1>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">Lütfen hataları düzeltin!</div>
            <?php endif; ?>

            <div class="form-floating mb-3">
                <input value="<?= old_value('name') ?>" type="text" class="form-control" name="name" required>
                <label>Ad Soyad</label>
            </div>
            <?php if (!empty($errors['name'])): ?>
                <div class="text-danger"><?= $errors['name'] ?></div>
            <?php endif; ?>



            <button type="submit" class="btn btn-primary w-100">Kaydet</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            $('.select2').select2({
                width: '100%',
                placeholder: "Oyuncu ara...",
                allowClear: true
            });
        });
    </script>
<?php elseif ($action == 'edit'): ?>
    <div class="col-md-8 mx-auto">
        <form method="post">
            <h1 class="h3 mb-3 fw-normal">Oyuncu Düzenle</h1>

            <?php if (!empty($row)): ?>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">Lütfen hataları düzeltin!</div>
                <?php endif; ?>

                <div class="form-floating mb-3">
                    <input value="<?= old_value('name', $row['name']) ?>" type="text" class="form-control" name="name" required>
                    <label>Ad Soyad</label>
                </div>
                <?php if (!empty($errors['name'])): ?>
                    <div class="text-danger"><?= $errors['name'] ?></div>
                <?php endif; ?>

                <button type="submit" class="btn btn-primary w-100">Güncelle</button>

            <?php else: ?>
                <div class="alert alert-danger text-center">Oyuncu bulunamadı.</div>
            <?php endif; ?>
        </form>
    </div>

<?php elseif ($action == 'delete'): ?>
    <div class="col-md-6 mx-auto">
        <form method="post">
            <h1 class="h3 mb-3 fw-normal text-center">Oyuncuyu Sil</h1>
            <?php if (!empty($row)): ?>
                <div class="alert alert-warning text-center">
                    <strong>"<?= esc($row['name']) ?>"</strong> adlı oyuncuyu silmek istediğinizden emin misiniz?
                    <br>
                    Bu işlem geri alınamaz!
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" value="<?= esc($row['name']) ?>" disabled>
                    <label>Ad Soyad</label>
                </div>

                <?php

                ?>

                <input type="hidden" name="actor_id_to_delete" value="<?= esc($row['id']) ?>">

                <button type="submit" class="btn btn-danger w-100 mb-2">Evet, Sil</button>
                <a href="<?= ROOT ?>/admin/actor_save" class="btn btn-secondary w-100" type="button">Hayır, Geri Dön</a>
            <?php else: ?>
                <div class="alert alert-danger text-center">Silinecek oyuncu bulunamadı.</div>
                <div class="text-center">
                    <a href="<?= ROOT ?>/admin/actor_save" class="btn btn-primary">Oyuncu Listesine Dön</a>
                </div>
            <?php endif; ?>
        </form>
    </div>
<?php else: ?>
    <h4>
        Oyuncular
        <a href="<?= ROOT ?>/admin/actor_save/add">
            <button class="btn btn-primary">Yeni Ekle</button>
        </a>
    </h4>

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Ad</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $limit = 10;
                $offset = ($page['page_number'] - 1) * $limit;
                $query = "SELECT * FROM actors ORDER BY id DESC LIMIT $limit OFFSET $offset";
                $rows = query($query);
                ?>
                <?php if (!empty($rows)): ?>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= esc($row['name']) ?></td>
                            <td>
                                <a href="<?= ROOT ?>/admin/actor_save/edit/<?= $row['id'] ?>" class="btn btn-sm btn-warning text-white">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="<?= ROOT ?>/admin/actor_save/delete/<?= $row['id'] ?>" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash-fill"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center">Oyuncu bulunamadı.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Sayfalama Kontrolleri -->
    <div class="d-flex justify-content-between my-3">
        <a href="<?= $page['first_link'] ?>" class="btn btn-outline-primary">İlk Sayfa</a>
        <a href="<?= $page['prev_link'] ?>" class="btn btn-outline-primary">Önceki</a>
        <a href="<?= $page['next_link'] ?>" class="btn btn-outline-primary">Sonraki</a>
    </div>
<?php endif; ?>