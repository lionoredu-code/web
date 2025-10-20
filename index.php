<?php
// 🔹 Iniciar sesión antes de enviar cualquier salida al navegador
session_start();

// Si el usuario ya inició sesión, redirigirlo al dashboard
if (isset($_SESSION['usuario_id'])) {
    header("Location: dashboard.php");
    exit();
}

// Inicializar variables para el mensaje
$mensaje = '';
$clase_mensaje = '';

// Procesar el mensaje de la URL si existe
if (!empty($_GET['mensaje'])) {
    $mensaje = htmlspecialchars($_GET['mensaje']);
    $tipo = $_GET['tipo'] ?? 'error';
    $clase_mensaje = $tipo === 'success' ? 'success' : 'error';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #007bff 0%, #00c6ff 100%);
        }

        .container {
            display: flex;
            width: 100%;
            height: 100vh;
        }

 /* Parte izquierda */
        .left {
            width: 50%;
            background: #007bff;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .left::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: white;
            clip-path: ellipse(120% 80% at left 120%);
            z-index: 0;
        }

        .illustration-container {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: center;
 align-items: center;
            width: 100%;
            height: 100%;
            padding: 20px;
        }

        .illustration-container img {
            max-width: 70%;
            max-height: 70vh;
            object-fit: contain;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Parte derecha */
        .right {
            width: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #e3f2fd, #f8fbff);
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.15);
        }

        .login-box {
            width: 100%;
            max-width: 400px;
            text-align: center;
            padding: 0 30px;
 }

        .login-box img.avatar {
            width: 80px;
            margin-bottom: 10px;
            border-radius: 50%;
        }

        .login-box h1 {
            font-size: 32px;
            color: #1a237e;
            margin-bottom: 40px;
            font-weight: 700;
        }

        .btn {
            width: 100%;
            background: #007bff;
            color: white;
            padding: 15px;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn:hover {
            background: #0056b3;
        }

 .btn-secondary {
            width: 100%;
            background: #6c757d;
            color: white;
            padding: 15px;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 15px;
            transition: background 0.3s ease;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .error { 
            color: #dc3545; 
            background-color: #f8d7da; 
            border: 1px solid #f5c6cb; 
            padding: 10px; 
            border-radius: 5px; 
            margin-bottom: 15px; 
        }

 .success { 
            color: #28a745; 
            background-color: #d4edda; 
            border: 1px solid #c3e6cb; 
            padding: 10px; 
            border-radius: 5px; 
            margin-bottom: 15px; 
        }

        @media (max-width: 900px) {
            .left { display: none; }
            .right { width: 100%; }
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Lado izquierdo -->
    <div class="left">
        <div class="illustration-container">
            <img src="WhatsApp Image 2025-10-08 at 5.13.52 PM.jpeg" alt="Profesionales de la salud">
        </div>
    </div>

    <!-- Lado derecho -->
    <div class="right">
        <div class="login-box">
            <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="avatar" class="avatar">
            <h1>BIENVENIDO</h1>

            <?php
            if (!empty($mensaje)) {
                echo "<p class='$clase_mensaje'>" . htmlspecialchars($mensaje) . "</p>";
            }
            ?>

            <!-- 🔹 Botones conservados -->
            <button type="button" class="btn" onclick="window.location.href='login.php'">IR AL INICIO</button>
            <button type="button" class="btn-secondary" onclick="window.location.href='registro.php'">REGISTRARSE</button>
        </div>
    </div>
</div>

</body>
</html>