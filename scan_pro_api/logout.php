<?php
session_start();
session_destroy();
setcookie('user_token', '', time() - 3600, "/");
header("Location: ../app/login_form.html");
exit;
?>