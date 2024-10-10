<?php
    require_once "/usr/local/lib/php/vendor/autoload.php";
    include("database.php");

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);
    $error = False;
    
    connectDB();
    session_start(); // Función primitiva de php, comprueba si se ha incializado sesion con anterioridad 

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $user = $_POST['user'];
      $pass = $_POST['constraseña'];
      $correo = $_POST['email'];
      
      if (addUser($user, $pass, $correo)) {  // Si se hace bien el registro
        if (checkLogin($user, $pass)) { // Si el login es correcto
          $_SESSION['usuario'] = $user;
          header('Location: pcontrol.php'); // Entramos el la pantalla de control
        } else {
          $error = True;
        }
      } else {
        $error = True;
      }
    }

    echo $twig->render('registrarse.html', ['error' => $error])
?>