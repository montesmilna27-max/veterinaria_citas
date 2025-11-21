<?php
session_start();
require_once __DIR__ . '/conexion.php'; // Usa $con (PDO)

$alerta = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $usuario  = trim($_POST['usuario']  ?? '');
    $password = $_POST['password'] ?? '';

    if ($usuario === '' || $password === '') {
        $alerta = 'Debe ingresar usuario y contraseña.';
    } else {

        // --- Buscar usuario ---
        $stmt = $con->prepare("
            SELECT id, nombre, usuario, password_hash, rol, activo
            FROM usuarios
            WHERE usuario = :usuario
            LIMIT 1
        ");
        $stmt->execute(['usuario' => $usuario]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $ip  = $_SERVER['REMOTE_ADDR']      ?? '';
        $ua  = $_SERVER['HTTP_USER_AGENT'] ?? '';

        if ($user && (int)$user['activo'] === 1 && password_verify($password, $user['password_hash'])) {

            session_regenerate_id(true);

            $_SESSION['user_id']   = (int)$user['id'];
            $_SESSION['user_name'] = $user['nombre'] ?: $user['usuario'];
            $_SESSION['user_role'] = $user['rol'];

            // Registrar auditoría LOGIN_OK
            $stmtAud = $con->prepare("
                INSERT INTO auditoria (usuario_id, accion, detalle, ip, user_agent)
                VALUES (:uid, :accion, :detalle, :ip, :ua)
            ");
            $stmtAud->execute([
                'uid'     => $user['id'],
                'accion'  => 'LOGIN_OK',
                'detalle' => 'Inicio de sesión correcto',
                'ip'      => $ip,
                'ua'      => $ua
            ]);

            header("Location: dashboard.php");
            exit;

        } else {

            $alerta = 'Usuario o contraseña incorrectos.';

            $uid     = $user['id'] ?? null;
            $detalle = $user
                ? "Intento fallido para usuario existente: {$user['usuario']}"
                : "Intento fallido para usuario NO existente: {$usuario}";

            // Registrar auditoría LOGIN_FAIL
            $stmtAud = $con->prepare("
                INSERT INTO auditoria (usuario_id, accion, detalle, ip, user_agent)
                VALUES (:uid, :accion, :detalle, :ip, :ua)
            ");
            $stmtAud->execute([
                'uid'     => $uid,
                'accion'  => 'LOGIN_FAIL',
                'detalle' => $detalle,
                'ip'      => $ip,
                'ua'      => $ua
            ]);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ingreso - VetCitas</title>
    <style>
        body { font-family: Arial, sans-serif; background:#f5f5f5; }
        .login-box {
            max-width: 360px; margin:60px auto; padding:20px;
            background:#fff; border-radius:6px;
            box-shadow:0 2px 6px rgba(0,0,0,.15);
        }
        h1 { font-size:1.3rem; margin-bottom:15px; }
        label { display:block; margin-top:10px; }
        input[type=text], input[type=password] {
            width:100%; padding:8px; box-sizing:border-box;
        }
        .btn {
            margin-top:15px; padding:8px 12px;
            background:#00796b; color:#fff; border:none;
            cursor:pointer; border-radius:4px;
        }
        .alerta { color:#c00; margin-top:10px; }
    </style>
</head>
<body>
<div class="login-box">
    <h1>VetCitas - Iniciar sesión</h1>

    <?php if ($alerta): ?>
        <div class="alerta"><?= htmlspecialchars($alerta) ?></div>
    <?php endif; ?>

    <form method="post">
        <label for="usuario">Usuario</label>
        <input type="text" name="usuario" id="usuario" autocomplete="username" required>

        <label for="password">Contraseña</label>
        <input type="password" name="password" id="password" autocomplete="current-password" required>

        <button type="submit" class="btn">Entrar</button>
    </form>
</div>
</body>
</html>
