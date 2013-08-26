<?php

session_start();
// Destruye todas las variables de la sesion
$nombre=$_SESSION['usuario'];
session_unset();
// Finalmente, destruye la sesion
session_destroy();

//Redireccionar a la pagina de login
header ("Location: ../index.php?ac=out&name=".$nombre);

?>