<?php
require 'conexion.php';

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recogemos los datos del formulario
    $nombre   = trim($_POST['nombre'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $rol      = $_POST['rol'] ?? 'usuario';

    // Comprobamos que no estén vacíos
    if ($nombre === "" || $email === "" || $password === "") {
        $mensaje = "Por favor, rellena todos los campos.";
    } else {
        // Comprobamos si el email ya existe (para quedar pro)
        $sqlExiste = "SELECT id FROM usuarios WHERE email = ?";
        $stmtExiste = $conexion->prepare($sqlExiste);
        $stmtExiste->bind_param("s", $email);
        $stmtExiste->execute();
        $stmtExiste->store_result();

        if ($stmtExiste->num_rows > 0) {
            $mensaje = "Ese correo ya está registrado.";
        } else {
            // Encriptamos la contraseña
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // Insertamos con prepared statement (seguro y fácil)
            $sql = "INSERT INTO usuarios (nombre, email, password_hash, rol)
                    VALUES (?, ?, ?, ?)";

            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("ssss", $nombre, $email, $password_hash, $rol);

            if ($stmt->execute()) {
                $mensaje = "Usuario registrado correctamente.";
            } else {
                $mensaje = "Error al registrar el usuario.";
            }

            $stmt->close();
        }

        $stmtExiste->close();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de usuario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f3f3;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .contenedor {
            background: #fff;
            padding: 20px 25px;
            border-radius: 6px;
            box-shadow: 0 0 8px rgba(0,0,0,0.15);
            width: 320px;
        }
        h2 {
            margin-top: 0;
            text-align: center;
            font-size: 20px;
        }
        label {
            font-size: 14px;
        }
        input, select, button {
            width: 100%;
            padding: 8px;
            margin: 6px 0 12px;
            font-size: 14px;
        }
        button {
            cursor: pointer;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
        }
        .mensaje {
            font-size: 14px;
            margin-bottom: 10px;
            color: #333;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="contenedor">
    <h2>Registro</h2>

    <?php if ($mensaje !== ""): ?>
        <div class="mensaje">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>
    <?php endif; ?>

    <form method="post" action="">
        <label>Nombre</label>
        <input type="text" name="nombre" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Contraseña</label>
        <input type="password" name="password" required>

        <label>Rol</label>
        <select name="rol">
            <option value="usuario">Usuario</option>
            <option value="tecnico">Técnico</option>
            <option value="admin">Administrador</option>
        </select>

        <button type="submit">Registrar</button>
    </form>
</div>
</body>
</html>
