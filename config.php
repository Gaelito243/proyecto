<?php
$servername = "localhost";
$username = "admin_velvet";
$password = "velvet";
$dbname = "velvet";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
