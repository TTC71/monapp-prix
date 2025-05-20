<?php
session_start();
if (!isset($_SESSION['token']) && !isset($_COOKIE['user_token'])) {
    header("Location: ../app/login_form.html");
    exit;
}
?>