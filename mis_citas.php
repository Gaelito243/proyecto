<?php
session_start();
include 'config.php'; // Asegúrate de que la ruta al archivo config.php es correcta

// Verificar si el usuario está logueado y es un cliente
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol_usuario'] != 'cliente') {
    header('Location: login.php');
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$action = isset($_GET['action']) ? $_GET['action'] : 'view_citas';

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

switch ($action) {
    case 'view_citas':
        // Obtener todas las citas del usuario
        $sql = "SELECT citas.id_citas, citas.fecha_cita, citas.hora_cita, servicios.tipo_servicio, servicios.costo_servicio, citas.estado_cita FROM citas
                JOIN servicios ON citas.id_servicio = servicios.id_servicio
                WHERE citas.id_usuario = ?
                ORDER BY citas.fecha_cita, citas.hora_cita";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        ?>    
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>Mis Citas</title>
            <link rel="stylesheet" href="../proyecto/estilos.css" media="screen">
            <link href="https://fonts.googleapis.com/css2?family=Averia+Libre:wght@400;700&display=swap" rel="stylesheet">
            <link href="https://fonts.googleapis.com/css2?family=Aclonica&display=swap" rel="stylesheet">
            <style>
                .body-pago-citas {
                    font-family: 'Averia Libre', sans-serif; /* Fuente para textos pequeños */

                    background-color: #fff0f5; /* Fondo rosa claro */
                    color: #333;
                }
                .custom-container-citas {
                    max-width: 1000px;
                    margin: 280px auto;
                    padding: 20px;
                    border: 1px solid #ddd;
                    border-radius: 0; /* Se eliminan los bordes redondeados */
                    background-color: #ffffff;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                    overflow-x: auto;
                }
                .h2-citas {
                    text-align: center;
                    color: #523750;
                    margin-bottom: 20px;
                    font-family: 'Aclonica', sans-serif; /* Fuente para h2 */
                }
                .table-citas {
                    width: 100%;
                    border-collapse: collapse; /* Asegura que las celdas tengan contornos completos */
                }
                .th-citas, .td-citas {
                    padding: 12px;
                    border: 1px solid #ddd; /* Contorno completo */
                    text-align: left;
                }
                .th-citas {
                    background-color: #523750;
                    color: white;
                    font-family: 'Aclonica', sans-serif; /* Fuente para th */
                }
                .tr-citas:nth-child(even) {
                    background-color: #f9f9f9;
                }
                .actions-citas a {
                    margin-right: 10px;
                    text-decoration: none;
                    color: #ff69b4;
                }
                .actions-citas a:hover {
                    text-decoration: underline;
                }
            </style>
        </head>
        <body class="body-pago-citas">
            <?php include 'navbar.php'; ?>
            <div class="custom-container-citas">
                <h2 class="h2-citas">Mis Citas</h2>
                <table class="table-citas">
                    <tr class="tr-citas">
                        <th class="th-citas">Fecha</th>
                        <th class="th-citas">Hora</th>
                        <th class="th-citas">Servicio</th>
                        <th class="th-citas">Costo</th>
                        <th class="th-citas">Estado</th>
                        <th class="th-citas">Acciones</th>
                    </tr>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr class="tr-citas">
                        <td class="td-citas"><?php echo htmlspecialchars($row['fecha_cita']); ?></td>
                        <td class="td-citas"><?php echo htmlspecialchars($row['hora_cita']); ?></td>
                        <td class="td-citas"><?php echo htmlspecialchars($row['tipo_servicio']); ?></td>
                        <td class="td-citas">$<?php echo htmlspecialchars($row['costo_servicio']); ?></td>
                        <td class="td-citas">
    <?php 
    if ($row['estado_cita'] === 'Pendiente de Confirmar' || $row['estado_cita'] === 'Pendiente de Confirmación') {
        echo 'Pendiente de Confirmar';
    } else {
        echo htmlspecialchars($row['estado_cita']);
    }
    ?>
</td>

                        <td class="td-citas actions-citas">
                            <?php if ($row['estado_cita'] === 'Pendiente') { ?>
                                <a href="?action=upload_voucher&id_cita=<?php echo $row['id_citas']; ?>">Pagar</a>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
            <footer>
            <?php include 'footer.php'; ?>
        </footer>
        </body>
        </html>
        
        <?php
        $stmt->close();
        break;

    case 'upload_voucher':
        $id_cita = isset($_GET['id_cita']) ? $_GET['id_cita'] : '';
        
        if (empty($id_cita)) {
            echo "<p>No se ha proporcionado un ID de cita válido.</p>";
            exit();
        }

        // Preparar la consulta para obtener la cita pendiente
        $sql = "SELECT citas.fecha_cita, citas.hora_cita, servicios.tipo_servicio, servicios.costo_servicio, citas.estado_cita 
                FROM citas
                JOIN servicios ON citas.id_servicio = servicios.id_servicio
                WHERE citas.id_citas = ? AND citas.id_usuario = ? AND citas.estado_cita = 'Pendiente'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $id_cita, $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $cita = $result->fetch_assoc();
            ?>
            
            <!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <title>Subir Voucher de Pago</title>
                <link rel="stylesheet" href="../proyecto/estilos.css" media="screen">
                <link href="https://fonts.googleapis.com/css2?family=Averia+Libre:wght@400;700&display=swap" rel="stylesheet">
                <link href="https://fonts.googleapis.com/css2?family=Aclonica&display=swap" rel="stylesheet">
                <style>
                    .body-pago-citas {
                        font-family: 'Averia Libre', sans-serif; /* Fuente para textos pequeños */
                        margin: 0;
                        padding: 0;
                        background-color: #fff0f5; /* Fondo rosa claro */
                    }
                    .container-pago-citas {
                        max-width: 600px;
                        margin: 200px auto;
                        padding: 20px;
                        border: 1px solid #ddd;
                        border-radius: 0; /* Se eliminan los bordes redondeados */
                        background-color: #ffffff;
                        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                    }
                    .h2-pago-citas {
                        text-align: center;
                        color: #523750;
                        font-family: 'Aclonica', sans-serif; /* Fuente para h2 */
                    }
                    .label-pago-citas, input[type="file"].input-pago-citas, input[type="submit"].input-pago-citas {
                        display: block;
                        width: 100%;
                        margin-bottom: 10px;
                    }
                    input[type="file"].input-pago-citas, input[type="submit"].input-pago-citas {
                        padding: 10px;
                        border: 1px solid #ddd;
                        border-radius: 0; /* Se eliminan los bordes redondeados */
                    }
                    input[type="submit"].input-pago-citas {
                        background-color: #8b568b;
                        color: white;
                        cursor: pointer;
                        transition: background-color 0.3s ease;
                    }
                    input[type="submit"].input-pago-citas:hover {
                        background-color: #a67c9b;
                    }
                    .bank-info-pago-citas {
                        margin-top: 20px;
                        padding: 20px;
                        background-color: #ffffff;
                        border-left: 5px solid #8b568b;
                    }
                    .bank-info-pago-citas h3 {
                        margin-top: 0;
                        color: #ff69b4;
                    }
                </style>
            </head>
            <body class="body-pago-citas">
            <?php include 'navbar.php'; ?>
                <div class="container-pago-citas">
                    <h2 class="h2-pago-citas">Subir Voucher de Pago</h2>
                    <form action="?action=procesar_pago" method="post" enctype="multipart/form-data">
                        <label class="label-pago-citas" for="voucher">Voucher del Depósito:</label>
                        <input class="input-pago-citas" type="file" name="voucher" id="voucher" required>

                        <input type="hidden" name="id_cita" value="<?php echo htmlspecialchars($id_cita); ?>">
                        <input class="input-pago-citas" type="submit" value="Subir Voucher">
                    </form>
                    <div class="bank-info-pago-citas">
                        <h3>Información para Depósito</h3>
                        <p>Banco: Nombre del Banco</p>
                        <p>Cuenta: 123456789</p>
                        <p>CLABE: 012345678901234567</p>
                        <p>Número de Referencia: <?php echo htmlspecialchars($id_cita); ?></p>
                        <p>Precio del Servicio: $<?php echo htmlspecialchars($cita['costo_servicio']); ?></p>
                    </div>
                </div>
                <footer>
            <?php include 'footer.php'; ?>
        </footer>
            </body>
            </html>
            
            <?php
        } else {
            echo "<p>No tienes citas pendientes que requieran subir un voucher o la cita no existe.</p>";
        }
        $stmt->close();
        break;

    case 'procesar_pago':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_cita = $_POST['id_cita'];
            $upload_dir = 'uploads/';

            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $uploaded_file = $upload_dir . basename($_FILES['voucher']['name']);

            if (move_uploaded_file($_FILES['voucher']['tmp_name'], $uploaded_file)) {
                $sql = "UPDATE citas SET voucher_path = ?, estado_cita = 'Pendiente de Confirmar' WHERE id_citas = ?";
                $stmt = $conn->prepare($sql);
                if ($stmt === false) {
                    die('MySQL prepare error: ' . $conn->error);
                }

                $stmt->bind_param('si', $uploaded_file, $id_cita);
                if ($stmt->execute()) {
                    header('Location: ?action=view_citas&upload_success=1');
                } else {
                    header('Location: ?action=view_citas&upload_success=0');
                }
                $stmt->close();
            } else {
                header('Location: ?action=view_citas&upload_success=0');
            }
        }
        break;
}

$conn->close();
?>
