<?php
session_start();

$sexo = $_POST['sexo']; 
$edad = $_POST['edad'];
$fumador = $_POST['fumador'] === 'true';
$diabetes = $_POST['diabetes'] === 'true';
$pasIngresada = intval($_POST['pas']);
$peso = $_POST['peso'];
$altura = $_POST['altura'];

// Calcular IMC y estimar colesterol
$imc = $peso / (($altura / 100) ** 2);
$colesterolEstimado = ($imc * 3.8) + 115;
$colesterolEstimadoRedondeado = min(round($colesterolEstimado / 10) * 10, 270);

// Normalizar PAS
if ($pasIngresada < 115) {
    $pas = "90";
} elseif ($pasIngresada < 125) {
    $pas = "120";
} elseif ($pasIngresada < 135) {
    $pas = "130";
} elseif ($pasIngresada < 145) {
    $pas = "140";
} elseif ($pasIngresada < 155) {
    $pas = "150";
} elseif ($pasIngresada < 165) {
    $pas = "160";
} elseif ($pasIngresada < 175) {
    $pas = "170";
} else {
    $pas = "180";
}

// Buscar en el archivo JSON correspondiente al sexo y edad
$jsonPath = "../data/" . $sexo . "_" . str_replace("-", "_", $edad) . ".json";
if (!file_exists($jsonPath)) {
    $_SESSION['riesgo'] = "Datos no disponibles";
    header("Location: ../resultados/resultado_imc.php");
    exit();
}

$data = json_decode(file_get_contents($jsonPath), true);
$casos = $data["casos"];

$riesgo = "No disponible";

// Buscar el riesgo correcto
foreach ($casos as $caso) {
    if ($caso["diabetes"] === $diabetes && $caso["fumador"] === $fumador) {
        if (isset($caso["resultados"][$pas][$colesterolEstimadoRedondeado])) {
            $riesgo = $caso["resultados"][$pas][$colesterolEstimadoRedondeado];
        }
        break;
    }
}

// Si no se encuentra riesgo, asignar mensaje
if ($riesgo === "No disponible") {
    $riesgo = "Faltan datos para realizar el cálculo.";
}

// Guardar en sesión todos los datos necesarios ---
$_SESSION['riesgo'] = $riesgo;
$_SESSION['edad'] = $edad;
$_SESSION['colesterol'] = $colesterolEstimadoRedondeado;
$_SESSION['pas'] = $pasIngresada;
$_SESSION['sexo'] = $sexo;
$_SESSION['imc'] = round($imc, 1);
$_SESSION['peso'] = $peso;
$_SESSION['altura'] = $altura;
$_SESSION['fumador'] = $fumador ? 'Sí' : 'No';
$_SESSION['diabetes'] = $diabetes ? 'Sí' : 'No';

// Guardar en la base de datos
include("../includes/db.php");

$stmt = $conn->prepare("INSERT INTO resultados 
    (nombre, apellido, sexo, edad, peso, altura, imc, pas, colesterol, fumador, diabetes, riesgo) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param(
    "ssssssssssss",
    $_SESSION['nombre'],
    $_SESSION['apellido'],
    $_SESSION['sexo'],
    $_SESSION['edad'],
    $_SESSION['peso'],
    $_SESSION['altura'],
    $_SESSION['imc'],
    $_SESSION['pas'],
    $_SESSION['colesterol'],
    $_SESSION['fumador'],
    $_SESSION['diabetes'],
    $_SESSION['riesgo']
);
$stmt->execute();
$stmt->close();
$conn->close();

header("Location: ../resultados/resultado_imc.php");
exit();
