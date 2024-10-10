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

  $variables['actividad'] = getActividad($_GET['actividad']);

  if ($variables['actividad']['imagenes'] != '') {
    $variables['imagenes_separadas'] = explode(',', $variables['actividad']['imagenes']);
    $variables['piedefoto'] = explode('%', $variables['actividad']['piedefoto']);      
  }

  $variables['hashtagsSeparados'] = [];
  $variables['hashtags'] = [];
  $variables['hashtags'] = explode(', ', $variables['actividad']['hashtag']);

  foreach ($variables['hashtags'] as $hashtag) { //quito los # para luego poder utilizarlos para el enlace
    $hashtag = substr($hashtag, 1);
    $variables['hashtagsSeparados'][] = $hashtag;
  }
 
  $variables['comentarios'] = getAllComentarios($_GET['actividad']);

  if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Si se produce un post
    $username = $_SESSION['usuario'];
    $contenido = $_POST['comentario'];
    if (isset($username) && isset($contenido)) { // Si hay usuario y hay contenido en la caja del comentario
       postComentario($contenido, $username, $datosUsuario['correo'], $_GET['actividad']); // Subimos el comentario a la base de datos
       header('Location: /actividad.php?actividad='.$_GET['actividad']); // Enviamos el usuario a la pagina (recargarmos)
    }
 }

  echo $twig->render('actividad.html', $variables);
?>
  