<?php

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

$mysqli = null;

function connectDB() {
    global $mysqli;
    $mysqli = new mysqli('database', 'elenaSIBW', 'eSIBW', 'SIBW'); // CREATE USER 'elenaSIBW'@'%' IDENTIFIED BY 'eSIBW';
    if ($mysqli->connect_errno) {
      echo ("Fallo al conectar: " . $mysqli->connect_error);
    }
    return $mysqli;
}

//////////////////////////////////////////
///// OPERACIONES SOBRE ACTIVIDADES //////
/////////////////////////////////////////

function getActividad($id) {
    global $mysqli;
    $consulta = $mysqli->prepare("SELECT * FROM actividades WHERE id = ?");
    $consulta->bind_param("i", $id); 
    $consulta->execute();

    $actividad = $consulta->get_result();
    $datosAct = $actividad->fetch_assoc(); 
    return $datosAct;
}

function getAllActividades() {
  global $mysqli;
  $consulta = $mysqli->prepare("SELECT * FROM actividades");
  $consulta->execute();

  $actividad = $consulta->get_result();
  $datosAct = $actividad->fetch_all(MYSQLI_ASSOC); // Pasamos los datos a array
  return $datosAct;
}

function modifyActividad($idActividadAEditar, $tituloNuevo, $precioNuevo, $fechaNueva, $descripcionNueva, $galeriaNueva, $piedeFotoNuevo, $portadaNueva, $hashtagsNuevo) {
  global $mysqli;

  $consulta = $mysqli->prepare("UPDATE actividades SET nombre = ?, precio = ?, fecha = ?, descripcion = ?, imagenes = ?, piedefoto = ?, imagen_portada = ?, hashtag = ? WHERE id = ?");
  $consulta->bind_param("sissssssi", $tituloNuevo, $precioNuevo, $fechaNueva, $descripcionNueva, $galeriaNueva, $piedeFotoNuevo, $portadaNueva, $hashtagsNuevo,  $idActividadAEditar);
  $consulta->execute();
}

function deleteActividad($idActividad) {
  global $mysqli;

  $consulta = $mysqli->prepare("DELETE FROM actividades WHERE id = ?");
  $consulta->bind_param("i", $idActividad);
  deleteComentariosdeAct($idActividad);

  $consulta->execute();
}

function newActividad($tituloNuevo, $precioNuevo, $fechaNueva, $descripcionNueva, $galeria, $piedeFotoNuevo, $portada, $hashtagsNuevo) {
  global $mysqli;

  $consulta = $mysqli->prepare("INSERT INTO actividades (nombre, precio, fecha, descripcion, imagenes, piedefoto, imagen_portada, hashtag) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
  $consulta->bind_param("sissssss", $tituloNuevo, $precioNuevo, $fechaNueva, $descripcionNueva, $galeria, $piedeFotoNuevo, $portada, $hashtagsNuevo);
  
  $consulta->execute();
}

function vaciarGaleria($idActividad) {
  global $mysqli;
  $consulta = $mysqli->prepare("UPDATE actividades SET imagenes = '', piedefoto = '' WHERE id = ?");
  $consulta->bind_param("i", $idActividad);

  $consulta->execute();
}

function getActividades_Contenido($palabrasBuscadas, $rol) {
  $mysqli = connectDB();

  if ($rol >= 3) { // TODAS                                                                      
    $consulta = $mysqli->prepare("SELECT * FROM actividades WHERE LOWER(nombre) LIKE CONCAT('%', ?, '%') OR LOWER(descripcion) LIKE CONCAT('%', ?, '%')");
  } else if ($rol < 3) {
    $consulta = $mysqli->prepare("SELECT * FROM actividades WHERE (LOWER(nombre) LIKE CONCAT('%', ?, '%') OR LOWER(descripcion) LIKE CONCAT('%', ?, '%')) AND publicado = TRUE");
  }
  
  $consulta->bind_param("ss", $palabrasBuscadas, $palabrasBuscadas);
  $consulta->execute();
  

  $res = $consulta->get_result();
  $datos = $res->fetch_all(MYSQLI_ASSOC);
      
  return $datos;
}

function publicarActividad($idActividad) {
  $mysqli = connectDB();

  $consulta = $mysqli->prepare("UPDATE actividades SET publicado = TRUE WHERE id = ?");
  $consulta->bind_param("i", $idActividad);

  $consulta->execute();
}

function archivarActividad($idActividad) {
  $mysqli = connectDB();
  
  $consulta = $mysqli->prepare("UPDATE actividades SET publicado = FALSE WHERE id = ?");
  $consulta->bind_param("i", $idActividad);

  $consulta->execute();
}

///////////////////////////////////////
//// OPERACIONES SOBRE COMENTARIOS //// 
//////////////////////////////////////

function getAllComentarios($id_actividad) {
  global $mysqli;
  $consulta = $mysqli->prepare("SELECT * FROM comentarios WHERE id_actividad = ?");
  $consulta->bind_param("i", $id_actividad); 
  $consulta->execute();

  $comentarios = $consulta->get_result();
  $datosComentario = $comentarios->fetch_all(MYSQLI_ASSOC); // Pasamos los datos a array
  return $datosComentario;
}

function getAllComentariosDB() {
  global $mysqli;
  $consulta = $mysqli->prepare("SELECT * FROM comentarios"); 
  $consulta->execute();

  $comentarios = $consulta->get_result();
  $datosComentario = $comentarios->fetch_all(MYSQLI_ASSOC); // Pasamos los datos a array
  return $datosComentario;
}

function getAllComentariosDB_nomAct() {
  global $mysqli;
  $consulta = $mysqli->prepare("SELECT B.nombre FROM comentarios A INNER JOIN actividades B ON A.id_actividad=B.id"); 
  $consulta->execute();

  $comentarios = $consulta->get_result();
  $datosComentario = $comentarios->fetch_all(MYSQLI_ASSOC); // Pasamos los datos a array
  return $datosComentario;
}

function getComentario($idCo) { // Devuelve datos del comentario con el id $idCo
  global $mysqli;

  $consulta = $mysqli->prepare("SELECT * FROM comentarios WHERE id = ?");
  $consulta->bind_param("i", $idCo);
  $consulta->execute();

  $res = $consulta->get_result();
  $datosUsuario = $res->fetch_assoc();

  return $datosUsuario;
}

function deleteComentariosdeAct($idActividad) {
  global $mysqli;

  $consulta = $mysqli->prepare("DELETE FROM comentarios WHERE id_actividad = ?");
  $consulta->bind_param("i", $idActividad);

  $consulta->execute();

} 

function modifyComentario($idCo, $comentario) {
  global $mysqli;

  $consulta = $mysqli->prepare("UPDATE comentarios SET comentario = ? WHERE id = ?");
  $consulta->bind_param("si", $comentario, $idCo);

  $consulta->execute();
}

function deleteComentario($idCo) {
  $mysqli = connectDB();

  $consulta = $mysqli->prepare("DELETE FROM comentarios WHERE id = ?");
  $consulta->bind_param("i", $idCo);

  $consulta->execute();
}

function postComentario($comentario, $username, $email, $idAc) { // Subir comentartio a la base de datos
  global $mysqli;

  $fecha = date('d/m/Y - H:i:s');

  $consulta = $mysqli->prepare("INSERT INTO comentarios (comentario, nombre, fecha, email, id_actividad) VALUES (?, ?, ?, ?, ?)");
  $consulta->bind_param("ssssi", $comentario, $username, $fecha, $email, $idAc);
  $consulta->execute();
}

///////////////////////////////////////
///// OPERACIONES SOBRE SESIONES ///// 
//////////////////////////////////////

function getUser($nick) { // Devuelve datos del usuario llamado $nick
  global $mysqli;

  $consulta = $mysqli->prepare("SELECT username, password, correo, rol FROM usuarios WHERE username = ?");
  $consulta->bind_param("s", $nick);
  $consulta->execute();

  $res = $consulta->get_result();
  $datosUsuario = $res->fetch_assoc();

  return $datosUsuario;
}

function getAllUsers() { // Devuelve todos los usuarios
  global $mysqli;

  $consulta = $mysqli->prepare("SELECT * FROM usuarios");
  $consulta->execute();

  $res = $consulta->get_result();
  $usuarios = $res->fetch_all(MYSQLI_ASSOC);

  return $usuarios;
}

function checkLogin($user, $pass) { 
  global $mysqli;

  $consulta = $mysqli->prepare("SELECT username, password FROM usuarios WHERE username = ?");
  $consulta->bind_param("s", $user);
  $consulta->execute();

  $res = $consulta->get_result();
  $hash = $res->fetch_assoc();

  if ($hash != null) { //Si el usuario existe
    if (password_verify($pass, $hash['password'] )) { //Si la contraseña es igual que el hash
      return true;
    }
  }
  return false;
}

function addUser($nick, $pass, $email) {
  global $mysqli;

  $consultaRepetidos = $mysqli->prepare("SELECT * FROM usuarios WHERE username = ? OR correo = ?");
  $consultaRepetidos->bind_param("ss", $nick, $email);
  $consultaRepetidos->execute();

  $consultaRepetidos->store_result();

  if ($consultaRepetidos->num_rows == 0) {   // Verificar si no hay filas (es decir, no se ha usado antes)
      $hashed_password = password_hash($pass, PASSWORD_BCRYPT);
      $consulta = $mysqli->prepare("INSERT INTO usuarios (username, password, correo, rol) VALUES (?, ?, ?, 1)");
      $consulta->bind_param("sss", $nick, $hashed_password, $email);
      
      $consulta->execute();
      return true; // Se hace bien el add
  } else {    
      return false; // Error, nombre o correo ya en uso
  }
}

function modifyUser($nick, $newNick, $newPass, $newCorreo, $newRol) {
  global $mysqli;

  $newPassHash = password_hash($newPass, PASSWORD_BCRYPT);

  $consulta = $mysqli->prepare("UPDATE usuarios SET username = ?, password = ?, correo = ?, rol = ? WHERE username = ?");
  $consulta->bind_param("sssss", $newNick, $newPassHash, $newCorreo, $newRol, $nick);
  
  $consulta->execute();
}

function modifyUserPermissions($username, $newRol) {
  $mysqli = connectDB();

  $consulta = $mysqli->prepare("UPDATE usuarios SET rol = ? WHERE username = ?");
  $consulta->bind_param("ss", $newRol, $username);
  
  $consulta->execute();
}

function getAllPalabrasProhibidas() {
  global $mysqli;

  $consulta = $mysqli->prepare("SELECT * FROM PalabrasProhibidas");
  $consulta->execute();
  $palabras = [];

  $palabrasProhibidas = $consulta->get_result();
  $datosPalabras = $palabrasProhibidas->fetch_all(MYSQLI_ASSOC);
  
  foreach ($datosPalabras as $dato) {
    $palabras[] = $dato['palabra'];
  }
  
  return $palabras;
}


?>
