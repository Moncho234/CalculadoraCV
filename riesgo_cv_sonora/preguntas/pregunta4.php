<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['respuesta'] == "si") {
        header("Location: datos_colesterol.php"); // Colesterol
    } else {
        header("Location: datos_imc.php"); // Sin colesterol (IMC)
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pregunta 4</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
<div class="container">
    <h1>¿Conoce sus niveles de colesterol total?</h1>
    <form method="post">
        <button name="respuesta" value="si">Sí</button>
        <button name="respuesta" value="no">No</button>
    </form>
</div>
</body>
</html>
