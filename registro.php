<?php
session_start();
require 'conexion.php';

$success = false;
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $area = $_POST['area'];
    $correo = $_POST['correo'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (nombre, area, correo, password)
            VALUES ('$nombre', '$area', '$correo', '$password')";

    if ($conn->query($sql)) {
        $success = true;
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="container">
    <h2>Registro</h2>

    <?php if ($success): ?>
        <p class="success">Usuario registrado exitosamente</p>

        <script>
            setTimeout(() => {
                window.location.href = "login.php";
            }, 2000); // 2 segundos
        </script>
    <?php endif; ?>

    <?php if ($error): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php if (!$success): ?>
    <form method="POST">
        <input type="text" name="nombre" placeholder="Nombre completo" required>
        <input type="text" name="area" placeholder="Área" required>
        <input type="email" name="correo" placeholder="Correo" required>
        <div class="password-container">
            <input type="password" name="password" id="password" placeholder="Contraseña" required>
            <i class="fa-solid fa-eye toggle-password" onclick="togglePassword()"></i>
        </div>

        <button type="submit">Registrarse</button>
    </form>
    <a href="login.php">Ya tengo cuenta</a>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>