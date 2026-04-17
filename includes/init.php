<?php
define('BASE_URL', '/sistema-tickets/');
// Iniciar sesión (solo si no está iniciada)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Conexión a la BD
require_once __DIR__ . '/../conexion.php';