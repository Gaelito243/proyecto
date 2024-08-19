<?php
session_start();
include 'config.php'; // Asegúrate de que la ruta al archivo config.php es correcta

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Manejar la adición al carrito
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_servicio'])) {
    $id_servicio = $_POST['id_servicio'];

    // Verificar si el carrito existe en la sesión
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }

    // Agregar el servicio al carrito
    $_SESSION['carrito'][] = ['id_servicio' => $id_servicio];

    header('Location: catalogo.php');
    exit();
}

// Obtener todos los servicios
$sql = "SELECT * FROM servicios";
$result_servicios = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos.css" media="screen">
    <link href="https://fonts.googleapis.com/css2?family=Averia+Libre:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Aclonica&display=swap" rel="stylesheet">
    <title>Catálogo de Servicios</title>
    <style>
    .body-catalogo {
        font-family: 'Averia Libre', sans-serif; /* Fuente para textos pequeños */
        margin: 0;
        padding: 0;
        background-color: #fff0f5; /* Fondo rosa claro */
        color: #333;
    }
    .container-catalogo {
        max-width: 1000px;
        margin: 100px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .h2-catalogo {
        text-align: center;
        color: #523750;
        font-family: 'Aclonica', sans-serif; /* Fuente para h2 */
    }
    .grid-catalogo {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
    }
    .card-catalogo {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 10px;
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .card-catalogo img {
        width: 100%;
        height: 300px; /* Aumentar la altura de las imágenes */
        object-fit: cover; /* Ajustar la imagen para que cubra el espacio */
        border-radius: 8px;
    }
    .card-catalogo h3 {
        color: #523750;
        font-family: 'Aclonica', sans-serif; /* Fuente para h3 */
    }
    .card-catalogo p {
        color: #231d25;
        margin-bottom: 20px; /* Añadir espacio extra para garantizar la alineación */
    }
    .button-catalogo {
        background-color: #523750;
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 10px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        font-family: 'Averia Libre', sans-serif; /* Fuente para botones */
        align-self: center; /* Centrar el botón horizontalmente */
    }
    .button-catalogo:hover {
        background-color: #231d25;
    }
    </style>
</head>
<body class="body-catalogo">
<header>
    <?php include 'navbar.php'; // Incluir la barra de navegación ?>
</header>
<div class="container-catalogo">
    <h2 class="h2-catalogo">Catálogo de Servicios</h2>
    <div class="grid-catalogo">
        <?php while ($row = $result_servicios->fetch_assoc()) { ?>
        <div class="card-catalogo">
            <img src="<?php echo htmlspecialchars($row['imagen']); ?>" alt="<?php echo htmlspecialchars($row['tipo_servicio']); ?>">
            <h3><?php echo htmlspecialchars($row['tipo_servicio']); ?></h3>
            <p>$<?php echo htmlspecialchars($row['costo_servicio']); ?></p>
            <form action="catalogo.php" method="post">
                <input type="hidden" name="id_servicio" value="<?php echo $row['id_servicio']; ?>">
                <button class="button-catalogo" type="submit">Agregar al Carrito</button>
            </form>
        </div>
        <?php } ?>
    </div>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
