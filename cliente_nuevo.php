<?php
require_once __DIR__ . '/includes/auth.php';
require_role(['ADMIN', 'RECEPCION']);

require_once __DIR__ . '/conexion.php';

$errores = [];
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre    = trim($_POST['nombre'] ?? '');
    $telefono  = trim($_POST['telefono'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');

    if ($nombre === '') {
        $errores[] = 'El nombre es obligatorio.';
    }

    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'El correo no es válido.';
    }

    if (empty($errores)) {

        $stmt = $con->prepare("
            INSERT INTO clientes (nombre, telefono, email, direccion)
            VALUES (:nombre, :telefono, :email, :direccion)
        ");

        $ok = $stmt->execute([
            ':nombre'   => $nombre,
            ':telefono' => $telefono,
            ':email'    => $email,
            ':direccion'=> $direccion
        ]);

        if ($ok) {
            $mensaje = 'Cliente registrado correctamente.';
            $nombre = $telefono = $email = $direccion = '';
        } else {
            $errores[] = 'Error al guardar el cliente.';
        }
    }
}
?>
