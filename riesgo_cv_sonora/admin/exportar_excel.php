<?php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit();
}

include("../includes/db.php");

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=registros.xls");

echo "<table border='1'>";
echo "<tr>
        <th>Nombre</th><th>Apellido</th><th>Sexo</th><th>Edad</th>
        <th>Peso</th><th>Altura</th><th>IMC</th><th>PAS</th>
        <th>Colesterol</th><th>Fumador</th><th>Diabetes</th>
        <th>Riesgo</th><th>Fecha</th>
      </tr>";

$sql = "SELECT * FROM resultados ORDER BY fecha DESC";
$result = $conn->query($sql);

function mostrarDato($valor) {
    return ($valor == 0 || $valor === null || $valor === '') ? 'N/A' : htmlspecialchars($valor);
}

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . mostrarDato($row['nombre']) . "</td>";
    echo "<td>" . mostrarDato($row['apellido']) . "</td>";
    echo "<td>" . mostrarDato($row['sexo']) . "</td>";
    echo "<td>" . mostrarDato($row['edad']) . "</td>";
    echo "<td>" . mostrarDato($row['peso']) . "</td>";
    echo "<td>" . mostrarDato($row['altura']) . "</td>";
    echo "<td>" . mostrarDato($row['imc']) . "</td>";
    echo "<td>" . mostrarDato($row['pas']) . "</td>";
    echo "<td>" . mostrarDato($row['colesterol']) . "</td>";
    echo "<td>" . mostrarDato($row['fumador']) . "</td>";
    echo "<td>" . mostrarDato($row['diabetes']) . "</td>";
    echo "<td>" . mostrarDato($row['riesgo']) . "</td>";
    echo "<td>" . mostrarDato($row['fecha']) . "</td>";
    echo "</tr>";
}

echo "</table>";
$conn->close();
?>
