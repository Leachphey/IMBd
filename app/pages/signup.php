<?php

if (!empty($_POST)) {
    $errors = [];

    if (empty($_POST['username'])) {
        $errors['username'] = "Kullanıcı ismi gerekiyor";
    } else
    if (!preg_match('/^(?=.*[a-zA-Z])[a-zA-Z0-9_]+$/', $_POST['username'])) {
        $errors['username'] = "Kullanıcı adı en az bir harf içermeli, sadece harf, rakam ve alt çizgi içerebilir.";
    }

    $query = "SELECT id FROM users WHERE email = :email LIMIT 1";
    $email = query($query, ['email' => $_POST['email']], true);



    if (empty($_POST['email'])) {
        $errors['email'] = "Email gerekiyor";
    } else 
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Email Geçerli değil";
    } else
    if ($email) {
        $errors['email'] = "Girdiğiniz mail adresi kullanılmaktadır";
    }


    if (empty($_POST['password'])) {
        $errors['password'] = "Parola gerekiyor";
    } else
    if (strlen($_POST['password']) < 8) {
        $errors['password'] = "Parola 8 karekterden büyük olmalı";
    } else
    if ($_POST['password'] !== $_POST['repassword']) {
        $errors['password'] = "Parolalar birbiriyle uyuşmuyor";
    }

    if (empty($_POST['terms'])) {
        $errors['terms'] = "Sözleşmeyi kabul et";
    }


    if (empty($errors)) {
        $data = [];
        $data['username'] = $_POST['username'];
        $data['email'] = $_POST['email'];
        $data['password_hash'] = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $query = "insert into users (username,email,password_hash) values (:username,:email,:password_hash)";
        query($query, $data);

        redirect('signin');
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>IMBd - Kayıt Ol</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="<?= ROOT ?>/assets/img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="<?= ROOT ?>/assets/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="<?= ROOT ?>/assets/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="<?= ROOT ?>/assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="<?= ROOT ?>/assets/css/style.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid position-relative bg-white d-flex p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->


        <!-- Sign Up Start -->
        <div class="container-fluid">
            <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
                <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                    <div class="bg-light rounded p-4 p-sm-5 my-4 mx-3">
                        <form method="post"> <!-- FORM BAŞLANGICI -->
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <a href="home" class="">
                                    <h3 class="text-primary"><i class="fa fa-hashtag me-2"></i>IMBd</h3>
                                </a>
                                <h3>Kayıt Ol</h3>
                            </div>

                            <?php if (!empty($errors)): ?>
                                <div class="alert alert-danger">Hata!</div>
                            <?php endif; ?>

                            <div class="form-floating mb-3">
                                <input value="<?= old_value('username') ?>" type="text" class="form-control" id="floatingText" name="username" placeholder="jhondoe">
                                <label for="floatingText">Kullanıcı Adı</label>
                            </div>
                            <?php if (!empty($errors['username'])): ?>
                                <div class="text-danger"><?= $errors['username'] ?></div>
                            <?php endif; ?>
                            <div class="form-floating mb-3">
                                <input value="<?= old_value('email') ?>" type="email" class="form-control" id="floatingInput" name="email" placeholder="name@example.com">
                                <label for="floatingInput">Email Adresi</label>
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
                                <input type="password" class="form-control" name="repassword" placeholder="Password">
                                <label for="floatingPassword">Parola</label>
                            </div>
                            <?php if (!empty($errors['password'])): ?>
                                <div class="text-danger"><?= $errors['password'] ?></div>
                            <?php endif; ?>
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="terms" value="1" <?= old_checked('terms') ?>>
                                    <label class="form-check-label" for="exampleCheck1">Şartları kabul ediyorum</label>
                                </div>

                            </div>
                            <?php if (!empty($errors['terms'])): ?>
                                <div class="text-danger"><?= $errors['terms'] ?></div>
                            <?php endif; ?>
                            <button type="submit" class="btn btn-primary py-3 w-100 mb-4">Kayıt Ol</button>
                            <p class="text-center mb-0">Zaten bir hesabın var mı? <a href="signin">Giriş Yap</a></p>
                        </form> <!-- FORM BİTİŞİ -->
                    </div>

                </div>
            </div>
        </div>
        <!-- Sign Up End -->
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= ROOT ?>/assets/lib/easing/easing.min.js"></script>
    <script src="<?= ROOT ?>/assets/lib/waypoints/waypoints.min.js"></script>
    <script src="<?= ROOT ?>/assets/lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="<?= ROOT ?>/assets/lib/tempusdominus/js/moment.min.js"></script>
    <script src="<?= ROOT ?>/assets/lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="<?= ROOT ?>/assets/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="<?= ROOT ?>/assets/js/main.js"></script>
</body>

</html>