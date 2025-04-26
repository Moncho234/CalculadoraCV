<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultado del Riesgo Cardiovascular</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
<div class="container">
    <h1>Resultado del Riesgo Cardiovascular</h1>
    <p><strong>Sexo:</strong> <?= ucfirst($_SESSION['sexo']) ?></p>
    <p><strong>Edad:</strong> <?= $_SESSION['edad'] ?> años</p>
    <p><strong>Riesgo estimado a 10 años:</strong> <?= $_SESSION['riesgo'] ?></p>
    <p><strong>IMC calculado:</strong> <?= $_SESSION['imc'] ?></p>
    <p><strong>Peso:</strong> <?= $_SESSION['peso'] ?> kg</p>
    <p><strong>Altura:</strong> <?= $_SESSION['altura'] ?> cm</p>
    <p><strong>Presión sistólica:</strong> <?= $_SESSION['pas'] ?> mmHg</p>
    <p><strong>Fumador:</strong> <?= $_SESSION['fumador'] ?></p>

    <?php
    // Obtener riesgo numérico
    $riesgoNum = floatval(str_replace('%', '', $_SESSION['riesgo']));
    $nivel = "";
    if ($riesgoNum <= 4) {
        $nivel = "Bajo";
        $nivelClase = "bajo";
    } elseif ($riesgoNum <= 9) {
        $nivel = "Moderado";
        $nivelClase = "moderado";
    } elseif ($riesgoNum <= 19) {
        $nivel = "Alto";
        $nivelClase = "alto";
    } elseif ($riesgoNum <= 29) {
        $nivel = "Muy alto";
        $nivelClase = "muy-alto";
    } else {
        $nivel = "Crítico";
        $nivelClase = "critico";
    }
    ?>

    <div class="riesgo-grafico">
        <div class="torre-riesgo">
            <div class="nivel bajo <?= $nivelClase === 'bajo' ? 'activo' : '' ?>"></div>
            <div class="nivel moderado <?= $nivelClase === 'moderado' ? 'activo' : '' ?>"></div>
            <div class="nivel alto <?= $nivelClase === 'alto' ? 'activo' : '' ?>"></div>
            <div class="nivel muy-alto <?= $nivelClase === 'muy-alto' ? 'activo' : '' ?>"></div>
            <div class="nivel critico <?= $nivelClase === 'critico' ? 'activo' : '' ?>"></div>
        </div>
        <p class="nivel-texto">Nivel de riesgo: <strong><?= $nivel ?></strong></p>
    </div>

    <a href="../index.php">Volver al inicio</a>
</div>
</body>
</html>
