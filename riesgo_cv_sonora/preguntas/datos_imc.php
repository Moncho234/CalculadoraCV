<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Datos con IMC</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
<div class="container">
    <h1>Ingrese sus datos</h1>

    <?php if (isset($_SESSION['error'])): ?>
        <div style="background: #f44336; color: white; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
            <?= $_SESSION['error'] ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form method="post" action="../includes/calculadora_imc.php">
        <label>Sexo:
            <select name="sexo" required>
                <option value="masculino">Masculino</option>
                <option value="femenino">Femenino</option>
            </select>
        </label>
        <label>Edad:
            <select name="edad" required>
                <option value="40-44">40-44</option>
                <option value="45-49">45-49</option>
                <option value="50-54">50-54</option>
                <option value="55-59">55-59</option>
                <option value="60-64">60-64</option>
                <option value="65-69">65-69</option>
                <option value="70-74">70-74</option>
            </select>
        </label>
        <label>¿Fuma?
            <select name="fumador" required>
                <option value="true">Sí</option>
                <option value="false">No</option>
            </select>
        </label>
        <label>Peso (kg):
            <input type="number" name="peso" min="50" max="230" required>
        </label>
        <label>Altura (cm):
            <input type="number" name="altura" min="140" max="230" required>
        </label>
        <label>Presión arterial sistólica (mmHg):
            <input type="number" name="pas" min="90" max="200" required>
        </label>
        <button type="submit">Calcular riesgo</button>
    </form>
</div>
</body>
</html>
