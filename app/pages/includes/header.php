<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous" />
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/style_public.css">
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="<?= ROOT ?>/home">
                    <img src="<?= ROOT ?>/assets/images/logo/logo_wide.png" alt="IMBd Logo" height="30">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                        <li class="nav-item">
                            <a class="nav-link" href="<?= ROOT ?>/search?find_top_250=1">En iyi 250</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Filmler
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">

                                <form action="<?= ROOT ?>/search" method="get">
                                    <input type="hidden" name="find_top_movie" value="1">
                                    <button type="submit" class="dropdown-item">En Çok Beğenilen Filmler</button>
                                </form>
                                <form action="<?= ROOT ?>/search" method="get">
                                    <input type="hidden" name="recent_movies" value="1">
                                    <button type="submit" class="dropdown-item">En Son Eklenen Filmler</button>
                                </form>


                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown2" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Diziler
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown2">
                                <form action="<?= ROOT ?>/search" method="get">
                                    <input type="hidden" name="top_series" value="1">
                                    <button type="submit" class="dropdown-item">En Çok Beğenilen Diziler</button>
                                </form>
                                <form action="<?= ROOT ?>/search" method="get">
                                    <input type="hidden" name="recent_series" value="1">
                                    <button type="submit" class="dropdown-item">En Son Eklenen Diziler</button>
                                </form>

                            </ul>
                        </li>
                    </ul>

                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <form action="<?= ROOT ?>/search" class="d-flex">
                                <input value="<?= $_GET['find'] ?? '' ?>" name="find" class="form-control me-2" type="search" placeholder="Dizi/Filim ismi aratın" aria-label="Search" />
                                <button class="btn btn-outline-success" type="submit">Ara</button>
                            </form>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="accountDropdown" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <?php if (!logged_in()): ?>
                                    Giriş yap
                                <?php endif ?>

                                <?php if (is_admin()): ?>
                                    Yönetici Hesabım
                                <?php endif ?>

                                <?php if (logged_in() && !is_admin()): ?>
                                    Hesabım
                                <?php endif ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown">

                                <?php if (!logged_in()): ?>
                                    <li><a class="dropdown-item" href="signin">Giriş Yap</a></li>
                                    <li><a class="dropdown-item" href="signup">Üye Ol</a></li>
                                <?php endif ?>




                                <?php if (logged_in()): ?>

                                    <form action="<?= ROOT ?>/search" method="get">
                                        <input type="hidden" name="watchlist" value="1">
                                        <button type="submit" class="dropdown-item">İzleme Listem</button>
                                    </form>
                                    <li><a class="dropdown-item" href="<?= ROOT ?>/account_settings">Ayarlar</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="<?= ROOT ?>/logout">Çıkış Yap</a></li>
                                <?php endif ?>

                                <?php if (is_admin()): ?>
                                    <li><a class="dropdown-item" href="<?= ROOT ?>/admin">Yönetim</a></li>
                                <?php endif ?>

                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
            </div>
        </nav>
    </header>