<?php
session_start();

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $clave = $_POST['clave'];

    if ($usuario === 'admin' && $clave === '1234') {
        $_SESSION['admin'] = true;
        header("Location: registros.php");
        exit();
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso Admin</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Acceso Administrador</h1>
        <?php if ($error): ?>
            <p style="color: red;"><?= $error ?></p>
        <?php endif; ?>
        <form method="post">
            <label>Usuario:
                <input type="text" name="usuario" required>
            </label>
            <label>Contraseña:
                <input type="password" name="clave" required>
            </label>
            <button type="submit">Ingresar</button>
        </form>
    </div>
</body>
</html>
