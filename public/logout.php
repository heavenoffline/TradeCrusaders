<?php
session_start();

// Clear all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

//remove session cookie (more secure cleanup)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Redirect user after logout
header("Location: ../index.php");
exit();
