<?php
session_start();
include 'config.php';

// Verificar si el usuario está logueado y es un administrador
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol_usuario'] != 'administrador') {
    header('Location: login.php');
    exit();
}

$conn = new mysqli($servername, $username, $password, $dbname);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $id_cita = $_POST['id_cita'];
        switch ($_POST['action']) {
            case 'delete':
                // Borrar una cita
                $sql = "DELETE FROM citas WHERE id_citas = ?";
                $stmt = $conn->prepare($sql);
                if ($stmt === false) {
                    die('MySQL prepare error: ' . $conn->error);
                }
                $stmt->bind_param('i', $id_cita);
                if ($stmt->execute()) {
                    $_SESSION['mensaje'] = "Cita borrada correctamente.";
                } else {
                    $_SESSION['mensaje'] = "Error al borrar la cita: " . $stmt->error;
                }
                $stmt->close();
                break;

            case 'change_state':
                // Cambiar estado de la cita
                $nuevo_estado = $_POST['nuevo_estado'];
                
                $sql = "SELECT usuario.id_usuario, usuario.correo_electronico, citas.fecha_cita, citas.hora_cita 
                        FROM citas 
                        JOIN usuario ON citas.id_usuario = usuario.id_usuario 
                        WHERE citas.id_citas = ?";
                $stmt = $conn->prepare($sql);
                if ($stmt === false) {
                    die('MySQL prepare error: ' . $conn->error);
                }
                $stmt->bind_param('i', $id_cita);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $correo_electronico = $row['correo_electronico'];
                $fecha_cita = $row['fecha_cita'];
                $hora_cita = $row['hora_cita'];
                $id_usuario = $row['id_usuario'];

                $sql = "UPDATE citas SET estado_cita = ? WHERE id_citas = ?";
                $stmt2 = $conn->prepare($sql);
                if ($stmt2 === false) {
                    die('MySQL prepare error: ' . $conn->error);
                }
                $stmt2->bind_param('si', $nuevo_estado, $id_cita);
                if ($stmt2->execute()) {
                    $_SESSION['mensaje'] = "Estado de la cita actualizado correctamente.";

                    $sql = "INSERT INTO notificaciones (id_usuario, mensaje) VALUES (?, ?)";
                    $stmt3 = $conn->prepare($sql);
                    if ($stmt3 === false) {
                        die('MySQL prepare error: ' . $conn->error);
                    }
                    $mensaje = "Su cita para el $fecha_cita a las $hora_cita ha sido actualizada a '$nuevo_estado'.";
                    $stmt3->bind_param('is', $id_usuario, $mensaje);
                    $stmt3->execute();
                    $stmt3->close();
                } else {
                    $_SESSION['mensaje'] = "Error al actualizar el estado de la cita: " . $stmt2->error;
                }
                $stmt->close();
                $stmt2->close();
                break;

            case 'change_time':
                // Cambiar horario de la cita
                $fecha_cita = $_POST['fecha_cita'];
                $hora_cita = $_POST['hora_cita'];

                $sql = "SELECT * FROM citas WHERE fecha_cita = ? AND hora_cita = ? AND id_citas != ?";
                $stmt = $conn->prepare($sql);
                if ($stmt === false) {
                    die('MySQL prepare error: ' . $conn->error);
                }
                $stmt->bind_param('ssi', $fecha_cita, $hora_cita, $id_cita);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $_SESSION['mensaje'] = "Ya existe una cita agendada dentro de una hora y media de la hora solicitada. Por favor, elige otra hora.";
                } else {
                    $sql = "SELECT usuario.id_usuario, usuario.correo_electronico 
                            FROM citas 
                            JOIN usuario ON citas.id_usuario = usuario.id_usuario 
                            WHERE citas.id_citas = ?";
                    $stmt2 = $conn->prepare($sql);
                    if ($stmt2 === false) {
                        die('MySQL prepare error: ' . $conn->error);
                    }
                    $stmt2->bind_param('i', $id_cita);
                    $stmt2->execute();
                    $result2 = $stmt2->get_result();
                    $row = $result2->fetch_assoc();
                    $correo_electronico = $row['correo_electronico'];
                    $id_usuario = $row['id_usuario'];

                    $sql = "UPDATE citas SET fecha_cita = ?, hora_cita = ? WHERE id_citas = ?";
                    $stmt3 = $conn->prepare($sql);
                    if ($stmt3 === false) {
                        die('MySQL prepare error: ' . $conn->error);
                    }
                    $stmt3->bind_param('ssi', $fecha_cita, $hora_cita, $id_cita);
                    if ($stmt3->execute()) {
                        $_SESSION['mensaje'] = "Horario de la cita actualizado correctamente.";

                        $sql = "INSERT INTO notificaciones (id_usuario, mensaje) VALUES (?, ?)";
                        $stmt4 = $conn->prepare($sql);
                        if ($stmt4 === false) {
                            die('MySQL prepare error: ' . $conn->error);
                        }
                        $mensaje = "Su cita ha sido reprogramada para el $fecha_cita a las $hora_cita.";
                        $stmt4->bind_param('is', $id_usuario, $mensaje);
                        $stmt4->execute();
                        $stmt4->close();
                    } else {
                        $_SESSION['mensaje'] = "Error al actualizar el horario de la cita: " . $stmt3->error;
                    }
                    $stmt2->close();
                    $stmt3->close();
                }
                $stmt->close();
                break;
        }
    }

    header('Location: administrar_citas.php');
    exit();
}

// Obtener todas las citas
$sql = "SELECT citas.id_citas, citas.fecha_cita, citas.hora_cita, citas.estado_cita, citas.voucher_path, usuario.nombre_usuario, usuario.correo_electronico, servicios.tipo_servicio 
        FROM citas
        JOIN usuario ON citas.id_usuario = usuario.id_usuario
        JOIN servicios ON citas.id_servicio = servicios.id_servicio
        ORDER BY citas.fecha_cita, citas.hora_cita";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head class="head-admin-citas">
    <meta charset="UTF-8" class="meta-admin-citas">
    <title class="title-admin-citas">Administrar Citas</title>
    <link href='https://fonts.googleapis.com/css?family=Aclonica' rel='stylesheet' class="link-admin-citas">
    <link href='https://fonts.googleapis.com/css?family=Averia+Libre' rel='stylesheet' class="link-admin-citas">
    <style class="style-admin-citas">
        .body-admin-citas {
            font-family: 'Averia Libre', sans-serif; /* Fuente para textos pequeños */
            background-color: #fff0f5; /* Fondo rosa claro */
            color: #333;
            margin: 0;
            padding: 0;
        }

        .h2-admin-citas {
            text-align: center;
            color: #523750;
            padding: 20px 0;
            font-size: 2em;
            font-family: 'Aclonica', sans-serif; /* Fuente para h2 */
        }

        .container-admin-citas {
            width: 90%;
            margin: 2 00px auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .table-admin-citas {
            width: 100%;
            margin: 30px 0;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .th-admin-citas, .td-admin-citas {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .th-admin-citas {
            background-color: #523750;
            color: #FFFFFF;
        }

        .tr-admin-citas:nth-child(even) {
            background-color: #f2f2f2;
        }

        .tr-admin-citas:hover {
            background-color: #a891a7;
        }

        .form-admin-citas {
            display: inline;
        }

        .input-admin-citas[type="date"], .input-admin-citas[type="time"], .select-admin-citas {
            padding: 6px;
            margin: 4px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .button-admin-citas {
            background-color: #826380;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 4px 0;
        }

        .button-admin-citas:hover {
            background-color: #523750;
        }

        .link-admin-citas {
            color: #523750;
            text-decoration: none;
        }

        .link-admin-citas:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body class="body-admin-citas">
<header class="header-admin-citas">
    <?php include("navbar.php"); ?>
</header>
<?php if (isset($_SESSION['mensaje'])) { ?>
    <p class="p-admin-citas" style="color: green; text-align: center;"><?php echo $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?></p>
<?php } ?>

<div class="container-admin-citas">
    <h2 class="h2-admin-citas">Administrar Citas</h2>

    <table class="table-admin-citas">
        <tr class="tr-admin-citas">
            <th class="th-admin-citas">ID Cita</th>
            <th class="th-admin-citas">Fecha</th>
            <th class="th-admin-citas">Hora</th>
            <th class="th-admin-citas">Usuario</th>
            <th class="th-admin-citas">Correo Electrónico</th>
            <th class="th-admin-citas">Servicio</th>
            <th class="th-admin-citas">Estado</th>
            <th class="th-admin-citas">Acciones</th>
            <th class="th-admin-citas">Voucher</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr class="tr-admin-citas">
            <td class="td-admin-citas"><?php echo htmlspecialchars($row['id_citas']); ?></td>
            <td class="td-admin-citas"><?php echo htmlspecialchars($row['fecha_cita']); ?></td>
            <td class="td-admin-citas"><?php echo htmlspecialchars($row['hora_cita']); ?></td>
            <td class="td-admin-citas"><?php echo htmlspecialchars($row['nombre_usuario']); ?></td>
            <td class="td-admin-citas"><?php echo htmlspecialchars($row['correo_electronico']); ?></td>
            <td class="td-admin-citas"><?php echo htmlspecialchars($row['tipo_servicio']); ?></td>
            <td class="td-admin-citas"><?php echo htmlspecialchars($row['estado_cita']); ?></td>
            <td class="td-admin-citas">
                <form action="administrar_citas.php" method="post" class="form-admin-citas">
                    <input type="hidden" name="id_cita" value="<?php echo htmlspecialchars($row['id_citas']); ?>" class="input-hidden-admin-citas">
                    <input type="hidden" name="action" value="change_time" class="input-hidden-admin-citas">
                    <input type="date" name="fecha_cita" value="<?php echo htmlspecialchars($row['fecha_cita']); ?>" required class="input-admin-citas">
                    <input type="time" name="hora_cita" value="<?php echo htmlspecialchars($row['hora_cita']); ?>" required class="input-admin-citas">
                    <input type="submit" value="Cambiar Horario" class="button-admin-citas">
                </form>
                <form action="administrar_citas.php" method="post" class="form-admin-citas">
                    <input type="hidden" name="id_cita" value="<?php echo htmlspecialchars($row['id_citas']); ?>" class="input-hidden-admin-citas">
                    <input type="hidden" name="action" value="change_state" class="input-hidden-admin-citas">
                    <select name="nuevo_estado" class="select-admin-citas">
                        <option value="Pendiente" <?php echo $row['estado_cita'] === 'Pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                        <option value="Confirmada" <?php echo $row['estado_cita'] === 'Confirmada' ? 'selected' : ''; ?>>Confirmada</option>
                        <option value="Cancelada" <?php echo $row['estado_cita'] === 'Cancelada' ? 'selected' : ''; ?>>Cancelada</option>
                    </select>
                    <input type="submit" value="Cambiar Estado" class="button-admin-citas">
                </form>
                <form action="administrar_citas.php" method="post" class="form-admin-citas" onsubmit="return confirm('¿Estás seguro de que deseas borrar esta cita?');">
                    <input type="hidden" name="id_cita" value="<?php echo htmlspecialchars($row['id_citas']); ?>" class="input-hidden-admin-citas">
                    <input type="hidden" name="action" value="delete" class="input-hidden-admin-citas">
                    <input type="submit" value="Borrar" class="button-admin-citas">
                </form>
            </td>
            <td class="td-admin-citas">
                <?php if (!empty($row['voucher_path'])) { ?>
                    <a href="<?php echo htmlspecialchars($row['voucher_path']); ?>" target="_blank" class="link-admin-citas">Ver Voucher</a>
                <?php } else { ?>
                    No disponible
                <?php } ?>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
