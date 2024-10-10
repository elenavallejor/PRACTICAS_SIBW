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

   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
     $antiguoUser = $_SESSION['usuario'];
     $nuevoUser = $_POST['nuevoUser'];
     
     $nuevoCorreo = $_POST['nuevoCorreo'];
     $rol = $datosUsuario['rol'];
     
     $antiguaContraseña = $_POST['antiguaContraseña'];
     $nuevaContraseña = $_POST['nuevaContraseña'];
     $repetirContraseña = $_POST['repetirContraseña'];

     if ($nuevaContraseña == $repetirContraseña) {
        if (checkLogin($antiguoUser, $antiguaContraseña)) {
            modifyUser($antiguoUser, $nuevoUser, $nuevaContraseña, $nuevoCorreo, $rol);
            $_SESSION['usuario'] = $nuevoUser;
            $variables['usuario'] = $_SESSION['usuario'];
            $datosUsuario = getUser($_SESSION['usuario']);
            $variables['rol'] = $datosUsuario['rol'];
            $variables['correo'] = $datosUsuario['correo'];
            
            header('Location: /pcontrol.php');
        }
     } else {

     }
   }

   if (isset($variables['rol']) && $variables['rol'] >= 1) {
      echo $twig->render('pcontrol_editarperfil.html', $variables);
   } else {
      header('Location: pcontrol.php');
   }
?>