<?php
// Datos de conexión (ajusta según tu XAMPP)
$servidor = "localhost";
$usuario = "root";
$contrasena = "";
$basedatos = "chine";

// Intentamos conectar
$conexion = new mysqli($servidor, $usuario, $contrasena, $basedatos);

// Comprobamos si hay error
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Si todo va bien:
echo "✅ Conexión exitosa a la base de datos";
?>
