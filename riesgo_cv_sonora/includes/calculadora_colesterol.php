<?php 
session_start(); // Inicia sesión para usar variables de sesión

// Recolección de datos del formulario
$sexo = $_POST['sexo'];
$edad = $_POST['edad'];
$diabetes = $_POST['diabetes'] === 'true';
$fumador = $_POST['fumador'] === 'true';
$pasIngresada = intval($_POST['pas']);
$colesterolIngresado = intval($_POST['colesterol']);

// Normalizar la presión arterial sistólica (PAS) en rangos estándar
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

// Normalizar el colesterol en rangos de 10 (150 a 270)
if ($colesterolIngresado < 145) {
    $colesterol = "150";
} elseif ($colesterolIngresado >= 275) {
    $colesterol = "270";
} else {
    $colesterolRedondeado = round($colesterolIngresado / 10) * 10;
    if ($colesterolRedondeado < 140) $colesterolRedondeado = 150;
    elseif ($colesterolRedondeado > 270) $colesterolRedondeado = 270;
    $colesterol = strval($colesterolRedondeado);
}

// Construcción de la ruta al archivo JSON según sexo y edad
$jsonPath = "../data/" . strtolower($sexo) . "_" . str_replace("-", "_", $edad) . ".json";

// Verificar existencia del archivo JSON correspondiente
if (!file_exists($jsonPath)) {
    $_SESSION['riesgo'] = "Datos no disponibles";
    header("Location: ../resultados/resultado_colesterol.php");
    exit();
}

// Cargar los datos JSON
$data = json_decode(file_get_contents($jsonPath), true);
$casos = $data["casos"];

$riesgo = "No disponible";

// Buscar el caso correspondiente en los datos según diabetes y tabaquismo
foreach ($casos as $caso) {
    if ($caso["diabetes"] === $diabetes && $caso["fumador"] === $fumador) {

        // Caso especial de interpolación para PAS = 90
        if ($pas === "90" && isset($caso["resultados"]["120"][$colesterol])) {
            $valor = intval(str_replace('%', '', $caso["resultados"]["120"][$colesterol]));
            $nuevoValor = max(1, $valor - 1);
            $riesgo = $nuevoValor . "%";
        }
        // Interpolación para PAS = 150
        elseif ($pas === "150" && isset($caso["resultados"]["140"][$colesterol]) && isset($caso["resultados"]["160"][$colesterol])) {
            $v1 = intval(str_replace('%', '', $caso["resultados"]["140"][$colesterol]));
            $v2 = intval(str_replace('%', '', $caso["resultados"]["160"][$colesterol]));
            $riesgo = round(($v1 + $v2) / 2) . "%";
        }
        // Interpolación para PAS = 170
        elseif ($pas === "170" && isset($caso["resultados"]["160"][$colesterol]) && isset($caso["resultados"]["180"][$colesterol])) {
            $v1 = intval(str_replace('%', '', $caso["resultados"]["160"][$colesterol]));
            $v2 = intval(str_replace('%', '', $caso["resultados"]["180"][$colesterol]));
            $riesgo = round(($v1 + $v2) / 2) . "%";
        }
        // Riesgo exacto sin interpolación
        elseif (isset($caso["resultados"][$pas]) && isset($caso["resultados"][$pas][$colesterol])) {
            $riesgo = $caso["resultados"][$pas][$colesterol];
        }

        break; // Se encontró el caso, salir del bucle
    }
}

// Asegurar que el riesgo mínimo mostrado sea al menos 1%
if (strpos($riesgo, '%') !== false) {
    $valorNumerico = intval(str_replace('%', '', $riesgo));
    if ($valorNumerico < 1) {
        $riesgo = "1%";
    }
}

// Guardar los datos necesarios en la sesión para mostrar en la pantalla de resultados
$_SESSION['riesgo'] = $riesgo;
$_SESSION['sexo'] = $sexo;
$_SESSION['edad'] = $edad;
$_SESSION['colesterol'] = $colesterolIngresado;
$_SESSION['pas'] = $pasIngresada;
$_SESSION['fumador'] = $fumador ? 'Sí' : 'No';
$_SESSION['diabetes'] = $diabetes ? 'Sí' : 'No';

// Guardar el registro en la base de datos
// Se marca peso, altura e IMC como "N/A" porque esta calculadora no los utiliza
include("../includes/db.php");

$peso = "N/A";
$altura = "N/A";
$imc = "N/A";

$stmt = $conn->prepare("INSERT INTO resultados 
    (nombre, apellido, sexo, edad, peso, altura, imc, pas, colesterol, fumador, diabetes, riesgo) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param(
    "ssssssssssss",
    $_SESSION['nombre'],
    $_SESSION['apellido'],
    $_SESSION['sexo'],
    $_SESSION['edad'],
    $peso,
    $altura,
    $imc,
    $_SESSION['pas'],
    $_SESSION['colesterol'],
    $_SESSION['fumador'],
    $_SESSION['diabetes'],
    $_SESSION['riesgo']
);
$stmt->execute();
$stmt->close();
$conn->close();

// Redirigir a la pantalla de resultados
header("Location: ../resultados/resultado_colesterol.php");
exit();
