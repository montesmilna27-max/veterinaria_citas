<?php
require_once __DIR__ . '/includes/auth.php';
require_role(['ADMIN', 'RECEPCION']);
require_once __DIR__ . '/conexion.php';

// Obtener clientes
$stmtClientes = $con->query("SELECT id, nombre FROM clientes ORDER BY nombre ASC");
$clientes = $stmtClientes->fetchAll(PDO::FETCH_ASSOC);

$errores = [];
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $cliente_id = (int)($_POST['cliente_id'] ?? 0);
    $nombre     = trim($_POST['nombre'] ?? '');
    $especie    = trim($_POST['especie'] ?? '');
    $raza       = trim($_POST['raza'] ?? '');
    $fecha_nac  = trim($_POST['fecha_nac'] ?? '');
    $notas      = trim($_POST['notas'] ?? '');

    if ($cliente_id <= 0) {
        $errores[] = 'Debe seleccionar un cliente.';
    }

    if ($nombre === '') {
        $errores[] = 'El nombre de la mascota es obligatorio.';
    }

    $especiesPermitidas = ['PERRO','GATO','OTRO'];
    if ($especie === '' || !in_array($especie, $especiesPermitidas, true)) {
        $errores[] = 'La especie seleccionada no es válida.';
    }

    if ($fecha_nac !== '') {
        $d = DateTime::createFromFormat('Y-m-d', $fecha_nac);
        if (!$d || $d->format('Y-m-d') !== $fecha_nac) {
            $errores[] = 'La fecha de nacimiento no tiene un formato válido.';
        }
    } else {
        $fecha_nac = null;
    }

    if (empty($errores)) {
        $sql = "INSERT INTO mascotas (cliente_id, nombre, especie, raza, fecha_nac, notas)
                VALUES (:cliente_id, :nombre, :especie, :raza, :fecha_nac, :notas)";

        $stmt = $con->prepare($sql);
        $ok = $stmt->execute([
            ':cliente_id' => $cliente_id,
            ':nombre'     => $nombre,
            ':especie'    => $especie,
            ':raza'       => $raza,
            ':fecha_nac'  => $fecha_nac,
            ':notas'      => $notas
        ]);

        if ($ok) {
            $mensaje = 'Mascota registrada correctamente.';
            // limpiar
            $cliente_id = 0;
            $nombre = $especie = $raza = $fecha_nac = $notas = '';
        } else {
            $errores[] = 'Error al guardar la mascota.';
        }
    }
}
?>
