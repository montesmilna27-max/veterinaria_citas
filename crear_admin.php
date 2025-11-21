<?php
require_once __DIR__ . '/conexion.php';

$nombre  = 'Admin Principal';
$email   = 'admin@vet.local';
$usuario = 'admin';
$pass    = 'AdminSegura@2025';

$hash = password_hash($pass, PASSWORD_DEFAULT);

// Verificar si ya existe el usuario
$check = $con->prepare("SELECT id FROM usuarios WHERE usuario = ?");
$check->execute([$usuario]);

if ($check->fetch()) {
    die("El usuario ADMIN ya existe.");
}

// Insertar el administrador
$stmt = $con->prepare(
    "INSERT INTO usuarios (nombre, email, usuario, password_hash, rol, activo)
     VALUES (?, ?, ?, ?, 'ADMIN', 1)"
);

$ok = $stmt->execute([$nombre, $email, $usuario, $hash]);

if ($ok) {
    echo "Admin creado correctamente. Usuario: admin / Clave: $pass";
} else {
    echo "Error al crear admin.";
}
