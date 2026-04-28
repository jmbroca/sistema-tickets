<?php
require 'includes/init.php';
require 'includes/auth.php';
requireLogin();

// 🔽 Obtener ENUMs desde la BD
$conceptoEnum = $conn->query("SHOW COLUMNS FROM tickets LIKE 'concepto'")->fetch_assoc();
$equipoEnum = $conn->query("SHOW COLUMNS FROM tickets LIKE 'equipo'")->fetch_assoc();
$marcaEnum = $conn->query("SHOW COLUMNS FROM tickets LIKE 'marca'")->fetch_assoc();
$prioridadEnum = $conn->query("SHOW COLUMNS FROM tickets LIKE 'prioridad'")->fetch_assoc();

function getEnumValues($enum) {
    preg_match("/^enum\((.*)\)$/", $enum, $matches);
    return str_getcsv($matches[1], ',', "'");
}

$conceptos = getEnumValues($conceptoEnum['Type']);
$equipos = getEnumValues($equipoEnum['Type']);
$marcas = getEnumValues($marcaEnum['Type']);
$prioridades = getEnumValues($prioridadEnum['Type']);

$success = false;
$error = "";

// 🔽 PROCESAR FORMULARIO
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $concepto = $_POST['concepto'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $equipo = $_POST['equipo'] ?? '';
    $marca = $_POST['marca'] ?? '';
    $modelo = $_POST['modelo'] ?? '';
    $prioridad = $_POST['prioridad'] ?? '';
    $usuario_id = $_SESSION['id'];

    if (empty($concepto) || empty($descripcion) || empty($modelo)) {
        $error = "Todos los campos son obligatorios";
    } else {

        $estado = 'pendiente';
        $motivo = null;
        
        $stmt = $conn->prepare("
            INSERT INTO tickets 
            (concepto, descripcion, equipo, marca, modelo, prioridad, estado, motivo_rechazo, usuario_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "ssssssssi",
            $concepto,
            $descripcion,
            $equipo,
            $marca,
            $modelo,
            $prioridad,
            $estado,
            $motivo,
            $usuario_id
        );

        if ($stmt->execute()) {

        $ticket_id = $conn->insert_id;

        // Procesar adjuntos si existen
        if (!empty($_FILES['adjuntos']['name'][0])) {

            if (count($_FILES['adjuntos']['name']) > 3) {
                $error = "Máximo 3 archivos.";
            } else {

                $permitidos = [
                    'application/pdf',
                    'image/jpeg',
                    'image/png'
                ];

                $rutaDestino = "uploads/";

                foreach ($_FILES['adjuntos']['tmp_name'] as $i => $tmp) {
                    
                    if ($_FILES['adjuntos']['error'][$i] !== 0) {
                        continue;
                    }

                    $mime = mime_content_type($tmp);

                    if (!in_array($mime, $permitidos)) {
                        continue;
                    }

                    $extension = pathinfo(
                        $_FILES['adjuntos']['name'][$i],
                        PATHINFO_EXTENSION
                    );

                    // nombre único
                    $nuevoNombre = uniqid('ticket_') . "." . $extension;

                    if (
                        move_uploaded_file(
                            $tmp,
                            $rutaDestino . $nuevoNombre
                        )
                    ) {

                        $adj = $conn->prepare("
                            INSERT INTO adjuntos
                            (ticket_id, archivo)
                            VALUES (?, ?)
                        ");

                        $adj->bind_param(
                            "is",
                            $ticket_id,
                            $nuevoNombre
                        );

                        $adj->execute();
                        $adj->close();
                    }
                }
            }
        }

        $success = true;

    } else {
        $error = "Error al crear el ticket";
    }

        $stmt->close();
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="container-2">
    <h2>Crear Ticket</h2>

    <?php if ($success): ?>
        <p class="success">Ticket creado correctamente</p>

        <script>
            setTimeout(() => {
                window.location.href = "index.php";
            }, 2500);
        </script>
    <?php endif; ?>

    <?php if ($error): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php if (!$success): ?>
    <form method="POST" enctype="multipart/form-data">

        <!-- CONCEPTO -->
        <select name="concepto" required>
            <option value="">Selecciona un concepto</option>
            <?php foreach($conceptos as $c): ?>
                <option value="<?php echo $c; ?>"><?php echo $c; ?></option>
            <?php endforeach; ?>
        </select>

        <!-- DESCRIPCIÓN -->
        <textarea name="descripcion" placeholder="Descripción del problema" required></textarea>

        <!-- EQUIPO -->
        <select name="equipo" required>
            <option value="">Selecciona equipo</option>
            <?php foreach($equipos as $e): ?>
                <option value="<?php echo $e; ?>"><?php echo $e; ?></option>
            <?php endforeach; ?>
        </select>

        <!-- MARCA -->
        <select name="marca" required>
            <option value="">Selecciona marca</option>
            <?php foreach($marcas as $m): ?>
                <option value="<?php echo $m; ?>"><?php echo $m; ?></option>
            <?php endforeach; ?>
        </select>

        <!-- MODELO -->
        <input type="text" name="modelo" placeholder="Modelo" required>

        <!-- PRIORIDAD -->
        <select name="prioridad" required>
            <option value="">Selecciona prioridad</option>
            <?php foreach($prioridades as $p): ?>
                <option value="<?php echo $p; ?>"><?php echo $p; ?></option>
            <?php endforeach; ?>
        </select>

        <!-- ADJUNTOS (placeholder) -->
        <input
        type="file"
        id="adjuntos"
        name="adjuntos[]"
        multiple
        accept=".pdf,.jpg,.jpeg,.png">
        
        <p id="archivo-error" class="error" style="display:none;"></p>
        <p class="hint">
        Opcional: hasta 3 archivos (PDF, JPG, PNG)
        </p><br>

        <button type="submit">Crear ticket</button>
    </form>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>