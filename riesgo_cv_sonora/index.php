<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['nombre'] = $_POST['nombre'];
    $_SESSION['apellido'] = $_POST['apellido'];
    header("Location: preguntas/pregunta1.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<div class="container">
    <h1>Calculadora de Riesgo Cardiovascular</h1>
    <h1>Bienvenido</h1>
    <form method="post">
        <label>Nombre:</label>
        <input type="text" name="nombre" required>
        <label>Apellido:</label>
        <input type="text" name="apellido" required>
        <button type="submit">Comenzar</button>
    </form>
</div>

<div class="admin-access">
    <form action="admin/login.php" method="get">
        <button type="submit" class="admin-button">Ver registros</button>
    </form>
</div>

</body>
</html>
