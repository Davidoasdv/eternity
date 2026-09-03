<?php
// includes/auth.php

// Mulai session dengan pengaturan yang aman
if (session_status() === PHP_SESSION_NONE) {
    // Set cookie parameters untuk Railway
    $domain = $_SERVER['HTTP_HOST'] ?? '';
    if (strpos($domain, 'railway.app') !== false) {
        ini_set('session.cookie_domain', '.railway.app');
    }
    session_set_cookie_params([
        'lifetime' => 86400 * 30, // 30 hari
        'path' => '/',
        'domain' => $domain,
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

function isAuthenticated() {
    return isset($_SESSION['user_id']);
}

function requireAuth() {
    if (!isAuthenticated()) {
        header('Location: /login.php');
        exit;
    }
}

function logout() {
    session_destroy();
    header('Location: /login.php');
    exit;
}

function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

function setAuthSession($userId) {
    $_SESSION['user_id'] = $userId;
}
?>
