<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* Clear admin session */
$_SESSION = [];

/* Destroy session completely */
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

session_destroy();

/* IMPORTANT: no output before this */
header("Location: admin_login.php");
exit;