<?php
include 'config.php'; // Asegúrate de que la ruta al archivo config.php es correcta

$mensaje_exito = "";
$mensaje_error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $sugerencia = $_POST['sugerencia'];

    // Validar y limpiar datos
    if (!empty($nombre) && !empty($email) && !empty($sugerencia)) {
        // Insertar sugerencia en la base de datos
        $sql = "INSERT INTO sugerencias (nombre, email, sugerencia) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die('MySQL prepare error: ' . $conn->error);
        }
        $stmt->bind_param('sss', $nombre, $email, $sugerencia);
        if ($stmt->execute()) {
            $mensaje_exito = "Gracias por tu sugerencia. ¡La hemos recibido con éxito!";
        } else {
            $mensaje_error = "Hubo un error al enviar tu sugerencia. Por favor, inténtalo de nuevo.";
        }
        $stmt->close();
    } else {
        $mensaje_error = "Por favor, completa todos los campos.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto y Sugerencias</title>
    <link rel="stylesheet" href="estilos.css" media="screen">
    <style>
        .container-contacto {
            max-width: 800px;
            margin: 100px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2-contacto {
            text-align: center;
            color: #ff69b4;
        }
        p-contacto {
            text-align: center;
            font-size: 16px;
            color: #333;
        }
        .contact-info-contacto, .suggestion-box-contacto {
            margin-bottom: 30px;
        }
        input[type="text"].input-contacto, input[type="email"].input-contacto, textarea.textarea-contacto {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        button.button-contacto {
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
        button.button-contacto:hover {
            background-color: #231d25;
        }
        .mensaje-exito-contacto {
            color: #32cd32;
            text-align: center;
            margin-bottom: 20px;
        }
        .mensaje-error-contacto {
            color: #ff6347;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; // Incluir la barra de navegación ?>

    <div class="container-contacto">
        <h2 class="h2-contacto">Información de Contacto</h2>
        <div class="contact-info-contacto">
            <p class="p-contacto"><strong>Dirección:</strong> Av. Vicepresidente Pino Suárez 750, Cd Industrial, 58200 Morelia, Mich.</p>
            <p class="p-contacto"><strong>Teléfono:</strong> +52 443 123 4567</p>
            <p class="p-contacto"><strong>Email:</strong> info@velvetsstudio.com</p>
        </div>

        <h2 class="h2-contacto">Buzón de Sugerencias</h2>
        <div class="suggestion-box-contacto">
            <?php
            if (!empty($mensaje_exito)) {
                echo "<p class='mensaje-exito-contacto'>$mensaje_exito</p>";
            }
            if (!empty($mensaje_error)) {
                echo "<p class='mensaje-error-contacto'>$mensaje_error</p>";
            }
            ?>
            <form action="contacto.php" method="post">
                <input class="input-contacto" type="text" name="nombre" placeholder="Nombre completo" required>
                <input class="input-contacto" type="email" name="email" placeholder="Correo electrónico" required>
                <textarea class="textarea-contacto" name="sugerencia" placeholder="Tu sugerencia" rows="5" required></textarea>
                <button class="button-contacto" type="submit">Enviar Sugerencia</button>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; // Incluir el footer ?>
</body>
</html>
