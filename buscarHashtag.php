<?php
require_once "/usr/local/lib/php/vendor/autoload.php";
include("database.php");

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

connectDB();
session_start();

if (isset($_SESSION['usuario'])) {
   $variables['usuario'] = $_SESSION['usuario'];
   $datosUsuario = getUser($_SESSION['usuario']);
   $variables['rol'] = $datosUsuario['rol'];
}

$variables['palabraBuscada'] = $_GET['hashtag'];
$variables['actividades'] = getAllActividades();
$variables['actividadesBuscadas'] = [];
$variables['palabraBuscada'] = '#' . $variables['palabraBuscada'];

foreach ($variables['actividades'] as $actividad) {
   if (isset($actividad['hashtag']) && stripos($actividad['hashtag'], $variables['palabraBuscada']) !== false) {
       $variables['actividadesBuscadas'][] = $actividad;
   }
}

echo $twig->render('buscarHashtag.html', $variables);
?>
