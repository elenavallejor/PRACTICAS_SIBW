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
      $variables['correo'] = $datosUsuario['correo'];
   }

   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
     $user = $_POST['usuarioPermisos'];
     $rol = $_POST['roles'];
     
     modifyUserPermissions($user, $rol);
     header('Location: /pcontrol_permisos.php');
   }

   $variables['usuarios'] = getAllUsers();

   if (isset($variables['rol']) && $variables['rol'] == 4) {
      echo $twig->render('pcontrol_permisos.html', $variables);
   } else {
      header('Location: principal.php');
   }
?>