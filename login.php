<?php
// 🔹 Iniciar sesión antes de cualquier salida
session_start();

// Si el usuario ya inició sesión, redirigirlo directamente al panel principal
if (isset($_SESSION['usuario_id'])) {
    header("Location: dashboard.php");
    exit();
}

// Inicializar variables para los mensajes
$mensaje = '';
$clase_mensaje = '';

// Procesar el mensaje de la URL (si vienes de un registro exitoso)
if (!empty($_GET['mensaje'])) {
    $mensaje = htmlspecialchars($_GET['mensaje']);
    $tipo = $_GET['tipo'] ?? 'error';
    $clase_mensaje = $tipo === 'success' ? 'success' : 'error';
}

// Procesar el formulario de login cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario']);
    $contrasena = $_POST['contrasena'];
    
    // Validar que los campos no estén vacíos
    if (empty($usuario) || empty($contrasena)) {
        $mensaje = "Usuario y contraseña son requeridos.";
        $clase_mensaje = 'error';
    } else {
        try {
            // 🔹 Conexión a la base de datos
            $pdo = new PDO('mysql:host=localhost;dbname=sistema_login', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Buscar al usuario en la base de datos
            $stmt = $pdo->prepare("SELECT id, usuario, contrasena, rol FROM usuarios WHERE usuario = ?");
            $stmt->execute([$usuario]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Verificar si el usuario existe y la contraseña es correcta
            if ($user && password_verify($contrasena, $user['contrasena'])) {
                // Login exitoso: crear variables de sesión
                $_SESSION['usuario_id'] = $user['id'];
                $_SESSION['usuario'] = $user['usuario'];
                $_SESSION['rol'] = $user['rol'];
                
                // Redirigir al panel de control
                header("Location: dashboard.php");
                exit();
            } else {
                // Si las credenciales son incorrectas
                $mensaje = "Usuario o contraseña incorrectos.";
                $clase_mensaje = 'error';
            }
        } catch (PDOException $e) {
            error_log("Error de base de datos: " . $e->getMessage());
            $mensaje = "Error de conexión con el sistema. Inténtalo más tarde.";
            $clase_mensaje = 'error';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
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
            margin-bottom: 25px;
            font-weight: 700;
        }

        /* Estilos para los campos del formulario */
        .input-field {
            width: 100%;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 1px solid #ced4da;
            font-size: 16px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .input-field:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.4);
        }

        /* Estilos para los botones */
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

        /* Estilos para los mensajes de error y éxito */
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

        /* Diseño responsivo */
        @media (max-width: 900px) {
            .left { display: none; }
            .right { width: 100%; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="left">
        <div class="illustration-container">
            <img src="WhatsApp Image 2025-10-08 at 5.13.52 PM.jpeg" alt="Profesionales de la salud">
        </div>
    </div>

    <div class="right">
        <div class="login-box">
            <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="avatar" class="avatar">
            <h1>INICIAR SESIÓN</h1>

            <?php
            // Muestra el mensaje de error o éxito si existe
            if (!empty($mensaje)) {
                echo "<p class='$clase_mensaje'>" . htmlspecialchars($mensaje) . "</p>";
            }
            ?>

            <form action="login.php" method="POST">
                <input type="text" name="usuario" placeholder="Nombre de Usuario" class="input-field" required>
                <input type="password" name="contrasena" placeholder="Contraseña" class="input-field" required>
                <button type="submit" class="btn">INGRESAR</button>
            </form>
            
            <button type="button" class="btn-secondary" onclick="window.location.href='index.php'">VOLVER AL INICIO</button>
        </div>
    </div>
</div>

</body>
</html>