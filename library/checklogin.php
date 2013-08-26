<?php
// El nombre del host, usualmente localhost
$host       = "localhost";
$username   = ""; // Nombre de usuario mysql
$password   = ""; // contraseña mysql
$db_name    = "test"; // base de datos que usaremso
$tbl_name   = "usuarios"; // nombre de la tabla

//Conectamos con el servidor y seleccionamos la base de datos
mysql_connect("$host", "$username", "$password")
                                       or die("cannot connect");
mysql_select_db("$db_name")or die("cannot select DB");

//Cogemos los datos que nos llegan desde el formulario
//( lo tendremos que crear)
$minombre   = $_POST['minombre'];
$contraseña = $_POST['contraseña'];
// Unas pequeñas medidas de seguridad para
//proteger las bases de datos de posibles inyecciones
$minombre   = stripslashes($minombre);
$contraseña = stripslashes($contraseña);
$minombre   = mysql_real_escape_string($minombre);
$contraseña = mysql_real_escape_string($contraseña);

$sql="
    SELECT *
    FROM   $tbl_name
    WHERE  username='$minombre'
    AND    password='$contraseña'
";
$result=mysql_query($sql);

// Contamos el numero de filas
$count=mysql_num_rows($result);
//Si el resultado marcado es $minombre
//y $contraseña,debería haber solo una fila
if($count==1)
{
    //Registramos usuario y redireccionamos a exito.php
    session_register("minombre");
    session_register("contraseña");
    header("location:exito.php");
}
else
{
    echo "Nombre de usuario equivocado o contraseña";
}
?>