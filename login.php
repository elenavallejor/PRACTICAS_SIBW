<?php
    require_once "/usr/local/lib/php/vendor/autoload.php";
    include("database.php");

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);
    $variables['error'] = False;

    connectDB();
    session_start(); // Función primitiva de php, comprueba si se ha incializado sesion con anterioridad 

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $user = $_POST['user'];
      $pass = $_POST['constraseña'];
  
      if (checkLogin($user, $pass)) {
        $_SESSION['usuario'] = $user;
        header('Location: pcontrol.php');
      } else {
        $variables['error'] = True;
      }
    }
  
    echo $twig->render('login.html', $variables)
?>