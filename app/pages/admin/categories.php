<?php if ($action == 'add'): ?>
    <div class="col-md-8 mx-auto">
        <form method="post">
            <h1 class="h3 mb-3 fw-normal">Yeni Tür Ekle</h1>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">Lütfen hataları düzeltin!</div>
            <?php endif; ?>

            <div class="form-floating mb-3">
                <input value="<?= old_value('name') ?>" type="text" class="form-control" name="name" required>
                <label>Tür Adı</label>
            </div>
            <?php if (!empty($errors['name'])): ?>
                <div class="text-danger"><?= $errors['name'] ?></div>
            <?php endif; ?>

            <button type="submit" class="btn btn-primary w-100">Kaydet</button>
        </form>
    </div>

<?php elseif ($action == 'edit'): ?>
    <div class="col-md-8 mx-auto">
        <form method="post">
            <h1 class="h3 mb-3 fw-normal">Tür Düzenle</h1>

            <?php if (!empty($row)): ?>
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">Lütfen hataları düzeltin!</div>
                <?php endif; ?>

                <div class="form-floating mb-3">
                    <input value="<?= old_value('name', $row['name']) ?>" type="text" class="form-control" name="name" required>
                    <label>Tür Adı</label>
                </div>
                <?php if (!empty($errors['name'])): ?>
                    <div class="text-danger"><?= $errors['name'] ?></div>
                <?php endif; ?>

                <button type="submit" class="btn btn-primary w-100">Güncelle</button>
            <?php else: ?>
                <div class="alert alert-danger text-center">Tür bulunamadı.</div>
            <?php endif; ?>
        </form>
    </div>

<?php elseif ($action == 'delete'): ?>
    <div class="col-md-6 mx-auto">
        <form method="post">
            <h1 class="h3 mb-3 fw-normal">Tür Sil</h1>
            <?php if (!empty($row)): ?>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" value="<?= esc($row['name']) ?>" disabled>
                    <label>Tür Adı</label>
                </div>

                <button type="submit" class="btn btn-danger w-100">Sil</button>
                <a href="<?= ROOT ?>/admin/categories">
                    <button class="btn btn-secondary w-100 mt-2" type="button">Geri</button>
                </a>
            <?php else: ?>
                <div class="alert alert-danger text-center">Kayıt bulunamadı.</div>
            <?php endif; ?>
        </form>
    </div>

<?php else: ?>
    <h4>
        Türler
        <a href="<?= ROOT ?>/admin/categories/add">
            <button class="btn btn-primary">Yeni Tür Ekle</button>
        </a>
    </h4>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tür</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $limit = 10;
                $offset = ($page['page_number'] - 1) * $limit;
                $query = "SELECT * FROM genres ORDER BY id DESC LIMIT $limit OFFSET $offset";
                $rows = query($query);
                ?>
                <?php if (!empty($rows)): ?>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= esc($row['name']) ?></td>
                            <td>
                                <a href="<?= ROOT ?>/admin/categories/edit/<?= $row['id'] ?>" class="btn btn-warning btn-sm text-white"><i class="bi bi-pencil-square"></i></a>
                                <a href="<?= ROOT ?>/admin/categories/delete/<?= $row['id'] ?>" class="btn btn-danger btn-sm"><i class="bi bi-trash-fill"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center">Kayıt bulunamadı</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-between my-3">
        <a href="<?= $page['first_link'] ?>" class="btn btn-outline-primary">İlk Sayfa</a>
        <a href="<?= $page['prev_link'] ?>" class="btn btn-outline-primary">Önceki</a>
        <a href="<?= $page['next_link'] ?>" class="btn btn-outline-primary">Sonraki</a>
    </div>
<?php endif; ?>