<?php if ($action == 'add'): ?>
    <div class="col-md-6 mx-auto">
        <form method="post"> <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">Lütfen hataları düzeltin!</div>
            <?php endif; ?>
            <h1 class="h3 mb-3 fw-normal">Kullanıcı ekle</h1>


            <div class="form-floating mb-3">
                <input value="<?= old_value('username') ?>" type="text" class="form-control" id="floatingText" name="username" placeholder="jhondoe">
                <label for="floatingText">Kullanıcı adı</label>
            </div>
            <?php if (!empty($errors['username'])): ?>
                <div class="text-danger"><?= $errors['username'] ?></div>
            <?php endif; ?>

            <div class="form-floating mb-3">
                <input value="<?= old_value('email') ?>" type="email" class="form-control" id="floatingInput" name="email" placeholder="name@example.com">
                <label for="floatingInput">Email adresi</label>
            </div>
            <?php if (!empty($errors['email'])): ?>
                <div class="text-danger"><?= $errors['email'] ?></div>
            <?php endif; ?>

            <div class="form-floating mb-4">
                <input value="<?= old_value('password') ?>" type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password">
                <label for="floatingPassword">Parola</label>
            </div>
            <?php if (!empty($errors['password'])): ?>
                <div class="text-danger"><?= $errors['password'] ?></div>
            <?php endif; ?>

            <div class="form-floating mb-4">
                <input type="password" class="form-control" name="repassword" placeholder="Retype Password">
                <label for="floatingPassword">Parola</label>
            </div>
            <?php if (!empty($errors['repassword'])): ?>
                <div class="text-danger"><?= $errors['repassword'] ?></div>
            <?php endif; ?>

            <button type="submit" class="btn btn-primary py-3 w-100 mb-4">Sign Up</button>
        </form>
    </div>
<?php elseif ($action == 'edit'): ?>
    <div class="col-md-6 mx-auto">
        <form method="post">
            <h1 class="h3 mb-3 fw-normal">Kullanıcı hesabı düzenle</h1>

            <?php if (!empty($row)): ?>
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">Lütfen hataları düzeltin!</div>
                <?php endif; ?>

                <div class="form-floating mb-3">
                    <input value="<?= old_value('username', $row['username']) ?>" type="text" class="form-control" id="floatingText" name="username" placeholder="jhondoe">
                    <label for="floatingText">Kullanıcı adı</label>
                </div>
                <?php if (!empty($errors['username'])): ?>
                    <div class="text-danger"><?= $errors['username'] ?></div>
                <?php endif; ?>

                <div class="form-floating mb-3">
                    <input value="<?= old_value('email', $row['email']) ?>" type="email" class="form-control" id="floatingInput" name="email" placeholder="name@example.com">
                    <label for="floatingInput">Email adresi</label>
                </div>
                <?php if (!empty($errors['email'])): ?>
                    <div class="text-danger"><?= $errors['email'] ?></div>
                <?php endif; ?>

                <div class="form-floating mb-4">
                    <input value="" type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password (Değiştirmek istemiyorsanız boş bırakın)">
                    <label for="floatingPassword">parola (Değiştirmek istemiyorsanız boş bırakın)</label>
                </div>
                <?php if (!empty($errors['password'])): ?>
                    <div class="text-danger"><?= $errors['password'] ?></div>
                <?php endif; ?>

                <div class="form-floating mb-4">
                    <input type="password" class="form-control" name="repassword" placeholder="Retype Password">
                    <label for="floatingPassword">parola</label>
                </div>
                <?php if (!empty($errors['repassword'])): ?>
                    <div class="text-danger"><?= $errors['repassword'] ?></div>
                <?php endif; ?>

                <button type="submit" class="btn btn-primary py-3 w-100 mb-4 float-end">Kaydet</button>

                <a href="<?= ROOT ?>/admin/users">
                    <button class="btn btn-primary py-3 w-100 mb-4" type="button">Geri</button>
                </a>
            <?php else: ?>
                <div class="alert alert-danger text-center">Kayıt bulunamadı</div>
            <?php endif; ?>
        </form>
    </div>

<?php elseif ($action == 'delete'): ?>

    <div class="col-md-6 mx-auto">
        <form method="post">
            <h1 class="h3 mb-3 fw-normal">Kullanıcı hesabı sil</h1>
            <?php if (!empty($row)): ?>
                <div class="form-floating mb-3">
                    <div class="form-control"> <?= old_value('username', $row['username']) ?> </div>
                    <label for="floatingInput">Kullanıcı ismi</label>
                </div>
                <div class="form-floating mb-3">
                    <div type="email" class="form-control"> <?= old_value('email', $row['email']) ?></div>
                    <label for="floatingInput">Email adresi</label>
                </div>
                <button type="submit" class="btn btn-primary py-3 w-100 mb-4 float-end btn-danger">Hesabı Sil</button>

                <a href="<?= ROOT ?>/admin/users">
                    <button class="btn btn-primary py-3 w-100 mb-4" type="button">Geri</button>
                </a>
            <?php else: ?>
                <div class="alert alert-danger text-center">Kayıt bulunamadı</div>
            <?php endif; ?>
        </form>
    </div>

<?php else: ?>
    <h4>
        Users
        <a href="<?= ROOT ?>/admin/users/add">
            <button class="btn btn-primary">Yeni kullanıcı ekle</button>
        </a>
    </h4>

    <div class="table-responsive">
        <table class="table">
            <tr>
                <th>#</th>
                <th>Kullanıcı adı</th>
                <th>Email adresi</th>
                <th>Rol</th>
                <th>Kayıt tarihi</th>
                <th>Yönet</th>
            </tr>
            <?php

            $limit = 10;
            $offset = ($page['page_number'] - 1) * $limit;

            $query = "SELECT * FROM users ORDER BY id DESC limit $limit offset $offset";
            $rows = query($query);
            ?>
            <?php if (!empty($rows)): ?>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= esc($row['username']) ?></td>
                        <td><?= $row['email'] ?></td>
                        <td><?= $row['role'] ?></td>
                        <td><?= date("d/m/Y", strtotime($row['created_at'])) ?></td>
                        <td>
                            <a href="<?= ROOT ?>/admin/users/edit/<?= $row['id'] ?>">
                                <button class="btn btn-warning text-white btn-sm"><i class="bi bi-pencil-square"></i></button>
                            </a>
                            <a href="<?= ROOT ?>/admin/users/delete/<?= $row['id'] ?>">
                                <button class="btn btn-danger btn-sm"><i class="bi bi-trash-fill"></i></button>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>

        <div class="col-md-12 mb-4">
            <a href="<?= $page['first_link'] ?>">
                <button class="btn btn-primary">İlk Sayfa</button>
            </a>
            <a href="<?= $page['prev_link'] ?>">
                <button class="btn btn-primary">Önceki Sayfa</button>
            </a>

            <a href="<?= $page['next_link'] ?>">
                <button class="btn btn-primary float-end">Sonraki Sayfa</button>
            </a>


        </div>
    </div>
<?php endif; ?>