<?php
require 'includes/init.php';
require 'includes/auth.php';
requireLogin();

/* 🧹 1. BORRAR ARCHIVOS FÍSICOS */
$result = $conn->query("
    SELECT archivo FROM adjuntos 
    WHERE ticket_id IN (
        SELECT id FROM tickets 
        WHERE estado='cancelado' 
        AND fecha_cancelado <= NOW() - INTERVAL 1 HOUR
    )
");

while($row = $result->fetch_assoc()){
    $ruta = "uploads/" . $row['archivo'];
    if(file_exists($ruta)){
        unlink($ruta);
    }
}

/* 🧹 2. BORRAR REGISTROS DE BD */
$conn->query("
    DELETE t, a FROM tickets t
    LEFT JOIN adjuntos a ON a.ticket_id = t.id
    WHERE t.estado = 'cancelado'
    AND t.fecha_cancelado <= NOW() - INTERVAL 1 HOUR
");

$id_usuario = $_SESSION['id'];

$stmt = $conn->prepare("
SELECT *
FROM tickets
WHERE usuario_id = ?
ORDER BY fecha_creacion DESC
");

$stmt->bind_param("i", $id_usuario);
$stmt->execute();

$tickets = $stmt->get_result();

include 'includes/header.php';
?>

<div class="tickets-container">

<?php while($ticket = $tickets->fetch_assoc()): ?>

<div class="ticket-card">

    <div class="ticket-header">
        <div class="ticket-title">
            <?php echo date('d/m/Y', strtotime($ticket['fecha_creacion'])); ?>
            -
            <?php echo htmlspecialchars($ticket['concepto']); ?>
        </div>

        <span class="ticket-priority">
            <?php echo ucfirst($ticket['prioridad']); ?>
        </span>
    </div>

    <div class="ticket-divider"></div>

    <div class="ticket-device">
        <?php
        echo $ticket['equipo']." ".
             $ticket['marca']." ".
             $ticket['modelo'];
        ?>
    </div>

    <div class="ticket-desc">
        <?php echo htmlspecialchars($ticket['descripcion']); ?>
    </div>


<?php
$estado = strtolower($ticket['estado']);

$estadoClase='pendiente';
$posClase='left';

if($estado=='en proceso'){
   $estadoClase='proceso';
   $posClase='center';
}

if($estado=='resuelto'){
   $estadoClase='resuelto';
   $posClase='right';
}

if($estado=='cancelado' || $estado=='rechazado'){
   $estadoClase='cancelado';
   $posClase='right';
}
?>

<div class="ticket-status">

    <div class="timeline <?php echo $estadoClase; ?>" 
     data-estado="<?php echo $estadoClase; ?>">
        <span></span>
        <span></span>
        <span></span>
    </div>

    <div class="status-label <?php echo $estadoClase; ?> <?php echo $posClase; ?>">
        <?php echo ucfirst($estado); ?>
    </div>

</div>

<?php if ($ticket['estado'] === 'Pendiente'): ?>
    <p class="cancel-ticket" onclick="confirmarCancelacion(<?php echo $ticket['id']; ?>)">
        Cancelar ticket
    </p>
<?php endif; ?>

</div>

<?php endwhile; ?>

</div>

<?php include 'includes/footer.php'; ?>