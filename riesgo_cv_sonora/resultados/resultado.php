<?php
session_start();
$nombre = $_SESSION['nombre'] ?? 'Paciente';
$apellido = $_SESSION['apellido'] ?? '';
$riesgo = $_SESSION['riesgo'] ?? 'Pendiente';
$porcentaje = $_SESSION['riesgo_valor'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultado</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
<div class="container">
    <h1>Resultado de Riesgo Cardiovascular</h1>
    <p><strong>Nombre:</strong> <?php echo htmlspecialchars($nombre . ' ' . $apellido); ?></p>
    <p><strong>Nivel de riesgo:</strong> <?php echo htmlspecialchars($riesgo); ?>
    <?php if ($porcentaje !== null): ?>
        (<?php echo $porcentaje; ?>%)
    <?php endif; ?></p>
    <a href="../index.php">Volver al inicio</a>
</div>
</body>
</html>
