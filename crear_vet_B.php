<?php
require_once __DIR__ . '/conexion.php';

$nombre  = 'Dr. Tony';
$email   = 'vet1@vet.local';
$usuario = 'vet1';
$clave   = 'VetSeguro@2025';
$rol     = 'VET';

// Verificar si ya existe
$stmt = $con->prepare("SELECT id FROM usuarios WHERE usuario = :usuario LIMIT 1");
$stmt->execute(['usuario' => $usuario]);
$existe = $stmt->fetch();

if ($existe) {
    die("El usuario veterinario ya existe.");
}

$password_hash = password_hash($clave, PASSWORD_DEFAULT);

// Insertar nuevo veterinario
$stmt = $con->prepare("
    INSERT INTO usuarios (nombre, email, usuario, password_hash, rol, activo)
    VALUES (:nombre, :email, :usuario, :password_hash, :rol, 1)
");

$ok = $stmt->execute([
    'nombre'        => $nombre,
    'email'         => $email,
    'usuario'       => $usuario,
    'password_hash' => $password_hash,
    'rol'           => $rol
]);

if ($ok) {
    echo "Veterinario creado correctamente. Usuario: {$usuario} / Clave: {$clave}";
} else {
    echo "Error al crear veterinario.";
}
