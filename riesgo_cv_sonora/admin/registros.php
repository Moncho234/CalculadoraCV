<?php
session_start();

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit();
}

include("../includes/db.php");

$sql = "SELECT * FROM resultados ORDER BY fecha DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registros Guardados</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>

    <h1 style="text-align: center; margin-top: 2rem;">Registros Guardados</h1>

    <div style="text-align: center; margin-bottom: 2rem;">
    <a href="exportar_excel.php" class="boton-secundario">Descargar en Excel</a>
    <a href="graficas.php" class="boton-secundario">Ver gráficas</a>
    </div>

    <?php if ($result->num_rows > 0): ?>
        <div class="tabla-wrapper">
            <table class="tabla-registros">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Sexo</th>
                        <th>Edad</th>
                        <th>Peso</th>
                        <th>Altura</th>
                        <th>IMC</th>
                        <th>PAS</th>
                        <th>Colesterol</th>
                        <th>Fumador</th>
                        <th>Diabetes</th>
                        <th>Riesgo</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <?php
                            $peso = ($row['peso'] == 0) ? 'N/A' : htmlspecialchars($row['peso']);
                            $altura = ($row['altura'] == 0) ? 'N/A' : htmlspecialchars($row['altura']);
                            $imc = ($row['imc'] == 0) ? 'N/A' : htmlspecialchars($row['imc']);
                            $colesterol = ($row['colesterol'] == 0) ? 'N/A' : htmlspecialchars($row['colesterol']);
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nombre']) ?></td>
                            <td><?= htmlspecialchars($row['apellido']) ?></td>
                            <td><?= htmlspecialchars($row['sexo']) ?></td>
                            <td><?= htmlspecialchars($row['edad']) ?></td>
                            <td><?= $peso ?></td>
                            <td><?= $altura ?></td>
                            <td><?= $imc ?></td>
                            <td><?= htmlspecialchars($row['pas']) ?></td>
                            <td><?= $colesterol ?></td>
                            <td><?= htmlspecialchars($row['fumador']) ?></td>
                            <td><?= htmlspecialchars($row['diabetes']) ?></td>
                            <td><?= htmlspecialchars($row['riesgo']) ?></td>
                            <td><?= htmlspecialchars($row['fecha']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p style="text-align:center;">No hay registros disponibles.</p>
    <?php endif; ?>

    <div style="text-align: center; margin: 3rem 0;">
    <a href="../logout.php" class="boton-secundario">Cerrar sesión</a>
    </div>

</body>
</html>

<?php $conn->close(); ?>
