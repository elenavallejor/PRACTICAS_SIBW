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
      $tituloNuevo = $_POST['tituloNuevo'];
      $precioNuevo = $_POST['precioNuevo'];
      $fechaNueva = $_POST['fechaNueva'];
      $descripcionNueva = $_POST['descripcionNueva'];
      $hashtagsNuevo = $_POST['hashtagsNuevo'];
      $portada;
      $galeria = '';
      $piedeFotoNuevo = '';


      if (isset($_FILES['imagen_portada']) && !empty($_FILES["imagen_portada"]["name"])) { // Si hemos añadido portada
         $errors= array();
                
         $file_name = $_FILES['imagen_portada']['name'];
         $file_size = $_FILES['imagen_portada']['size'];
         $file_tmp = $_FILES['imagen_portada']['tmp_name'];
         $file_type = $_FILES['imagen_portada']['type'];

         $explode = explode('.',$file_name);
         $end = end($explode);
         $file_ext = strtolower($end);
         
         $extensions= array("jpeg","jpg","png");
         
         if (in_array($file_ext,$extensions) === false){
           $errors[] = "Extensión no permitida, elige una imagen JPEG o PNG.";
         }
         
         if ($file_size > 2097152){
           $errors[] = 'Tamaño del fichero demasiado grande';
         }
         
         if (empty($errors)==true) {
            $portada = $file_name; // Se cambia la portada
            move_uploaded_file($file_tmp, "img/" . $file_name);
            $variables['imagen'] = "img/" . $file_name;
         }
         
         if (sizeof($errors) > 0) {
           $variables['errores'] = $errors;
         }
      }

      if (isset($_FILES['galeria']) && !empty($_FILES["galeria"]["name"])) { // Si hemos añadido fotos a la galeria
         $errors = array();

         $uploaded_files = array();
         $file_count = count($_FILES['galeria']['name']);
         $allowed_extensions = array("jpeg", "jpg", "png");
         $max_file_size = 2097152; 
  
         for ($i = 0; $i < $file_count; $i++) {
            $file_name = $_FILES['galeria']['name'][$i];
            $file_size = $_FILES['galeria']['size'][$i];
            $file_tmp = $_FILES['galeria']['tmp_name'][$i];
            $file_type = $_FILES['galeria']['type'][$i];   
           
            $explode = explode('.', $file_name);
            $end = end($explode);
            $file_ext = strtolower($end);
        
            if (!in_array($file_ext, $allowed_extensions)) {
               $errors[] = "Extensión no permitida para el archivo " . $file_name . ", elige una imagen JPEG o PNG.";
            }
     
            if ($file_size > $max_file_size) {
               $errors[] = 'Tamaño del fichero demasiado grande para el archivo ' . $file_name;
            }
     
            if (empty($errors)) {
               if (move_uploaded_file($file_tmp, "img/" . $file_name)) {
                  $uploaded_files[] = "img/" . $file_name;
                  if ($galeria != '')  {
                     $galeria = $galeria . ("," . $file_name );
                  } else {
                     $galeria = $galeria . ($file_name);
                  }
                  
                  if ($piedeFotoNuevo != '' || $piedeFotoNuevo != " " || $piedeFotoNuevo != NULL) {
                     $piedeFotoNuevo =  $piedeFotoNuevo . $_POST['piedefotoNuevo'];
                  } else {
                     $piedeFotoNuevo = $piedeFotoNuevo . "%" . $_POST['piedefotoNuevo'];
                  }
                  
               
               } else {
                  $errors[] = 'Error al subir el archivo ' . $file_name;
               }
            }    
            }
        
            if (!empty($uploaded_files)) {
               $variables['imagenes'] = $uploaded_files;
            }
        
            if (sizeof($errors) > 0) {
               $variables['errores'] = $errors;
            }
      }

      newActividad($tituloNuevo, $precioNuevo, $fechaNueva, $descripcionNueva, $galeria, $piedeFotoNuevo, $portada, $hashtagsNuevo);
      header('Location: /pcontrol_actividades.php');
      
   }

      
   if (isset($variables['rol']) && $variables['rol'] >= 3) { // Si el usuario tiene permisos
      echo $twig->render('pcontrol_añadirActividad.html', $variables);
   } else {
      header('Location: principal.php');
   }
?>
