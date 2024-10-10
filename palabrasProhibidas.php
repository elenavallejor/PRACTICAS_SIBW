<?php
  require_once "/usr/local/lib/php/vendor/autoload.php";
  include("database.php");

  connectDB();
  $palabrasProhibidas = getAllPalabrasProhibidas();
  echo json_encode($palabrasProhibidas);
?>