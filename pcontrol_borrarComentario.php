<?php
   require_once "/usr/local/lib/php/vendor/autoload.php";
   include("database.php");

   $loader = new \Twig\Loader\FilesystemLoader('templates');

   connectDB();
   session_start();

   $idCo = $_GET['comentario'];

   if (isset($_SESSION['usuario'])) {
      $variables['usuario'] = $_SESSION['usuario'];
      $datosUsuario = getUser($_SESSION['usuario']);
      $variables['rol'] = $datosUsuario['rol'];
   }

   if (isset($variables['rol']) && $variables['rol'] >= 2) {
      deleteComentario($idCo);
      header('Location: pcontrol_editarcomentarios.php');
   } else {
      header('Location: principal.php');
   }
?>