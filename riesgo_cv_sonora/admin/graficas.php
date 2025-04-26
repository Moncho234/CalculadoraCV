<?php
session_start();

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit();
}

include("../includes/db.php");

// Inicializar conteos
$grupos_edad = ['40-44', '45-49', '50-54', '55-59', '60-64', '65-69', '70-74'];
$niveles = ['bajo', 'moderado', 'alto', 'muy_alto', 'critico'];

$data = [
    'masculino' => [],
    'femenino' => []
];

foreach (['masculino', 'femenino'] as $sexo) {
    foreach ($grupos_edad as $edad) {
        $data[$sexo][$edad] = array_fill_keys($niveles, 0);
    }
}

// Obtener datos y agrupar
$sql = "SELECT sexo, edad, riesgo FROM resultados";
$result = $conn->query($sql);

function clasificarRiesgo($riesgo) {
    $riesgo = intval(str_replace('%', '', $riesgo));
    if ($riesgo <= 4) return 'bajo';
    elseif ($riesgo <= 9) return 'moderado';
    elseif ($riesgo <= 19) return 'alto';
    elseif ($riesgo <= 29) return 'muy_alto';
    else return 'critico';
}

while ($row = $result->fetch_assoc()) {
    $sexo = strtolower($row['sexo']);
    $edad = $row['edad'];
    $riesgo = $row['riesgo'];

    if (isset($data[$sexo][$edad]) && $riesgo !== 'No disponible' && $riesgo !== 'N/A') {
        $nivel = clasificarRiesgo($riesgo);
        $data[$sexo][$edad][$nivel]++;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gráficas de Riesgo</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        .chart-container {
            width: 90%;
            max-width: 1000px;
            margin: 2rem auto;
        }
        h2 {
            text-align: center;
            margin-top: 2rem;
        }
    </style>
</head>
<body>

    <h1 style="text-align: center;">Distribución de Riesgo por Edad y Sexo</h1>

    <div class="chart-container">
        <h2>Masculino</h2>
        <canvas id="chartMasculino"></canvas>
    </div>

    <div class="chart-container">
        <h2>Femenino</h2>
        <canvas id="chartFemenino"></canvas>
    </div>

<script>
const labels = <?= json_encode($grupos_edad) ?>;
const colores = {
    bajo: "#4CAF50",
    moderado: "#FFEB3B",
    alto: "#FF9800",
    muy_alto: "#F44336",
    critico: "#B71C1C"
};

function crearDataset(datos, nivel) {
    return {
        label: nivel.replace('_', ' ').toUpperCase(),
        data: datos.map(edad => edad[nivel]),
        backgroundColor: colores[nivel],
        stack: 'stack'
    };
}

const dataMasculino = <?= json_encode(array_values($data['masculino'])) ?>;
const dataFemenino = <?= json_encode(array_values($data['femenino'])) ?>;

const niveles = ['bajo', 'moderado', 'alto', 'muy_alto', 'critico'];

function renderChart(id, datos) {
    new Chart(document.getElementById(id), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: niveles.map(nivel => crearDataset(datos, nivel))
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: false
                },
                legend: {
                    position: 'top'
                },
            },
            scales: {
                x: { stacked: true },
                y: { stacked: true, beginAtZero: true }
            }
        }
    });
}

renderChart("chartMasculino", dataMasculino);
renderChart("chartFemenino", dataFemenino);
</script>

<div style="text-align: center; margin: 3rem 0;">
    <a href="../logout.php" class="boton-secundario">Cerrar sesión</a>
</div>

</body>
</html>
