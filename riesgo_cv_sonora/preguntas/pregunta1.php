<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['respuesta'] == "si") {
        $_SESSION['riesgo'] = "Muy alto";
        header("Location: ../resultados/resultado.php");
    } else {
        header("Location: pregunta2.php");
    }
    exit();
}
?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><title>Pregunta 1</title><link rel="stylesheet" href="../css/styles.css"></head>
<body><div class="container"><h1>¿Tiene historia de enfermedad cardiovascular?</h1>
<form method="post">
    <button name="respuesta" value="si">Sí</button>
    <button name="respuesta" value="no">No</button>
</form></div></body></html>
