<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['rol'] != 'admin') {
    header("Location: dashboard.php");
    exit();
}
?>

<?php include 'includes/header.php'; ?>

<div class="container">
    <h2>Panel de Administración</h2>

    <p>Bienvenido <?php echo $_SESSION['usuario']; ?></p>

    <a href="logout.php">Cerrar sesión</a>
</div>

<?php include 'includes/footer.php'; ?>