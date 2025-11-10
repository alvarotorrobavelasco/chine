<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $rol = $_POST['rol'];

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (nombre, email, password_hash, rol)
            VALUES ('$nombre', '$email', '$password_hash', '$rol')";

    if ($conexion->query($sql) === TRUE) {
        // ✅ Redirigir a la misma página con un mensaje (evita repetir el envío)
        header("Location: registro.php?ok=1");
        exit();
    } else {
        echo "⚠️ Error: " . $conexion->error;
    }
}

// Mostrar mensaje si se registró correctamente
if (isset($_GET['ok']) && $_GET['ok'] == 1) {
    echo "✅ Usuario registrado correctamente";
}
?>

<form method="POST" action="">
    <h2>Registro de Usuario</h2>

    <label>Nombre:</label><br>
    <input type="text" name="nombre" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Contraseña:</label><br>
    <input type="password" name="password" required><br><br>

    <label>Rol:</label><br>
    <select name="rol">
        <option value="usuario">Usuario</option>
        <option value="tecnico">Técnico</option>
        <option value="admin">Administrador</option>
    </select><br><br>

    <button type="submit">Registrar</button>
</form>
