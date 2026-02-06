<?php
session_start();
require '../database/koneksi.php';

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username == '' || $password == '') {
        $_SESSION['error'] = "Username / Password tidak boleh kosong !!"; // simpan di session
        header('Location: ../loginPage.php');
        exit;
    }

    $stmt = mysqli_prepare(
        $konek,
        "SELECT iduser, user, password FROM admin WHERE user = ? LIMIT 1"
    );

    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) === 1) {
        $admin = mysqli_fetch_assoc($result);

        if (password_verify($password, $admin['password'])) {
            session_regenerate_id(true);

            $_SESSION['status_login'] = true;
            $_SESSION['admin_id']     = $admin['iduser'];
            $_SESSION['username']     = $admin['user'];

            header('Location: ../index.php');
            exit;
        } else {
            $_SESSION['error'] = "Username / Password salah !!";
            header('Location: ../loginPage.php');
            exit;
        }
    } else {
        $_SESSION['error'] = "Username / Password salah !!";
        header('Location: ../loginPage.php');
        exit;
    }
}
