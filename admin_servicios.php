<?php
session_start();
include 'config.php';

// Verificar si el usuario está logueado y es un administrador
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol_usuario'] != 'administrador') {
    header('Location: login.php');
    exit();
}

$conn = new mysqli($servername, $username, $password, $dbname);

// Manejar las acciones según el tipo de solicitud
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add_service':
                // Dar de alta un nuevo servicio
                $tipo_servicio = $_POST['tipo_servicio'];
                $costo_servicio = $_POST['costo_servicio'];

                $target_dir = "uploads/";
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0755, true);
                }
                $target_file = $target_dir . basename($_FILES["imagen"]["name"]);

                if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
                    $sql = "INSERT INTO servicios (tipo_servicio, costo_servicio, imagen) VALUES (?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('sis', $tipo_servicio, $costo_servicio, $target_file);
                    $stmt->execute();
                    $stmt->close();
                    $_SESSION['mensaje'] = "Servicio agregado correctamente.";
                } else {
                    $_SESSION['mensaje'] = "Error al subir la imagen.";
                }
                header('Location: admin_servicios.php');
                exit();
                break;

            case 'edit_service':
                // Editar un servicio existente
                $id_servicio = $_POST['id_servicio'];
                $tipo_servicio = $_POST['tipo_servicio'];
                $costo_servicio = $_POST['costo_servicio'];

                if ($_FILES['imagen']['name']) {
                    $target_dir = "uploads/";
                    if (!is_dir($target_dir)) {
                        mkdir($target_dir, 0755, true);
                    }
                    $target_file = $target_dir . basename($_FILES["imagen"]["name"]);

                    if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
                        $sql = "UPDATE servicios SET tipo_servicio = ?, costo_servicio = ?, imagen = ? WHERE id_servicio = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param('sisi', $tipo_servicio, $costo_servicio, $target_file, $id_servicio);
                    } else {
                        $_SESSION['mensaje'] = "Error al subir la imagen.";
                        header('Location: admin_servicios.php');
                        exit();
                    }
                } else {
                    $sql = "UPDATE servicios SET tipo_servicio = ?, costo_servicio = ? WHERE id_servicio = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('sii', $tipo_servicio, $costo_servicio, $id_servicio);
                }
                $stmt->execute();
                $stmt->close();
                $_SESSION['mensaje'] = "Servicio actualizado correctamente.";
                header('Location: admin_servicios.php');
                exit();
                break;

            case 'delete_service':
                // Eliminar un servicio
                $id_servicio = $_POST['id_servicio'];

                $conn->begin_transaction();
                try {
                    $sql = "DELETE FROM servicios WHERE id_servicio = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('i', $id_servicio);
                    $stmt->execute();
                    $stmt->close();
                    $conn->commit();
                    $_SESSION['mensaje'] = "Servicio eliminado correctamente.";
                } catch (Exception $e) {
                    $conn->rollback();
                    $_SESSION['mensaje'] = "Error al eliminar el servicio: " . $e->getMessage();
                }
                header('Location: admin_servicios.php');
                exit();
                break;
        } // Cierre del switch
    } // Cierre del if (isset($_POST['action']))
} // Cierre del if ($_SERVER['REQUEST_METHOD'] == 'POST')

// Obtener todos los servicios
$sql = "SELECT * FROM servicios";
$result_servicios = $conn->query($sql);

// Obtener un servicio específico para editar (si se pasa un id_servicio en la URL)
$servicio_a_editar = null;
if (isset($_GET['id_servicio'])) {
    $id_servicio = $_GET['id_servicio'];
    $sql = "SELECT * FROM servicios WHERE id_servicio = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id_servicio);
    $stmt->execute();
    $result = $stmt->get_result();
    $servicio_a_editar = $result->fetch_assoc();
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head class="head-adminservicios">
    <meta charset="UTF-8" class="meta-adminservicios">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" class="meta-adminservicios">
    <link rel="stylesheet" href="estilos.css" media="screen" class="link-adminservicios">
    <link href='https://fonts.googleapis.com/css?family=Aclonica' rel='stylesheet' class="link-adminservicios">
    <link href='https://fonts.googleapis.com/css?family=Averia+Libre' rel='stylesheet' class="link-adminservicios">
    <title class="title-adminservicios">Administrar Servicios</title>
    <style class="style-adminservicios">
        /* Estilos adicionales */
        .body-adminservicios {
            font-family: 'Averia Libre', sans-serif;
            background-color: #fff0f5; /* Fondo rosa claro */
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container-adminservicios {
            max-width: 800px;
            margin: 150px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .h2-adminservicios {
            text-align: center;
            color: #523750;
            font-family: 'Aclonica', sans-serif;
        }
        .h3-adminservicios {
            color: #523750;
            font-family: 'Aclonica', sans-serif;
        }

        .form-adminservicios {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
        }
        .input-adminservicios {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .button-adminservicios {
            padding: 10px;
            background-color: #8b568c;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }
        .button-adminservicios:hover {
            background-color: #231d25;
        }

        /* Tabla rediseñada */
        .table-adminservicios {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .th-adminservicios, .td-adminservicios {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
            vertical-align: middle;
        }
        .th-adminservicios {
            background-color: #523750;
            color: #fff;
        }
        .td-adminservicios img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 4px;
        }
        .action-buttons-adminservicios {
            gap: 5px;
        }
        .button-edit-adminservicios, .button-delete-adminservicios {
            height: 40px;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s ease;
        }

        .button-edit-adminservicios {
            background-color: #a67c9b;
        }
        .button-edit-adminservicios:hover {
            background-color: #523750;
        }
        .button-delete-adminservicios {
            background-color: #000000;
        }
        .button-delete-adminservicios:hover {
            background-color: #000000;
        }
    </style>
</head>
<body class="body-adminservicios">
    <header class="header-adminservicios">
        <?php include 'navbar.php'; ?>
    </header>

    <div class="container-adminservicios">
        <h2 class="h2-adminservicios">Administrar Servicios</h2>

        <?php if (isset($_SESSION['mensaje'])) { ?>
            <p class="p-adminservicios"><?php echo $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?></p>
        <?php } ?>

        <?php if ($servicio_a_editar) { ?>
            <!-- Formulario para editar un servicio -->
            <h3 class="h3-adminservicios">Editar Servicio</h3>
            <form class="form-adminservicios" action="admin_servicios.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="action" value="edit_service" class="input-hidden-adminservicios">
                <input type="hidden" name="id_servicio" value="<?php echo htmlspecialchars($servicio_a_editar['id_servicio']); ?>" class="input-hidden-adminservicios">
                <input class="input-adminservicios" type="text" name="tipo_servicio" placeholder="Tipo de servicio" value="<?php echo htmlspecialchars($servicio_a_editar['tipo_servicio']); ?>" required>
                <input class="input-adminservicios" type="number" name="costo_servicio" placeholder="Costo del servicio" value="<?php echo htmlspecialchars($servicio_a_editar['costo_servicio']); ?>" required>
                <input class="input-adminservicios" type="file" name="imagen" accept="image/*">
                <button class="button-adminservicios" type="submit">Actualizar Servicio</button>
            </form>
            <a href="admin_servicios.php" class="button-adminservicios">Regresar a la Lista de Servicios</a>
        <?php } elseif (isset($_GET['action']) && $_GET['action'] == 'add_service') { ?>
            <!-- Formulario para agregar un nuevo servicio -->
            <h3 class="h3-adminservicios">Agregar Nuevo Servicio</h3>
            <form class="form-adminservicios" action="admin_servicios.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="action" value="add_service" class="input-hidden-adminservicios">
                <input class="input-adminservicios" type="text" name="tipo_servicio" placeholder="Tipo de servicio" required>
                <input class="input-adminservicios" type="number" name="costo_servicio" placeholder="Costo del servicio" required>
                <input class="input-adminservicios" type="file" name="imagen" accept="image/*" required>
                <button class="button-adminservicios" type="submit">Agregar Servicio</button>
            </form>
            <a href="admin_servicios.php" class="button-adminservicios">Regresar a la Lista de Servicios</a>
        <?php } else { ?>
            <!-- Lista de servicios -->
            <h3 class="h3-adminservicios">Lista de Servicios</h3>
            <a href="admin_servicios.php?action=add_service" class="button-adminservicios">Agregar Nuevo Servicio</a>
            <table class="table-adminservicios">
                <thead class="thead-adminservicios">
                    <tr class="tr-adminservicios">
                        <th class="th-adminservicios">ID</th>
                        <th class="th-adminservicios">Tipo</th>
                        <th class="th-adminservicios">Costo</th>
                        <th class="th-adminservicios">Imagen</th>
                        <th class="th-adminservicios">Acciones</th>
                    </tr>
                </thead>
                <tbody class="tbody-adminservicios">
                    <?php while ($row = $result_servicios->fetch_assoc()) { ?>
                    <tr class="tr-adminservicios">
                        <td class="td-adminservicios"><?php echo htmlspecialchars($row['id_servicio']); ?></td>
                        <td class="td-adminservicios"><?php echo htmlspecialchars($row['tipo_servicio']); ?></td>
                        <td class="td-adminservicios">$<?php echo htmlspecialchars($row['costo_servicio']); ?></td>
                        <td class="td-adminservicios"><img src="<?php echo htmlspecialchars($row['imagen']); ?>" alt="<?php echo htmlspecialchars($row['tipo_servicio']); ?>"></td>
                        <td class="td-adminservicios">
                            <div class="action-buttons-adminservicios">
                                <a href="admin_servicios.php?id_servicio=<?php echo $row['id_servicio']; ?>" class="button-edit-adminservicios">Editar</a>
                                <form action="admin_servicios.php" method="post" class="form-adminservicios" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este servicio?');">
                                    <input type="hidden" name="action" value="delete_service" class="input-hidden-adminservicios">
                                    <input type="hidden" name="id_servicio" value="<?php echo $row['id_servicio']; ?>" class="input-hidden-adminservicios">
                                    <button class="button-delete-adminservicios" type="submit">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    </div>
    <?php include 'footer.php'; ?>

</body>
</html>
