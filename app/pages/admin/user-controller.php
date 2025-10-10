<?php

if ($section === 'users' && $action === 'add') {
    if (!empty($_POST)) {
        $errors = [];

        if (empty($_POST['username'])) {
            $errors['username'] = "Kullanıcı ismi gerekiyor";
        } elseif (!preg_match('/^(?=.*[a-zA-Z])[a-zA-Z0-9_]+$/', $_POST['username'])) {
            $errors['username'] = "Kullanıcı adı en az bir harf içermeli, sadece harf, rakam ve alt çizgi içerebilir.";
        }

        $query = "SELECT id FROM users WHERE email = :email LIMIT 1";
        $email_check = query($query, ['email' => $_POST['email']]);

        if (empty($_POST['email'])) {
            $errors['email'] = "Email gerekiyor";
        } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Email geçerli değil";
        } elseif ($email_check) {
            $errors['email'] = "Girdiğiniz mail adresi kullanılmaktadır";
        }

        if (empty($_POST['password'])) {
            $errors['password'] = "Parola gerekiyor";
        } elseif (strlen($_POST['password']) < 8) {
            $errors['password'] = "Parola 8 karakterden büyük olmalı";
        } elseif ($_POST['password'] !== $_POST['repassword']) {
            $errors['password'] = "Parolalar birbiriyle uyuşmuyor";
            $errors['repassword'] = "Parolalar birbiriyle uyuşmuyor";
        }

        if (empty($errors)) {
            $data = [
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'password_hash' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                'role' => 'user'
            ];

            $query = "INSERT INTO users (username, email, password_hash, role) VALUES (:username, :email, :password_hash, :role)";
            query($query, $data);

            redirect('admin/users');
        }
    }
} elseif ($section === 'users' && $action === 'edit') {
    $query = "SELECT * FROM users WHERE id = :id LIMIT 1";
    $row = query_row($query, ['id' => $id]);

    if (!empty($_POST) && $row) {
        $errors = [];

        if (empty($_POST['username'])) {
            $errors['username'] = "Kullanıcı ismi gerekiyor";
        } elseif (!preg_match('/^(?=.*[a-zA-Z])[a-zA-Z0-9_]+$/', $_POST['username'])) {
            $errors['username'] = "Kullanıcı adı en az bir harf içermeli, sadece harf, rakam ve alt çizgi içerebilir.";
        }

        $query = "SELECT id FROM users WHERE email = :email AND id != :id LIMIT 1";
        $email_check = query($query, ['email' => $_POST['email'], 'id' => $id]);

        if (empty($_POST['email'])) {
            $errors['email'] = "Email gerekiyor";
        } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Email geçerli değil";
        } elseif ($email_check) {
            $errors['email'] = "Girdiğiniz mail adresi kullanılmaktadır";
        }

        if (!empty($_POST['password'])) {
            if (strlen($_POST['password']) < 8) {
                $errors['password'] = "Parola 8 karakterden büyük olmalı";
            } elseif ($_POST['password'] !== $_POST['repassword']) {
                $errors['password'] = "Parolalar birbiriyle uyuşmuyor";
                $errors['repassword'] = "Parolalar birbiriyle uyuşmuyor";
            }
        }

        if (empty($errors)) {
            $data = [
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'role' => $row['role'],
                'id' => $id,
            ];

            if (empty($_POST['password'])) {
                $query = "UPDATE users SET username = :username, email = :email, role = :role WHERE id = :id LIMIT 1";
            } else {
                $data['password_hash'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $query = "UPDATE users SET username = :username, email = :email, password_hash = :password_hash, role = :role WHERE id = :id LIMIT 1";
            }

            query($query, $data);
            redirect('admin/users');
        }
    }
} elseif ($section === 'users' && $action === 'delete') {
    $query = "SELECT * FROM users WHERE id = :id LIMIT 1";
    $row = query_row($query, ['id' => $id]);

    if ($_SERVER["REQUEST_METHOD"] == 'POST' && $row) {


        $data = [
            'id' => $id,
        ];

        $query = "DELETE FROM users WHERE id = :id LIMIT 1";
        query($query, $data);
        redirect('admin/users');
    }
}
