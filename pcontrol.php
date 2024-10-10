<?php
   require_once "/usr/local/lib/php/vendor/autoload.php";
   include("database.php");

   $loader = new \Twig\Loader\FilesystemLoader('templates');
   $twig = new \Twig\Environment($loader);

   connectDB();
   session_start();
   
   $variables[] = [];
   $variables['rol'] = -1;

   if (isset($_SESSION['usuario'])) {
      $variables['usuario'] = $_SESSION['usuario'];
      $datosUsuario = getUser($_SESSION['usuario']);
      $variables['rol'] = $datosUsuario['rol'];
   }

   if (isset($_SESSION['usuario'])) {
      echo $twig->render('pcontrol.html', $variables);
   } else {
      header('Location: principal.php');
   }
  
?>
