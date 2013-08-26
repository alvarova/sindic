<?php
$pre="";
include_once("../localconf.php");
  
error_reporting(0);
$db = ADONewConnection();
$result = $db->Connect("$dbhost", "$dbuname", "", "$dbname");


$usuario = $_POST['usuario'];
$password = $_POST['password'];
	//Obtengo la version encriptada del password
$usuario   = stripslashes($usuario);
$password = stripslashes($password);

$usuario   = mysql_real_escape_string($usuario);
$password = mysql_real_escape_string($password);
//echo $usuario."-";
$pw_enc = md5($password);
	
$sql="    SELECT *     FROM   usuarios     WHERE  nombre='".$usuario."' AND  password='$pw_enc'  ";
//var_dump($sql);
$rs=$db->Execute($sql);

 
//$count=mysql_num_rows($result);


$uid = "";
	
	//Si existe al menos una fila
	if( $rs->_numOfRows )
	{		
		//Obtener el Id del usuario en la BD 		
		$uid = $rs->fields['id_usuario'];
		$nombre = $rs->fields['nombre'];
		$nivel = $rs->fields['nivel'];
		

		//Iniciar una sesion de PHP
		session_start();
		//Crear una variable para indicar que se ha autenticado
		$_SESSION['autenticado']    = 'SI';
		//Crear una variable para guardar el ID del usuario para tenerlo siempre disponible
		$_SESSION['uid']       		= $uid;
		$_SESSION['usuario']       	= $nombre;
		$_SESSION['nivel']       	= $nivel;
		//CODIGO DE SESION

		//Crear un formulario para redireccionar al usuario y enviar oculto su Id 
  		echo '<body style="bgcolor: #000000; color:#000000;"><form name="formulario" method="post" action="../index.php?ac=in"> <input type="hidden" name="idUsr" value='.$uid.' /> </form>';
	}
	else {
		session_start();
		// Destruye todas las variables de la sesion
		session_unset();
		// Finalmente, destruye la sesion
		session_destroy();
		echo '<body style="bgcolor: #000000; color:#000000;"><form name="formulario" method="post" action="../index.php"><input type="hidden" name="msg_error" value="1"></form>';
	}

//var_dump($rs->_numOfRows); echo "<br/>";
//var_dump($rs);echo "<br/>";
//echo "Nombre:".$nombre.'-PasswordMD5:'.$pw_enc."|".$password;

?>
					
<script type="text/javascript"> 
	//Redireccionar con el formulario creado
	document.formulario.submit();
</script>