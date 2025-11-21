<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica si NO hay sesión activa
if (!isset($_SESSION['user_id']) || !isset($_SESSION['rol'])) {
    header("Location: login.php");
    exit;
}

// Función para proteger páginas por roles
function requireRole($roles = []) {
    if (!isset($_SESSION['rol'])) {
        header("Location: login.php");
        exit;
    }

    if (!in_array($_SESSION['rol'], $roles)) {
        header("Location: no_autorizado.php");
        exit;
    }
}
