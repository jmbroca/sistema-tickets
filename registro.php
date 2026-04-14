<?php
session_start();
require 'conexion.php';

$success = false;
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'] ?? '';
    $area = $_POST['area'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $password = $_POST['password'] ?? '';

    // 🔐 Hash de contraseña
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // 🔍 Verificar si el correo ya existe
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $error = "Este correo ya está registrado";
    } else {
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, area, correo, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nombre, $area, $correo, $passwordHash);

        if ($stmt->execute()) {
            $success = true;
        } else {
            $error = "Error al registrar usuario";
        }
    }

    $stmt->close();
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
            }, 2500); // 2.5 segundos
        </script>
    <?php endif; ?>

    <?php if ($error): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php if (!$success): ?>
    <form method="POST">
        <input type="text" name="nombre" placeholder="Nombre completo" required 
        value="<?php echo htmlspecialchars($nombre ?? ''); ?>">

        <input type="text" name="area" placeholder="Área" required 
        value="<?php echo htmlspecialchars($area ?? ''); ?>">

        <input type="email" name="correo" placeholder="Correo" required 
        value="<?php echo htmlspecialchars($correo ?? ''); ?>">

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