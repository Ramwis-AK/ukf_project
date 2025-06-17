<?php
// Spusti session
session_start();

// Unset všetky session premenné
$_SESSION = array();

// Zmaž session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Zruš session
session_destroy();

// Presmeruj na hlavnú stránku
header("location: index.php");
exit;
?>