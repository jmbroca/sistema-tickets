<?php
require 'includes/init.php';
require 'includes/auth.php';
requireLogin();

$id = $_GET['id'] ?? null;
$usuario_id = $_SESSION['id'];

if (!$id) {
    header("Location: mis_tickets.php");
    exit();
}

// Verificar que el ticket pertenece al usuario y está pendiente
$stmt = $conn->prepare("SELECT * FROM tickets WHERE id = ? AND usuario_id = ?");
$stmt->bind_param("ii", $id, $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    header("Location: mis_tickets.php");
    exit();
}

$ticket = $resultado->fetch_assoc();

if ($ticket['estado'] !== 'Pendiente') {
    header("Location: mis_tickets.php");
    exit();
}

// Cancelar ticket
$stmt = $conn->prepare("UPDATE tickets SET estado = 'Cancelado', fecha_cancelado = NOW() WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: mis_tickets.php");
exit();