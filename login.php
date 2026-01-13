<?php
session_start();

if (isset($_SESSION['status_login'])) {
    header('Location: index.php');
    exit;
}
$page_css = 'css/stylee.css';
$page_title = 'Login Page';
require 'layouts/headerPage.php';
?>

<body>
    <div class="wrapper">
        <form action="proses/Login.php" method="post">
            <h1>Login</h1>

            <div class="input-box">
                <input type="text" name="username" placeholder="Masukan Username Anda" required>
            </div>

            <div class="input-box">
                <input type="password" name="password" placeholder="Masukan Password Anda" required>
            </div>

            <?php if (isset($_GET['error'])): ?>
                <p style="color:red;">Username atau password salah</p>
            <?php endif; ?>

            <button type="submit" name="login" class="btn">Login</button>
        </form>
    </div>
</body>
<?php require 'layouts/footerPage.php'; ?>