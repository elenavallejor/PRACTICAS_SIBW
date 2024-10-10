<?php
  require_once "/usr/local/lib/php/vendor/autoload.php";
  include("database.php");

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);

  connectDB();

  $variables['actividad'] = getActividad($_GET['actividad']);
  $variables['imagenes_separadas'] = explode(',', $variables['actividad']['imagenes']);
  $variables['piedefoto'] = explode('%', $variables['actividad']['piedefoto']);

  $variables['comentarios'] = getAllComentarios($_GET['actividad']);

  echo $twig->render('actividad_imprimir.html', $variables);
?>
  