<?php
session_start();
include 'config.php'; // Asegúrate de que la ruta al archivo config.php es correcta

// Verificar si el usuario está logueado y es un cliente
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol_usuario'] != 'cliente') {
    header('Location: login.php');
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// Obtener las notificaciones del usuario
$sql = "SELECT * FROM notificaciones WHERE id_usuario = ? ORDER BY fecha DESC";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die('MySQL prepare error: ' . $conn->error);
}
$stmt->bind_param('i', $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Notificaciones</title>
    <link rel="stylesheet" href="../proyecto/estilos.css" media="screen">
    <link href="https://fonts.googleapis.com/css2?family=Averia+Libre:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Aclonica&display=swap" rel="stylesheet">
    <style>
        .body-notificaciones {
            font-family: 'Averia Libre', sans-serif; /* Fuente para textos pequeños */
            background-color: #fff0f5; /* Fondo rosa claro */
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container-notificaciones {
            max-width: 600px;
            margin: 200px auto; /* Margen superior aumentado */
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px; /* Borde redondeado */
            background-color: #ffffff; /* Fondo blanco */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .h2-notificaciones {
            text-align: center;
            color: #523750; /* Color de título rosa */
            margin-bottom: 20px;
            font-family: 'Aclonica', sans-serif; /* Fuente para h2 */
        }
        .notificacion-notificaciones {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }
        .notificacion-notificaciones:last-child {
            border-bottom: none;
        }
        .fecha-notificaciones {
            color: #999;
            font-size: 12px;
        }
    </style>
</head>
<body class="body-notificaciones">
    <?php include 'navbar.php'; // Incluir la barra de navegación ?>
    <div class="container-notificaciones">
        <h2 class="h2-notificaciones">Mis Notificaciones</h2>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <div class="notificacion-notificaciones">
                <p><?php echo htmlspecialchars($row['mensaje']); ?></p>
                <p class="fecha-notificaciones"><?php echo htmlspecialchars($row['fecha']); ?></p>
            </div>
        <?php } ?>
    </div>
    <?php include 'footer.php'; ?>

</body>
</html>
