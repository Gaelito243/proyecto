<?php
session_start();
include 'config.php';

// Verificar si el usuario está logueado y es un administrador
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol_usuario'] != 'administrador') {
    header('Location: login.php');
    exit();
}

$conn = new mysqli($servername, $username, $password, $dbname);

// Procesar el formulario de agregar o editar usuario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'edit':
                // Editar un usuario existente
                $id_usuario = $_POST['id_usuario'];
                $nombre_usuario = $_POST['nombre_usuario'];
                $correo_electronico = $_POST['correo_electronico'];
                $password = $_POST['password'];
                $rol_usuario = $_POST['rol_usuario'];

                $sql = "UPDATE usuario SET nombre_usuario = ?, correo_electronico = ?, password = ?, rol_usuario = ? WHERE id_usuario = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('ssssi', $nombre_usuario, $correo_electronico, $password, $rol_usuario, $id_usuario);
                if ($stmt->execute()) {
                    $_SESSION['mensaje'] = 'Usuario actualizado correctamente.';
                } else {
                    $_SESSION['mensaje'] = 'Error al actualizar el usuario.';
                }
                $stmt->close();
                header('Location: admin_usuarios.php');
                exit();
                break;

            case 'add':
                // Dar de alta un nuevo usuario
                $nombre_usuario = $_POST['nombre_usuario'];
                $correo_electronico = $_POST['correo_electronico'];
                $password = $_POST['password'];
                $rol_usuario = $_POST['rol_usuario'];

                $sql = "INSERT INTO usuario (nombre_usuario, correo_electronico, password, rol_usuario) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                if ($stmt === false) {
                    die('Error al preparar la consulta: ' . $conn->error);
                }
                $stmt->bind_param('ssss', $nombre_usuario, $correo_electronico, $password, $rol_usuario);
                if ($stmt->execute()) {
                    $_SESSION['mensaje'] = 'Usuario agregado correctamente.';
                } else {
                    $_SESSION['mensaje'] = 'Error al agregar el usuario.';
                }
                $stmt->close();
                header('Location: admin_usuarios.php');
                exit();
                break;

            case 'delete':
                // Eliminar un usuario
                $id_usuario = $_POST['id_usuario'];

                $conn->begin_transaction();
                try {
                    // Eliminar notificaciones del usuario
                    $sql = "DELETE FROM notificaciones WHERE id_usuario = ?";
                    $stmt = $conn->prepare($sql);
                    if ($stmt === false) {
                        throw new Exception('Error al preparar la consulta: ' . $conn->error);
                    }
                    $stmt->bind_param('i', $id_usuario);
                    $stmt->execute();
                    $stmt->close();

                    // Eliminar el usuario
                    $sql = "DELETE FROM usuario WHERE id_usuario = ?";
                    $stmt = $conn->prepare($sql);
                    if ($stmt === false) {
                        throw new Exception('Error al preparar la consulta: ' . $conn->error);
                    }
                    $stmt->bind_param('i', $id_usuario);
                    $stmt->execute();
                    $stmt->close();

                    // Confirmar la transacción
                    $conn->commit();
                    $_SESSION['mensaje'] = "Usuario eliminado correctamente.";
                } catch (Exception $e) {
                    // Revertir la transacción en caso de error
                    $conn->rollback();
                    $_SESSION['mensaje'] = "Error al eliminar el usuario: " . $e->getMessage();
                }
                header('Location: admin_usuarios.php');
                exit();
                break;
        }
    }
}

// Obtener todos los usuarios
$sql = "SELECT * FROM usuario";
$result = $conn->query($sql);

// Obtener los datos de un usuario específico para editar
$usuario_a_editar = null;
if (isset($_GET['id_usuario'])) {
    $id_usuario = $_GET['id_usuario'];
    $sql = "SELECT * FROM usuario WHERE id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id_usuario);
    $stmt->execute();
    $result_edit = $stmt->get_result();
    $usuario_a_editar = $result_edit->fetch_assoc();
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos.css" media="screen">
    <link href='https://fonts.googleapis.com/css?family=Aclonica' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Averia Libre' rel='stylesheet'>
    <title>Administrar Usuarios</title>
    <style>
        /* Estilos adicionales */
        .body-admin-usuarios {
            font-family: 'Averia Libre', sans-serif; /* Fuente para textos pequeños */
            background-color: #fff0f5; /* Fondo rosa claro */
            color: #333;
            margin: 0;
            padding: 0;
        }
        .button-agregar-usuario, .button-regresar {
            padding: 10px 20px;
            background-color: #8b568c;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px;
            margin-bottom: 20px;
            display: inline-block;
        }
        .button-agregar-usuario:hover, .button-regresar:hover {
            background-color: #a67c9b;
        }
        .container-admin-usuarios {
            max-width: 1500px;
            margin: 150px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .h2-admin-usuarios {
            text-align: center;
            color: #000000;
            font-family: 'Aclonica', sans-serif; /* Fuente para h2 y h3 */
        }
        .h3-admin-usuarios {
            color: #000000;
            font-family: 'Aclonica', sans-serif; /* Fuente para h2 y h3 */
        }

        .form-admin-usuarios {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
        }
        .input-admin-usuarios {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .button-admin-usuarios {
            padding: 10px;
            background-color: #523750;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s ease;
            text-align: center;
            text-decoration: none;
        }
        .button-admin-usuarios:hover {
            background-color: #231d25;
        }
        .table-admin-usuarios {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .th-admin-usuarios, .td-admin-usuarios {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .th-admin-usuarios {
            background-color: #523750;
            color: #fff;
        }
        .action-buttons-admin-usuarios {
            display: flex;
            gap: 10px;
        }
        .button-edit-admin-usuarios, .button-delete-admin-usuarios {
            padding: 5px 10px;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
            text-align: center;
        }
        .button-edit-admin-usuarios {
            background-color: #a67c9b;
        }
        .button-edit-admin-usuarios:hover {
            background-color: #523750;
        }
        .button-delete-admin-usuarios {
            background-color: #000000;
        }
        .button-delete-admin-usuarios:hover {
            background-color: #000000;
        }
        .mensaje-admin-usuarios {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        .mensaje-exito-admin-usuarios {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .mensaje-error-admin-usuarios {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body class="body-admin-usuarios">
    <header>
        <?php include 'navbar.php'; ?>
    </header>

    <div class="container-admin-usuarios">
        <?php if (!isset($usuario_a_editar) && !isset($_GET['action'])) { ?>
            <!-- Solo mostrar el botón de agregar cuando no se esté en el apartado de agregar o editar -->
            <h2 class="h2-admin-usuarios">Administrar usuarios</h2>
            <a href="admin_usuarios.php?action=add_user" class="button-agregar-usuario">Agregar Nuevo Usuario</a>
        <?php } ?>

        <?php if (isset($_SESSION['mensaje'])) { ?>
            <div class="mensaje-admin-usuarios <?php echo strpos($_SESSION['mensaje'], 'correctamente') !== false ? 'mensaje-exito-admin-usuarios' : 'mensaje-error-admin-usuarios'; ?>">
                <?php echo $_SESSION['mensaje']; ?>
                <?php unset($_SESSION['mensaje']); ?>
            </div>
        <?php } ?>

        <?php if (isset($_GET['action']) && $_GET['action'] == 'add_user') { ?>
            <!-- Formulario para agregar usuario -->
            <h2 class="h2-admin-usuarios">Agregar Nuevo Usuario</h2>
            <form class="form-admin-usuarios" action="admin_usuarios.php" method="post">
                <input type="hidden" name="action" value="add">
                <input class="input-admin-usuarios" type="text" name="nombre_usuario" placeholder="Nombre completo" required>
                <input class="input-admin-usuarios" type="email" name="correo_electronico" placeholder="Correo electrónico" required>
                <input class="input-admin-usuarios" type="password" name="password" placeholder="Contraseña" required>
                <select class="input-admin-usuarios" name="rol_usuario" required>
                    <option value="cliente">Cliente</option>
                    <option value="administrador">Administrador</option>
                </select>
                
                <button class="button-admin-usuarios" type="submit">Agregar Usuario</button>
            </form>
            <a href="admin_usuarios.php" class="button-regresar">Regresar a la Lista de Usuarios</a>
        <?php } elseif ($usuario_a_editar) { ?>
            <!-- Formulario para editar usuario -->
            <h2 class="h2-admin-usuarios">Editar Usuario</h2>
            <form class="form-admin-usuarios" action="admin_usuarios.php" method="post">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id_usuario" value="<?php echo htmlspecialchars($usuario_a_editar['id_usuario']); ?>">
                <input class="input-admin-usuarios" type="text" name="nombre_usuario" placeholder="Nombre completo" value="<?php echo htmlspecialchars($usuario_a_editar['nombre_usuario']); ?>" required>
                <input class="input-admin-usuarios" type="email" name="correo_electronico" placeholder="Correo electrónico" value="<?php echo htmlspecialchars($usuario_a_editar['correo_electronico']); ?>" required>
                <input class="input-admin-usuarios" type="password" name="password" placeholder="Contraseña" value="<?php echo htmlspecialchars($usuario_a_editar['password']); ?>" required>
                <select class="input-admin-usuarios" name="rol_usuario" required>
                    <option value="cliente" <?php echo $usuario_a_editar['rol_usuario'] == 'cliente' ? 'selected' : ''; ?>>Cliente</option>
                    <option value="administrador" <?php echo $usuario_a_editar['rol_usuario'] == 'administrador' ? 'selected' : ''; ?>>Administrador</option>
                </select>
                <button class="button-admin-usuarios" type="submit">Actualizar Usuario</button>
            </form>
            <a href="admin_usuarios.php" class="button-regresar">Regresar a la Lista de Usuarios</a>
        <?php } else { ?>
            <!-- Lista de usuarios -->
            <h3 class="h3-admin-usuarios">Lista de Usuarios</h3>
            <table class="table-admin-usuarios">
                <thead>
                    <tr>
                        <th class="th-admin-usuarios">ID</th>
                        <th class="th-admin-usuarios">Nombre</th>
                        <th class="th-admin-usuarios">Correo</th>
                        <th class="th-admin-usuarios">Rol</th>
                        <th class="th-admin-usuarios">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td class="td-admin-usuarios"><?php echo htmlspecialchars($row['id_usuario']); ?></td>
                        <td class="td-admin-usuarios"><?php echo htmlspecialchars($row['nombre_usuario']); ?></td>
                        <td class="td-admin-usuarios"><?php echo htmlspecialchars($row['correo_electronico']); ?></td>
                        <td class="td-admin-usuarios"><?php echo htmlspecialchars($row['rol_usuario']); ?></td>
                        <td class="td-admin-usuarios action-buttons-admin-usuarios">
                            <a href="admin_usuarios.php?id_usuario=<?php echo $row['id_usuario']; ?>" class="button-edit-admin-usuarios">Editar</a>
                            <form action="admin_usuarios.php" method="post" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id_usuario" value="<?php echo $row['id_usuario']; ?>">
                                <button class="button-delete-admin-usuarios" type="submit">Eliminar</button>
                            </form>
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
