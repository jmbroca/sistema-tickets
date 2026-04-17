<?php
require '../includes/init.php';
require '../includes/auth.php';
requireAdmin();
?>

<?php include '../includes/header.php'; ?>

<div class="container">
    <h2>Panel de Administración</h2>

    <p>Bienvenido <?php echo $_SESSION['usuario']; ?></p>

    <a href="../logout.php">Cerrar sesión</a>
</div>

<?php include '../includes/footer.php'; ?>