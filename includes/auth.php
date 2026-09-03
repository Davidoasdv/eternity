<?php
// includes/auth.php
session_start();

function isAuthenticated() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function requireAuth() {
    if (!isAuthenticated()) {
        header('Location: /eternity/login.php');
        exit;
    }
}

function logout() {
    session_destroy();
    header('Location: /eternity/login.php');
    exit;
}

function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

function setAuthSession($userId) {
    $_SESSION['user_id'] = $userId;
}
?>
