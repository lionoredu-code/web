<?php
// 🔹 Iniciar sesión antes de enviar cualquier salida
session_start();

// Si el usuario ya ha iniciado sesión, redirigirlo al dashboard
if (isset($_SESSION['usuario_id'])) {
    header("Location: dashboard.php");
    exit();
}

// Inicializar variables para los mensajes de feedback
$mensaje = '';
$clase_mensaje = '';

// Procesar el formulario solo si se envió a través del método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Limpiar y obtener los datos del formulario
    $usuario = trim($_POST['usuario']);
    $contrasena = $_POST['contrasena'];

    // 🔹 Validaciones básicas
    if (empty($usuario) || empty($contrasena)) {
        $mensaje = "Ambos campos son obligatorios.";
        $clase_mensaje = 'error';
    } elseif (strlen($contrasena) < 6) {
        $mensaje = "La contraseña debe tener al menos 6 caracteres.";
        $clase_mensaje = 'error';
    } else {
        try {
            // 🔹 Conexión segura a la base de datos usando PDO
            $pdo = new PDO('mysql:host=localhost;dbname=sistema_login', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Verificar si el nombre de usuario ya existe
            $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE usuario = ?");
            $stmt->execute([$usuario]);

            if ($stmt->rowCount() > 0) {
                $mensaje = "El nombre de usuario ya existe. Por favor, elige otro.";
                $clase_mensaje = 'error';
            } else {
                // Si no existe, hashear la contraseña e insertar el nuevo usuario
                $hash = password_hash($contrasena, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare("INSERT INTO usuarios (usuario, contrasena, rol) VALUES (?, ?, 'usuario')");

                if ($stmt->execute([$usuario, $hash])) {
                    // Si el registro es exitoso, redirigir a la página de login con un mensaje de éxito
                    header("Location: login.php?mensaje=Registro exitoso. ¡Ya puedes iniciar sesión!&tipo=success");
                    exit();
                } else {
                    $mensaje = "Ocurrió un error al crear la cuenta.";
                    $clase_mensaje = 'error';
                }
            }
        } catch (PDOException $e) {
            // Manejo de errores de conexión a la BD
            error_log("Error de base de datos: " . $e->getMessage()); // Guardar error real en logs
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
    <title>Registro de Usuario</title>
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

        /* 🔹 Estilos para los campos del formulario */
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
            <h1>REGISTRO DE USUARIO</h1>

            <?php
            // Muestra el mensaje de error o éxito si existe
            if (!empty($mensaje)) {
                echo "<p class='$clase_mensaje'>" . htmlspecialchars($mensaje) . "</p>";
            }
            ?>

            <form action="registro.php" method="POST">
                <input type="text" name="usuario" placeholder="Nombre de Usuario" class="input-field" required maxlength="50">
                <input type="password" name="contrasena" placeholder="Contraseña (mín. 6 caracteres)" class="input-field" required minlength="6">
                <button type="submit" class="btn">REGISTRARSE</button>
            </form>
            
            <button type="button" class="btn-secondary" onclick="window.location.href='index.php'">VOLVER AL INICIO</button>
        </div>
    </div>
</div>

</body>
</html>