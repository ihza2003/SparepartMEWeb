<?php
//blum login
if (!isset($_SESSION['status_login'])) {
    header('Location: loginPage.php');
    exit;
}
