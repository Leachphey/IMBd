<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


<?php if ($action == 'add'): ?>
    <div class="col-md-8 mx-auto">
        <form method="post" enctype="multipart/form-data">
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">Lütfen hataları düzeltin!</div>
            <?php endif; ?>

            <h1 class="h3 mb-3 fw-normal">Film / Dizi Ekle</h1>

            <div class="form-floating mb-3">
                <input value="<?= old_value('title') ?>" type="text" class="form-control" id="floatingTitle" name="title" required>
                <label for="floatingTitle">Başlık</label>
            </div>
            <?php if (!empty($errors['title'])): ?>
                <div class="text-danger"><?= $errors['title'] ?></div>
            <?php endif; ?>

            <div class="form-floating mb-3">
                <select class="form-select" id="floatingType" name="type">
                    <option value="movie" <?= old_value('type') == 'movie' ? 'selected' : '' ?>>Film</option>
                    <option value="series" <?= old_value('type') == 'series' ? 'selected' : '' ?>>Dizi</option>
                </select>
                <label for="floatingType">Tür</label>
            </div>
            <?php if (!empty($errors['type'])): ?>
                <div class="text-danger"><?= $errors['type'] ?></div>
            <?php endif; ?>

            <div class="form-floating mb-3">
                <textarea class="form-control" id="floatingDesc" name="description" style="height: 150px;"><?= old_value('description') ?></textarea>
                <label for="floatingDesc">Açıklama</label>
            </div>
            <?php if (!empty($errors['description'])): ?>
                <div class="text-danger"><?= $errors['description'] ?></div>
            <?php endif; ?>

            <div class="mb-3">
                <label class="form-label">Poster Görseli</label>
                <input class="form-control" type="file" name="poster">
            </div>
            <?php if (!empty($errors['poster'])): ?>
                <div class="text-danger"><?= $errors['poster'] ?></div>
            <?php endif; ?>

            <div class="form-floating mb-3">
                <input value="<?= old_value('release_year') ?>" type="date" class="form-control" name="release_year">
                <label>Çıkış Tarihi (gg/aa/yyyy)</label>
            </div>
            <?php if (!empty($errors['release_year'])): ?>
                <div class="text-danger"><?= $errors['release_year'] ?></div>
            <?php endif; ?>

            <div class="form-floating mb-3">
                <input value="<?= old_value('duration_minutes') ?>" type="number" class="form-control" name="duration_minutes">
                <label>Süre (Dakika)</label>
            </div>
            <?php if (!empty($errors['duration_minutes'])): ?>
                <div class="text-danger"><?= $errors['duration_minutes'] ?></div>
            <?php endif; ?>

            <?php $genres = query("SELECT * FROM genres ORDER BY name"); ?>
            <div class="mb-3">
                <label for="genre_ids" class="form-label">Kategoriler</label>
                <select class="form-select select2" id="genre_ids" name="genre_ids[]" multiple required>
                    <?php foreach ($genres as $genre): ?>
                        <option value="<?= $genre['id'] ?>"><?= esc($genre['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="form-text">Arama yapabilir, çoklu seçim yapabilirsiniz.</div>
            </div>

            <?php if (!empty($errors['genre_ids'])): ?>
                <div class="text-danger"><?= $errors['genre_ids'] ?></div>
            <?php endif; ?>

            <?php $actors = query("SELECT * FROM actors ORDER BY name"); ?>
            <div class="mb-3">
                <label for="actor_ids" class="form-label">Oyuncular</label>
                <select class="form-select select2" id="actor_ids" name="actor_ids[]" multiple>
                    <?php foreach ($actors as $actor): ?>
                        <option value="<?= $actor['id'] ?>"><?= esc($actor['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="form-text">Oyuncu arayıp çoklu seçim yapabilirsiniz.</div>
            </div>


            <button type="submit" class="btn btn-primary w-100 py-2">Kaydet</button>
        </form>
    </div>

<?php elseif ($action == 'edit'): ?>
    <div class="col-md-8 mx-auto">
        <form method="post" enctype="multipart/form-data">
            <h1 class="h3 mb-3 fw-normal">Film / Dizi Düzenle</h1>

            <?php if (!empty($row)): ?>
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">Lütfen hataları düzeltin!</div>
                <?php endif; ?>

                <!-- Başlık -->
                <div class="form-floating mb-3">
                    <input value="<?= old_value('title', $row['title']) ?>" type="text" class="form-control" name="title">
                    <label>Başlık</label>
                </div>

                <!-- Açıklama -->
                <div class="form-floating mb-3">
                    <textarea class="form-control" name="description" style="height: 150px;"><?= old_value('description', $row['description']) ?></textarea>
                    <label>Açıklama</label>
                </div>

                <!-- Mevcut poster -->
                <?php if (!empty($row['poster_url'])): ?>
                    <div class="mb-3">
                        <img src="<?= ROOT . '/' . $row['poster_url'] ?>" alt="Poster" height="100">
                    </div>
                <?php endif; ?>

                <!-- Yeni poster yükleme -->
                <div class="mb-3">
                    <label class="form-label">Yeni Poster Yükle</label>
                    <input class="form-control" type="file" name="poster">
                </div>

                <!-- Çıkış Tarihi -->
                <div class="form-floating mb-3">
                    <input value="<?= old_value('release_year', $row['release_year']) ?>" type="date" class="form-control" name="release_year">
                    <label>Çıkış Tarihi</label>
                </div>

                <!-- Tür Seçimi -->
                <div class="mb-3">
                    <label class="form-label">Tür</label>
                    <select class="form-select select2" name="type" required>
                        <option value="movie" <?= old_value('type', $row['type']) == 'movie' ? 'selected' : '' ?>>Film</option>
                        <option value="series" <?= old_value('type', $row['type']) == 'series' ? 'selected' : '' ?>>Dizi</option>
                    </select>
                </div>
                <?php if (!empty($errors['type'])): ?>
                    <div class="text-danger"><?= $errors['type'] ?></div>
                <?php endif; ?>



                <?php if (!empty($errors['type'])): ?>
                    <div class="text-danger"><?= $errors['type'] ?></div>
                <?php endif; ?>


                <!-- Süre -->
                <div class="form-floating mb-3">
                    <input value="<?= old_value('duration_minutes', $row['duration_minutes']) ?>" type="number" class="form-control" name="duration_minutes">
                    <label>Süre (dakika)</label>
                </div>

                <!-- Kategoriler -->
                <?php
                $genres = query("SELECT * FROM genres ORDER BY name");
                $selected_genres = query("SELECT genre_id FROM movie_genres WHERE movie_id = :id", ['id' => $row['id']]);
                $selected_ids = array_column($selected_genres, 'genre_id');
                ?>
                <div class="mb-3">
                    <label class="form-label">Kategoriler</label>
                    <select class="form-control" name="genre_ids[]" multiple size="5">
                        <?php foreach ($genres as $genre): ?>
                            <option value="<?= $genre['id'] ?>" <?= in_array($genre['id'], $selected_ids) ? 'selected' : '' ?>>
                                <?= esc($genre['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- aktör seçim -->
                <?php
                $actors = query("SELECT * FROM actors ORDER BY name");
                $selected_actors = query("SELECT actor_id FROM movie_actors WHERE movie_id = :id", ['id' => $row['id']]);
                $selected_actor_ids = array_column($selected_actors, 'actor_id');
                ?>
                <div class="mb-3">
                    <label class="form-label">Oyuncular</label>
                    <select class="form-select select2" name="actor_ids[]" multiple>
                        <?php foreach ($actors as $actor): ?>
                            <option value="<?= $actor['id'] ?>" <?= in_array($actor['id'], $selected_actor_ids) ? 'selected' : '' ?>>
                                <?= esc($actor['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>



                <button type="submit" class="btn btn-primary w-100 py-2">Kaydet</button>
            <?php else: ?>
                <div class="alert alert-danger text-center">Film veya dizi bulunamadı.</div>
            <?php endif; ?>
        </form>
    </div>


<?php elseif ($action == 'delete'): ?>
    <div class="col-md-6 mx-auto">
        <form method="post">
            <h1 class="h3 mb-3 fw-normal">Film / Dizi Sil</h1>
            <?php if (!empty($row)): ?>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" value="<?= esc($row['title']) ?>" disabled>
                    <label>Başlık</label>
                </div>

                <div class="form-floating mb-3">
                    <textarea class="form-control" disabled><?= esc($row['description']) ?></textarea>
                    <label>Açıklama</label>
                </div>

                <button type="submit" class="btn btn-danger w-100">Sil</button>
                <a href="<?= ROOT ?>/admin/movie_save">
                    <button class="btn btn-secondary w-100 mt-2" type="button">Geri</button>
                </a>
            <?php else: ?>
                <div class="alert alert-danger text-center">Kayıt bulunamadı</div>
            <?php endif; ?>
        </form>
    </div>


<?php else: ?>
    <h4>
        Dizi/Filim Ekle
        <a href="<?= ROOT ?>/admin/movie_save/add">
            <button class="btn btn-primary">Ekle</button>
        </a>
    </h4>

    <div class="table-responsive">
        <table class="table">
            <tr>
                <th>#</th>
                <th>Adı</th>
                <th>Tanıtım yazısı</th>
                <th>Çıkış Yılı</th>
                <th>Süre</th>
                <th>Puan</th>
                <th>Yönet</th>
            </tr>
            <?php

            $limit = 10;
            $offset = ($page['page_number'] - 1) * $limit;

            $query = "SELECT * FROM movies ORDER BY id DESC limit $limit offset $offset";
            $rows = query($query);
            ?>
            <?php if (!empty($rows)): ?>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= esc($row['title']) ?></td>
                        <td><?= substr(esc($row['description']), 0, 50) . (strlen(esc($row['description'])) > 50 ? '...' : '') ?></td>
                        <td><?= $row['release_year'] ?></td>
                        <td><?= $row['duration_minutes'] ?></td>
                        <td><?= $row['rating'] ?></td>
                        <td>
                            <a href="<?= ROOT ?>/admin/movie_save/edit/<?= $row['id'] ?>">
                                <button class="btn btn-warning text-white btn-sm"><i class="bi bi-pencil-square"></i></button>
                            </a>
                            <a href="<?= ROOT ?>/admin/movie_save/delete/<?= $row['id'] ?>">
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

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        $('.select2').select2({
            width: '100%',
            placeholder: "Seçiniz",
            allowClear: true
        });
    });
</script>