<?php

// Verifica que el usuario esté logueado
function requireLogin() {
    if (!isset($_SESSION['id'])) {
        header("Location: " . BASE_URL . "login.php");
        exit();
    }
}

// Solo permite acceso a admins
function requireAdmin() {
    if (!isset($_SESSION['id'])) {
        header("Location: " . BASE_URL . "login.php");
        exit();
    }

    if ($_SESSION['rol'] !== 'admin') {
        header("Location: " . BASE_URL . "index.php");
        exit();
    }
}