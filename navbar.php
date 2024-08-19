<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../proyecto/estilos.css" media="screen">
    <title>Salón de Belleza</title>
    <style>
        .usuario-sesion {
            color: white; /* Cambiar el color del texto a blanco */
        }
    </style>
</head>
<body>
<header id="inicio">
    <div class="barra-navegacion">
        <div class="logo">
            <img src="../proyecto/imagenes/velvetlogoo.png" alt="Velvet's Studio Logo" height="90" width="100">
        </div>
        <div class="boton-alternar" onclick="toggleMenu()">
            <div></div>
            <div></div>
            <div></div>
        </div>
        <ul class="menu">
            <?php
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            if (isset($_SESSION['rol_usuario'])) {
                if ($_SESSION['rol_usuario'] == 'administrador') {
                    // Menú para administrador
                    echo '<li><a href="admin.php#inicio">Inicio</a></li>';
                    echo '<li><a href="admin_usuarios.php">Administrar Usuarios</a></li>';
                    echo '<li><a href="admin_servicios.php">Administrar Servicios</a></li>';
                    echo '<li><a href="administrar_citas.php">Administrar Citas</a></li>';
                    echo '<li><a href="logout.php">Cerrar Sesión</a></li>';
                } elseif ($_SESSION['rol_usuario'] == 'cliente') {
                    // Menú para cliente
                    echo '<li><a href="cliente.php#inicio">Inicio</a></li>';
                    echo '<li><a href="cliente.php#servicios">Servicios</a></li>';
                    echo '<li><a href="cliente.php#somos">¿Quiénes somos?</a></li>';
                    echo '<li><a href="cliente.php#ubicacion">Ubicación</a></li>';
                    echo '<li><a href="cliente.php#contacto">Contacto</a></li>';
                    echo '<li><a href="cliente.php#equipo">Nuestro Equipo</a></li>';
                    echo '<li><a href="catalogo.php">Catálogo</a></li>';
                    echo '<li><a href="carrito.php">Carrito</a></li>';
                    echo '<li><a href="mis_citas.php">Mis Citas</a></li>';
                    echo '<li><a href="notificaciones.php">Notificaciones</a></li>';
                    echo '<li><a href="logout.php">Cerrar Sesión</a></li>';
                }
            } else {
                // Menú para usuarios sin sesión
                echo '<li><a href="index.php#inicio">Inicio</a></li>';
                echo '<li><a href="index.php#servicios">Servicios</a></li>';
                echo '<li><a href="index.php#somos">¿Quiénes somos?</a></li>';
                echo '<li><a href="index.php#ubicacion">Ubicación</a></li>';
                echo '<li><a href="index.php#contacto">Contacto</a></li>';
                echo '<li><a href="index.php#equipo">Nuestro Equipo</a></li>';
                echo '<li><a href="acceso.php">Acceso</a></li>';
            }
            ?>
        </ul>
        <div class="usuario-sesion">
            <?php
            if (isset($_SESSION['nombre_usuario'])) {
                if ($_SESSION['rol_usuario'] == 'administrador') {
                    echo "Administrador: " . $_SESSION['nombre_usuario'];
                } elseif ($_SESSION['rol_usuario'] == 'cliente') {
                    echo "Usuario: " . $_SESSION['nombre_usuario'];
                }
            } else {
                echo "No has iniciado sesión.";
            }
            ?>
        </div>
    </div>
</header>
<script>
function toggleMenu() {
    const menu = document.querySelector('.barra-navegacion .menu');
    menu.classList.toggle('active');
}
</script>
</body>
</html>
