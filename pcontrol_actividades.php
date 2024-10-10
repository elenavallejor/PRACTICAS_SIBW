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

   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
     $user = $_POST['usuarioPermisos'];
     $rol = $_POST['roles'];
     modifyUserPermissions($user, $rol);
     header('Location: /paneldecontrol/permisos');
   }

   $variables['actividades'] = getAllActividades();

   if (isset($variables['rol']) && $variables['rol'] >= 3) {
      echo $twig->render('pcontrol_actividades.html', $variables);
   } else {
      header('Location: pcontrol.php');
   }
?>
