<?php
session_start();

if (isset($_SESSION['status_login'])) {
    header('Location: index.php');
    exit;
}
$page_css = 'css/login.css';
$page_title = 'Login Page';
require 'layouts/headerPage.php';
?>

<body>
    <div class="container-fluid wrapper">
        <div class="row justify-content-center align-items-center h-100">

            <div class="col-xl-8">
                <div class="card login-card shadow-lg overflow-hidden">

                    <div class="row g-0">
                        <!-- IMAGE -->
                        <div class="col-lg-6">
                            <div class="login-image">
                                <img src="img/inspiring.jpg" alt="Login Image">
                            </div>
                        </div>

                        <!-- FORM -->
                        <div class="col-lg-6">
                            <div class="card-body p-5 h-100">

                                <h2 class="fw-bold mb-2 jdl-login">Selamat Datang!</h2>
                                <p class="text-muted mb-4">
                                    Silakan masuk menggunakan akun yang telah terdaftar di sistem.
                                </p>

                                <?php if (isset($_SESSION['error'])): ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="fa fa-times-circle"></i>
                                        <?= $_SESSION['error']; ?>
                                        <button type="button" class="close" data-dismiss="alert">
                                            <span>&times;</span>
                                        </button>
                                    </div>
                                    <?php unset($_SESSION['error']); ?>
                                <?php endif; ?>

                                <form action="proses/Login.php" method="POST">

                                    <div class="mb-3">
                                        <input
                                            type="text"
                                            name="username"
                                            class="form-control"
                                            placeholder="Masukan Username Anda"
                                            autocomplete="off"
                                            required>
                                    </div>

                                    <div class="mb-4">
                                        <div class="password-input">
                                            <input
                                                type="password"
                                                id="password"
                                                name="password"
                                                class="form-control"
                                                placeholder="Password"
                                                autocomplete="off"
                                                required>
                                            <i class="bi bi-eye toggle-password"
                                                onclick="togglePassword('password')"></i>
                                        </div>
                                    </div>

                                    <button
                                        type="submit"
                                        name="login"
                                        class="btn btn-lgn fw-semibold text-white mb-3">
                                        Masuk
                                    </button>

                                    <p class="text-muted small text-center mb-0">
                                        Pastikan data yang anda masukan sudah benar.
                                    </p>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>
</body>
<?php require 'layouts/footerPage.php'; ?>