<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "velvet";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Manejar solicitudes POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && $_POST['action'] == 'login') {
        // Manejar inicio de sesión
        $correo_electronico = $_POST['correo_electronico'];
        $password = $_POST['password'];

        $sql = "SELECT id_usuario, nombre_usuario, password, rol_usuario FROM usuario WHERE correo_electronico = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Error al preparar la consulta: " . $conn->error);
        }

        $stmt->bind_param("s", $correo_electronico);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id_usuario, $nombre_usuario, $stored_password, $rol_usuario);
            $stmt->fetch();

            if ($password === $stored_password) { // Comparar contraseñas directamente
                $_SESSION['id_usuario'] = $id_usuario;
                $_SESSION['nombre_usuario'] = $nombre_usuario;
                $_SESSION['rol_usuario'] = $rol_usuario;

                if ($rol_usuario == 'cliente') {
                    header("Location: cliente.php");
                } elseif ($rol_usuario == 'administrador') {
                    header("Location: admin.php");
                } else {
                    echo "Rol de usuario no reconocido.";
                }
                exit();
            } else {
                echo "Contraseña incorrecta.";
            }
        } else {
            echo "No se encontró una cuenta con ese correo electrónico.";
        }

        $stmt->close();

    } elseif (isset($_POST['action']) && $_POST['action'] == 'register') {
        // Manejar registro de usuario
        $nombre_usuario = $_POST['nombre_usuario'];
        $correo_electronico = $_POST['correo_electronico'];
        $password = $_POST['password'];
        $rol_usuario = "cliente";

        $sql = "INSERT INTO usuario (nombre_usuario, correo_electronico, password, rol_usuario) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Error al preparar la consulta: " . $conn->error);
        }

        $stmt->bind_param("ssss", $nombre_usuario, $correo_electronico, $password, $rol_usuario);

        if ($stmt->execute()) {
            echo "Registro exitoso. <a href='#' onclick='toggleForms()'>Inicia sesión aquí</a>";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head class="head-acceso">
    <meta charset="UTF-8" class="meta-acceso">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" class="meta-acceso">
    <link rel="stylesheet" href="estilos.css" media="screen" class="link-acceso">
    <link href='https://fonts.googleapis.com/css?family=Aclonica' rel='stylesheet' class="link-acceso">
    <link href='https://fonts.googleapis.com/css?family=Averia+Libre' rel='stylesheet' class="link-acceso">
    <title class="title-acceso">Registro y Login</title>
    <style class="style-acceso">
        /* Estilos adicionales */
        .body-acceso {
            font-family: 'Averia Libre', sans-serif; /* Fuente para textos pequeños */
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container-acceso {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            margin: 200px auto;
        }

        .h2-acceso {
            text-align: center;
            margin-bottom: 20px;
            color: #000000;
            font-family: 'Aclonica', sans-serif; /* Fuente para h2 */
        }

        .input-acceso {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        .button-acceso {
            width: 100%;
            padding: 10px;
            background-color: #523750;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        .button-acceso:hover {
            background-color: #231d25;
        }

        .toggle-acceso {
            text-align: center;
            margin-top: 20px;
        }

        .toggle-acceso a {
            color: #007bff;
            text-decoration: none;
        }

        .toggle-acceso a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body class="body-acceso">
<header>
    <?php include("navbar.php"); ?>
</header>

    <div class="container-acceso" id="loginContainer">
        <h2 class="h2-acceso">Iniciar Sesión</h2>
        <form method="POST" action="">
            <input type="hidden" name="action" value="login">
            <input class="input-acceso" type="email" name="correo_electronico" placeholder="Correo electrónico" required>
            <input class="input-acceso" type="password" name="password" placeholder="Contraseña" required>
            <button class="button-acceso" type="submit">Iniciar Sesión</button>
        </form>
        <div class="toggle-acceso">
            <span>¿No tienes una cuenta? <a href="#" onclick="toggleForms()">Regístrate aquí</a></span>
        </div>
    </div>

    <div class="container-acceso" id="registerContainer" style="display:none;">
        <h2 class="h2-acceso">Registro</h2>
        <form method="POST" action="">
            <input type="hidden" name="action" value="register">
            <input class="input-acceso" type="text" name="nombre_usuario" placeholder="Nombre completo" required>
            <input class="input-acceso" type="email" name="correo_electronico" placeholder="Correo electrónico" required>
            <input class="input-acceso" type="password" name="password" placeholder="Contraseña" required>
            <button class="button-acceso" type="submit">Registrarse</button>
        </form>
        <div class="toggle-acceso">
            <span>¿Ya tienes una cuenta? <a href="#" onclick="toggleForms()">Inicia sesión aquí</a></span>
        </div>
    </div>
    <?php include 'footer.php'; ?>
    <script>
        function toggleForms() {
            var registerContainer = document.getElementById('registerContainer');
            var loginContainer = document.getElementById('loginContainer');
            
            if (registerContainer.style.display === 'none') {
                registerContainer.style.display = 'block';
                loginContainer.style.display = 'none';
            } else {
                registerContainer.style.display = 'none';
                loginContainer.style.display = 'block';
            }
        }
    </script>
</body>
</html>
