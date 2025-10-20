<?php
session_start();

// Bloque de seguridad: solo permite el acceso a administradores
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: dashboard.php?mensaje=Acceso no autorizado.&tipo=error");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Gestionar Usuarios</title>
    </head>
<body>
    <h1>Panel de Gestión de Usuarios</h1>
    <p>Esta página solo puede ser vista por administradores.</p>
    <a href="dashboard.php">Volver al Dashboard</a>
</body>
</html>