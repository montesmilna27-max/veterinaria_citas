<?php
.card p { font-size: 22px; margin: 0; font-weight: bold; }
</style>
</head>
<body>
<header>
<div>
<strong>VetCitas</strong>
<span class="rol">[<?php echo htmlspecialchars($userRole); ?>]</span>
</div>
<div>
<?php echo htmlspecialchars($userName); ?>
<a href="logout.php">Cerrar sesión</a>
</div>
</header>
<main style="padding:20px;">
<h1>Bienvenido, <?php echo htmlspecialchars($userName); ?></h1>


<div style="margin:20px 0;">
<?php if ($userRole === 'ADMIN'): ?>
<a href="cliente_nuevo.php"
style="padding:8px 12px;background:#00796b;color:#fff;border-radius:4px;text-decoration:none;margin-right:10px;">
+ Nuevo cliente
</a>
<a href="mascota_nueva.php"
style="padding:8px 12px;background:#00796b;color:#fff;border-radius:4px;text-decoration:none;margin-right:10px;">
+ Nueva mascota
</a>
<a href="cita_nueva.php"
style="padding:8px 12px;background:#00796b;color:#fff;border-radius:4px;text-decoration:none;">
+ Nueva cita
</a>
<?php else: ?>
<a href="cita_nueva.php"
style="padding:8px 12px;background:#00796b;color:#fff;border-radius:4px;text-decoration:none;">
+ Agendar nueva cita
</a>
<?php endif; ?>
</div>


<div style="display:flex;gap:20px;flex-wrap:wrap;">
<?php if ($userRole === 'ADMIN'): ?>
<a href="clientes_list.php" style="flex:1;min-width:200px;text-decoration:none;color:inherit;">
<div style="background:#fff;border-radius:6px;padding:15px;box-shadow:0 2px 4px rgba(0,0,0,.1);">
<strong>Clientes registrados</strong>
<div style="font-size:2em;margin-top:10px;"><?php echo $stats['total_clientes']; ?></div>
</div>
</a>


<a href="mascotas_list.php" style="flex:1;min-width:200px;text-decoration:none;color:inherit;">
<div style="background:#fff;border-radius:6px;padding:15px;box-shadow:0 2px 4px rgba(0,0,0,.1);">
<strong>Mascotas registradas</strong>
<div style="font-size:2em;margin-top:10px;"><?php echo $stats['total_mascotas']; ?></div>
</div>
</a>
<?php endif; ?>


<a href="citas_list.php?filtro=hoy" style="flex:1;min-width:200px;text-decoration:none;color:inherit;">
<div style="background:#fff;border-radius:6px;padding:15px;box-shadow:0 2px 4px rgba(0,0,0,.1);">
<strong>Citas de hoy</strong>
<div style="font-size:2em;margin-top:10px;"><?php echo $stats['citas_hoy']; ?></div>
</div>
</a>


<a href="citas_list.php?filtro=pendientes" style="flex:1;min-width:200px;text-decoration:none;color:inherit;">
<div style="background:#fff;border-radius:6px;padding:15px;box-shadow:0 2px 4px rgba(0,0,0,.1);">
<strong>Citas pendientes</strong>
<div style="font-size:2em;margin-top:10px;"><?php echo $stats['citas_pendientes']; ?></div>
</div>
</a>
</div>
</main>


</body>
</html>
