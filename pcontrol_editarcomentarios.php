<?php
   require_once "/usr/local/lib/php/vendor/autoload.php";
   include("database.php");

   $loader = new \Twig\Loader\FilesystemLoader('templates');
   $twig = new \Twig\Environment($loader);

   session_start();
   connectDB();

   if (isset($_SESSION['usuario'])) {
      $variables['usuario'] = $_SESSION['usuario'];
      $datosUsuario = getUser($_SESSION['usuario']);
      $variables['rol'] = $datosUsuario['rol'];
      $variables['correo'] = $datosUsuario['correo'];
   }


   $variables['comentariosDB'] = getAllComentariosDB();
   $variables['actividades'] = getAllComentariosDB_nomAct();

   if (isset($variables['rol']) && $variables['rol'] >= 2) {
      echo $twig->render('pcontrol_editarcomentarios.html', $variables);
   } else {
      header('Location: principal.php');
   }
?>
