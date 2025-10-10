<?php
require_once '../app/core/init.php';
if (!logged_in()) redirect('signin');

$user = $_SESSION['user'];
$user_id = $user['id'];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['update_profile'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $password2 = trim($_POST['password2']);

    if (empty($username)) $errors['username'] = "Kullanıcı adı boş olamaz.";
    if (empty($email)) $errors['email'] = "E-posta boş olamaz.";

    if (!empty($password)) {
        if ($password != $password2) {
            $errors['password'] = "Şifreler eşleşmiyor.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            query("UPDATE users SET password_hash = :p WHERE id = :id", ['p' => $hashed, 'id' => $user_id]);
        }
    }

    if (empty($errors)) {
        query("UPDATE users SET username = :u, email = :e WHERE id = :id", [
            'u' => $username,
            'e' => $email,
            'id' => $user_id
        ]);
        $_SESSION['user']['username'] = $username;
        $_SESSION['user']['email'] = $email;
        $success = "Profil güncellendi.";
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['delete_account'])) {
    $current_password = $_POST['current_password'];

    $row = query_row("SELECT password_hash FROM users WHERE id = :id", ['id' => $user_id]);
    if (password_verify($current_password, $row['password_hash'])) {
        query("DELETE FROM watchlist WHERE user_id = :uid", ['uid' => $user_id]);
        query("DELETE FROM comments WHERE user_id = :uid", ['uid' => $user_id]);
        query("DELETE FROM users WHERE id = :uid", ['uid' => $user_id]);
        session_destroy();
        redirect('home');
    } else {
        $errors['delete'] = "Şifre yanlış!";
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['clear_watchlist'])) {
    query("DELETE FROM watchlist WHERE user_id = :uid", ['uid' => $user_id]);
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['clear_comments'])) {
    query("DELETE FROM comments WHERE user_id = :uid", ['uid' => $user_id]);
}

?>

<?php include 'includes/header.php'; ?>
<div class="container py-5">
    <h3>Ayarlar</h3>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="form-floating mb-3">
            <input name="username" class="form-control" value="<?= esc($user['username']) ?>">
            <label>Kullanıcı Adı</label>
        </div>
        <div class="form-floating mb-3">
            <input name="email" class="form-control" value="<?= esc($user['email']) ?>">
            <label>E-posta</label>
        </div>
        <div class="form-floating mb-3">
            <input name="password" type="password" class="form-control">
            <label>Yeni Şifre (isteğe bağlı)</label>
        </div>
        <div class="form-floating mb-3">
            <input name="password2" type="password" class="form-control">
            <label>Yeni Şifre Tekrar</label>
        </div>

        <button class="btn btn-primary w-100" name="update_profile" type="submit">Güncelle</button>
    </form>

    <hr class="my-4">

    <form method="post">
        <button class="btn btn-warning w-100 mb-2" name="clear_watchlist" type="submit">İzleme Listemi Temizle</button>
        <button class="btn btn-warning w-100 mb-2" name="clear_comments" type="submit">Yorumlarımı Sil</button>
    </form>

    <hr class="my-4">

    <form method="post">
        <div class="mb-2">Hesabınızı kalıcı olarak silmek için şifrenizi girin:</div>
        <input type="password" name="current_password" class="form-control mb-2" required placeholder="Mevcut şifre">
        <?php if (!empty($errors['delete'])): ?>
            <div class="text-danger mb-2"><?= $errors['delete'] ?></div>
        <?php endif; ?>
        <button class="btn btn-danger w-100" name="delete_account" type="submit">Hesabımı Sil</button>
    </form>
</div>
<?php include 'includes/footer.php'; ?>