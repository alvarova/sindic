<?
//----------------------------------------------
// Archivo para actualizar via AJAX registros
// en la DB.
//
// Emplado en:
//             admingroup.php
//----------------------------------------------
	$pre="../";  //Nivel del directorio para agregar DB y Pear
	include_once($pre."localconf.php");
	error_reporting(0);

$db = ADONewConnection();
//$db->debug = false ;
$result = $db->Connect("$dbhost", "$dbuname", "", "$dbname");


switch ($_POST['accion']) {
    case "update":
		$consulta = "UPDATE  `email_grupo` SET  `nombregrupo` =  '".addslashes($_POST['nombre'])."' WHERE  `id` =".$_POST['id'];
		$rs=$db->Execute($consulta);	
		if ($rs === false) die("Fallo en la consulta de grupo...");
		echo "-> se actualizó el registro [".$_POST['id']."]".addslashes($_POST['nombre']);
        break;
    case "insert":
		$consulta = "INSERT INTO  `email_grupo` (`nombregrupo`)  VALUES	('".addslashes($_POST['nombre'])."')";
		$rs=$db->Execute($consulta);	
		if ($rs === false) die("Fallo agregando grupo...".$_POST['nombre']);
		echo "-> se agrego el registro [".$_POST['id']."]".addslashes($_POST['nombre']);
        break;
    case "delete":
		$consulta = "DELETE FROM `email_grupo` WHERE  `id` =".$_POST['id'];
		$rs=$db->Execute($consulta);	
		if ($rs === false) die("Fallo eliminacion del grupo...");
		echo "-> se elimino el registro [".$_POST['id']."]".addslashes($_POST['nombre']);
        break;
}


$db->close();
?>
