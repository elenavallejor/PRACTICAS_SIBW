<?php
require_once "/usr/local/lib/php/vendor/autoload.php";
include("database.php");

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

connectDB();
session_start();

$variables['rol'] = 0;

if (isset($_SESSION['usuario'])) {
    $variables['usuario'] = $_SESSION['usuario'];
    $datosUsuario = getUser($_SESSION['usuario']);
    $variables['rol'] = $datosUsuario['rol'];
}

if (isset($_GET['palabrasBuscadas'])) { 
    $datos = [];
    $palabrasBuscadas = $_GET['palabrasBuscadas'];
    if ($variables['rol'] >= 3 && isset($variables)) {
        $datos = getActividades_Contenido($palabrasBuscadas, $variables['rol']); // SE PUEDEN VER PUBLICADAS Y ARCHIVADAS
    } else {
        $datos = getActividades_Contenido($palabrasBuscadas, 0); // SOLO PUBLICADAS
    }

    
    
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode($datos);
        exit();
    }
}

    echo $twig->render('buscarActividad.html', $variables);
?>
