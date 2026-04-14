<?php
session_start();
require 'conexion.php';

/* 🔥 PROCESAR LOGIN PRIMERO */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['correo'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM usuarios WHERE correo='$correo'";
    $resultado = $conn->query($sql);

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();

        if (password_verify($password, $usuario['password'])) {

            $_SESSION['usuario'] = $usuario['nombre'];
            $_SESSION['id'] = $usuario['id'];
            $_SESSION['rol'] = $usuario['rol'];

            if ($usuario['rol'] == 'admin') {
                header("Location: dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit(); // 🔥 importante

        } else {
            $error = "Contraseña incorrecta";
        }

    } else {
        $error = "Usuario no encontrado";
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="container">
    <h2>Login</h2>

    <form method="POST">
        <input type="email" name="correo" placeholder="Correo" required>
        <div class="password-container">
            <input type="password" name="password" id="password" placeholder="Contraseña" required>
            <i class="fa-solid fa-eye toggle-password" onclick="togglePassword()"></i>
        </div>

        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <button type="submit">Iniciar sesión</button>
    </form>

    <a href="registro.php">Crear cuenta</a>
</div>

<?php include 'includes/footer.php'; ?>