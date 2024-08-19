<?php
session_start();
include 'config.php'; // Asegúrate de que la ruta al archivo config.php es correcta

// Verificar si el usuario está logueado y es un cliente
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol_usuario'] != 'cliente') {
    header('Location: login.php');
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$action = isset($_GET['action']) ? $_GET['action'] : 'view_cart';

switch ($action) {
    case 'view_cart':
        // Obtener los servicios en el carrito
        $servicios = [];
        if (isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0) {
            $ids = implode(',', array_map('intval', array_column($_SESSION['carrito'], 'id_servicio')));
            $sql = "SELECT id_servicio, tipo_servicio, costo_servicio, imagen FROM servicios WHERE id_servicio IN ($ids)";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                $servicios[] = $row;
            }
        }
        ?>

        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="estilos.css" media="screen">
            <link href="https://fonts.googleapis.com/css2?family=Averia+Libre:wght@400;700&display=swap" rel="stylesheet">
            <link href="https://fonts.googleapis.com/css2?family=Aclonica&display=swap" rel="stylesheet">
            <title>Carrito de Compras</title>
            <style>
                .body-carrito {
                    font-family: 'Averia Libre';
                    margin: 0;
                    padding: 0;
                    background-color: #fff0f5;
                    color: #333;
                }
                .container-carrito {
                    max-width: 1000px;
                    margin: 200px auto;
                    padding: 20px;
                    background-color: #fff;
                    border-radius: 8px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }
                .h2-carrito {
                    text-align: center;
                    color: #523750;
                    font-family: 'Aclonica', sans-serif;
                }
                .table-carrito {
                    width: 100%;
                    border-collapse: collapse;
                }
                .th-carrito, .td-carrito {
                    padding: 10px;
                    border: 1px solid #ddd;
                    text-align: left;
                }
                .th-carrito {
                    background-color: #523750;
                    color: #fff;
                    font-family: 'Aclonica', sans-serif;
                }
                .td-carrito img {
                    width: 100px;
                    height: auto;
                    border-radius: 8px;
                }
                .button-carrito {
                    background-color: #e74c3c;
                    color: #fff;
                    border: none;
                    border-radius: 4px;
                    padding: 5px 10px;
                    cursor: pointer;
                    transition: background-color 0.3s ease;
                }
                .button-carrito:hover {
                    background-color: #c0392b;
                }
                .submit-carrito {
                    background-color: #523750;
                    color: #fff;
                    border: none;
                    padding: 10px;
                    border-radius: 4px;
                    cursor: pointer;
                    transition: background-color 0.3s ease;
                    margin-top: 10px;
                }
                .submit-carrito:hover {
                    background-color: #231d25;
                }
            </style>
        </head>
        <body class="body-carrito">
        <header>
            <?php include 'navbar.php'; // Incluir la barra de navegación ?>
        </header>
        <div class="container-carrito">
            <h2 class="h2-carrito">Carrito de Compras</h2>
            <table class="table-carrito">
                <thead>
                    <tr>
                        <th class="th-carrito">Imagen</th>
                        <th class="th-carrito">Servicio</th>
                        <th class="th-carrito">Costo</th>
                        <th class="th-carrito">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($servicios as $servicio) { ?>
                    <tr>
                        <td class="td-carrito"><img src="<?php echo htmlspecialchars($servicio['imagen']); ?>" alt="<?php echo htmlspecialchars($servicio['tipo_servicio']); ?>"></td>
                        <td class="td-carrito"><?php echo htmlspecialchars($servicio['tipo_servicio']); ?></td>
                        <td class="td-carrito">$<?php echo htmlspecialchars($servicio['costo_servicio']); ?></td>
                        <td class="td-carrito">
                            <form action="carrito.php" method="post">
                                <input type="hidden" name="id_servicio" value="<?php echo $servicio['id_servicio']; ?>">
                                <input type="hidden" name="action" value="eliminar">
                                <button class="button-carrito" type="submit">Eliminar</button>
                            </form>
                            <form action="carrito.php?action=agendar" method="get">
                                <input type="hidden" name="id_servicio" value="<?php echo $servicio['id_servicio']; ?>">
                                <input type="hidden" name="action" value="agendar">
                                <button class="submit-carrito" type="submit">Agendar Cita</button>
                            </form>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <footer>
            <?php include 'footer.php'; ?>
        </footer>
        </body>
        </html>
        <?php
        break;

    case 'agendar':
        // Agendar cita para un servicio específico
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_servicio = $_POST['id_servicio'];
            $fecha_cita = $_POST['fecha_cita'];
            $hora_cita = $_POST['hora_cita'];

            // Verificar si ya existe una cita en la misma fecha y hora
            $sql = "SELECT * FROM citas WHERE fecha_cita = ? AND hora_cita = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                die('MySQL prepare error: ' . $conn->error);
            }
            $stmt->bind_param('ss', $fecha_cita, $hora_cita);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $mensaje_error = "Ya existe una cita agendada para esa fecha y hora. Por favor, elige otra hora.";
            } else {
                // Insertar la nueva cita
                $sql = "INSERT INTO citas (fecha_cita, hora_cita, id_usuario, id_servicio, estado_cita) VALUES (?, ?, ?, ?, 'Pendiente')";
                $stmt = $conn->prepare($sql);
                if ($stmt === false) {
                    die('MySQL prepare error: ' . $conn->error);
                }
                $stmt->bind_param('ssii', $fecha_cita, $hora_cita, $id_usuario, $id_servicio);
                if ($stmt->execute()) {
                    $mensaje_exito = "Cita agendada correctamente.";
                    // Eliminar el servicio del carrito después de agendar la cita
                    foreach ($_SESSION['carrito'] as $key => $servicio) {
                        if ($servicio['id_servicio'] == $id_servicio) {
                            unset($_SESSION['carrito'][$key]);
                            break;
                        }
                    }
                    header('Location: carrito.php?action=view_cart');
                    exit();
                } else {
                    $mensaje_error = "Error al agendar la cita: " . $stmt->error;
                }
            }

            $stmt->close();
            $conn->close();
        } else {
            $id_servicio = $_GET['id_servicio'];
            ?>

            <!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" href="estilos.css" media="screen">
                <link href="https://fonts.googleapis.com/css2?family=Averia+Libre:wght@400;700&display=swap" rel="stylesheet">
                <link href="https://fonts.googleapis.com/css2?family=Aclonica&display=swap" rel="stylesheet">
                <title>Agendar Cita</title>
                <style>
                    .body-agendar {
                        font-family: 'Averia Libre', sans-serif;
                        margin: 0;
                        padding: 0;
                        background-color: #fff0f5;
                        color: #333;
                    }
                    .container-agendar {
                        max-width: 600px;
                        margin: 100px auto;
                        padding: 20px;
                        background-color: #fff;
                        border-radius: 8px;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                        text-align: center;
                    }
                    .h2-agendar {
                        text-align: center;
                        color: #523750;
                        font-family: 'Aclonica', sans-serif;
                    }
                    .logo-agendar {
                        width: 100px;
                        height: auto;
                        margin-bottom: 20px;
                    }
                    .form-agendar {
                        display: flex;
                        flex-direction: column;
                        gap: 15px;
                    }
                    .form-agendar label {
                        font-weight: bold;
                        color: #523750;
                    }
                    .form-agendar input {
                        padding: 10px;
                        border: 1px solid #ddd;
                        border-radius: 4px;
                    }
                    .submit-agendar {
                        background-color: #523750;
                        color: #fff;
                        border: none;
                        padding: 10px;
                        border-radius: 4px;
                        cursor: pointer;
                        transition: background-color 0.3s ease;
                    }
                    .submit-agendar:hover {
                        background-color: #231d25;
                    }
                    .return-carrito {
                        background-color: #a67c9b;
                        color: #fff;
                        border: none;
                        padding: 10px;
                        border-radius: 4px;
                        cursor: pointer;
                        margin-top: 20px;
                        transition: background-color 0.3s ease;
                    }
                    .return-carrito:hover {
                        background-color: #8b568b;
                    }
                    footer {
                        margin: 0;
                        padding: 0;
                        border: none;
                    }
                </style>
            </head>
            <body class="body-agendar">
            <header>
                <?php include 'navbar.php'; ?>
            </header>
            <div class="container-agendar">
                <img src="uploads/velvetlogo.png" alt="Logo de la página" class="logo-agendar">
                <h2 class="h2-agendar">Agendar Cita</h2>
                <form class="form-agendar" action="carrito.php?action=agendar" method="post">
                    <input type="hidden" name="id_servicio" value="<?php echo $id_servicio; ?>">
                    <label for="fecha_cita">Fecha:</label>
                    <input type="date" name="fecha_cita" required>
                    <label for="hora_cita">Hora:</label>
                    <input type="time" name="hora_cita" required>
                    <button class="submit-agendar" type="submit">Confirmar Cita</button>
                </form>
                <form action="carrito.php?action=view_cart" method="get">
                    <button class="return-carrito" type="submit">Regresar al Carrito</button>
                </form>
                <?php if (isset($mensaje_error)) { ?>
                    <p style="color: red; text-align: center;"><?php echo $mensaje_error; ?></p>
                <?php } ?>
            </div>
            <footer>
                <?php include 'footer.php'; ?>
            </footer>
            </body>
            </html>

            <?php
        }
        break;
}

?>
