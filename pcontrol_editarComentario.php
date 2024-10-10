<?php 
   require_once "/usr/local/lib/php/vendor/autoload.php";
   include("database.php");

   $loader = new \Twig\Loader\FilesystemLoader('templates');
   $twig = new \Twig\Environment($loader);

   connectDB();
   session_start();

   $idCo = $_GET['comentario'];
   $variables['comentario'] = getComentario($idCo);
   $variables['actividades'] = getAllComentariosDB_nomAct();


   if (isset($_SESSION['usuario'])) {
      $variables['usuario'] = $_SESSION['usuario'];
      $datosUsuario = getUser($_SESSION['usuario']);
      $variables['rol'] = $datosUsuario['rol'];
   }

   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $comentarioNuevo = $_POST['comentarioNuevo'];
      $comentarioNuevo = "¡Mensaje editado por un moderador!: " . $comentarioNuevo; // Concatenar cadena modificacion

      modifyComentario($idCo, $comentarioNuevo);

      header('Location: pcontrol_editarcomentarios.php');
   }

   if (isset($variables['rol']) && $variables['rol'] >= 2) { // Si tiene permitido gestionar comentarios
      echo $twig->render('pcontrol_editarComentario.html', $variables);
   } else {
      header('Location: principal.php');
   }
?>