<?php
session_start();
require '../database/koneksi.php';

if (isset($_POST['login'])) {
    // ambil & bersihkan input
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username == '' || $password == '') {
        header('Location: ../login.php?error=1');
        exit;
    }

    // prepared statement (ANTI SQL INJECTION)
    $stmt = mysqli_prepare(
        $konek,
        "SELECT iduser, user, password FROM admin WHERE user = ? LIMIT 1"
    );

    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) === 1) {
        $admin = mysqli_fetch_assoc($result);

        // cek password hash
        if (password_verify($password, $admin['password'])) {

            // cegah session fixation
            session_regenerate_id(true);

            $_SESSION['status_login'] = true;
            $_SESSION['admin_id']     = $admin['iduser'];
            $_SESSION['username']     = $admin['user'];

            header('Location: ../index.php');
            exit;
        } else {
            // jika gagal
            header('Location: ../login.php?error=1');
            exit;
        }
    } else {
        // jika gagal
        header('Location: ../login.php?error=1');
        exit;
    }
}
