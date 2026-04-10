<?php
session_start();
?>

<?php include 'includes/header.php'; ?>

<div class="container">

    <h2>
        <?php if (isset($_SESSION['usuario'])): ?>
            Bienvenido/a <?php echo $_SESSION['usuario']; ?>
        <?php else: ?>
            Bienvenido/a al sistema de tickets
        <?php endif; ?>
    </h2>

    <p class="subtitle">Panel de usuario</p>

    <div class="actions">

        <?php if (isset($_SESSION['usuario'])): ?>
            <a class="btn" href="crear_ticket.php">Crear ticket</a>
            <a class="btn" href="mis_tickets.php">Mis tickets</a>
            <a class="btn logout" href="logout.php">Cerrar sesión</a>
        <?php else: ?>
            <a class="btn" href="login.php">Crear ticket</a>
            <p class="hint">Inicia sesión para crear un ticket</p>

            <a class="btn" href="login.php">Mis tickets</a>
            <p class="hint">Inicia sesión para ver tus tickets</p>
        <?php endif; ?>

    </div>

</div>

<?php include 'includes/footer.php'; ?>