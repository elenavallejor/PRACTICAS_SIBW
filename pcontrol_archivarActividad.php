<?php 
    require_once "/usr/local/lib/php/vendor/autoload.php";
    include("database.php");

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);
  
    connectDB();
    session_start();

    $idActividad = $_GET['actividad'];

    if (isset($_SESSION['usuario'])) {
        $variables['usuario'] = $_SESSION['usuario'];
        $datosUsuario = getUser($_SESSION['usuario']);
        $variables['rol'] = $datosUsuario['rol'];
    }
  
    if (isset($variables['rol']) && $variables['rol'] >= 3) {
        archivarActividad($idActividad);
        header('Location: pcontrol_actividades.php');
    } else {
        header('Location: principal.php');
    }
?>