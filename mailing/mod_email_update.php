<?
//----------------------------------------------
// Archivo para actualizar via AJAX registros
// en la DB de emails.
//
// Emplado en:
//             adminemail.php
//----------------------------------------------
	$pre="../";  //Nivel del directorio para agregar DB y Pear
	include_once($pre."localconf.php");
	error_reporting(0);

$db = ADONewConnection();
//$db->debug = false ;
$result = $db->Connect("$dbhost", "$dbuname", "", "$dbname");


switch ($_POST['accion']) {
    case "update":
    
		if (strlen($_POST['observacion'])!=strlen($_POST['direccion'])) {
			if (strlen($_POST['observacion'])>strlen($_POST['direccion'])) {
				;
				$consulta = "UPDATE  `sindicatofarm`.`email` SET  `observacion` =  '".addslashes($_POST['observacion'])."' WHERE  `email`.`id_email` =".$_POST['id'];
			} else {
				$consulta = "UPDATE  `email` SET  `direccion` =  '".addslashes($_POST['direccion'])."' WHERE  `id_email` =".$_POST['id'];
			}
			$rs=$db->Execute($consulta);	
			if ($rs === false) die("Fallo en el Update de email->".$_POST['id']."]->".addslashes($_POST['direccion']).addslashes($_POST['direccion']));
			echo "-> se actualizó el registro [".$_POST['id']."]".addslashes($_POST['nombre']);
			}else{
				echo "Error: No se enviaron datos validos";
			}
		break;
		
    case "insert":
		$consulta = "INSERT INTO  `email` (`observacion`, `direccion`)  VALUES	('".addslashes($_POST['nombre'])."','".addslashes($_POST['email'])."')";
		$rs=$db->Execute($consulta);	
		if ($rs === false) die("Fallo agregando grupo...".$_POST['nombre']);
		echo "-> se agrego el registro [".$_POST['id']."]".addslashes($_POST['nombre']);
        break;
    case "delete":
		//Eliminamos el email de la tabla principal
		$consulta = "DELETE FROM `email` WHERE  `id_email` =".$_POST['id'];
		$rs=$db->Execute($consulta);	
		if ($rs === false) die("Fallo eliminacion del email...");
		//Eliminamos cualquier vinculacion de este email a los grupos
		$consulta = "DELETE FROM `email_link` WHERE  `id_email` =".$_POST['id'];
		$rs=$db->Execute($consulta);	
		if ($rs === false) die("Fallo eliminacion de vinculos de grupo...");
		
		
		echo "-> se elimino el registro [".$_POST['id']."]".addslashes($_POST['nombre']);
        break;
    case "insertlink":
		if (($_POST['idgrupo']) && ($_POST['idemail'])) {
			$idgrp=$_POST['idgrupo'];
			$idemail=$_POST['idemail'];
		}
		$db2 = ADONewConnection();
		$result2 = $db2->Connect("$dbhost", "$dbuname", "", "$dbname");          
		$buscar_grupos = "SELECT * FROM email_link WHERE ( id_email = ".$idemail." ) ORDER BY id DESC LIMIT 0 , 1 ";
		$rs=$db->Execute($buscar_grupos); 
		$val=$db->Affected_Rows();
		$idlnk=$rs->fields['id'];
		echo "[registros existente ".$val."]\n";
		if($val==0) {		
			$consulta = "INSERT INTO  `email_link` (`id_grupo`, `id_email`)  VALUES	('".addslashes($idgrp)."','".addslashes($idemail)."')";
			$estado='agregó';
		}else{
			$consulta = "UPDATE  `sindicatofarm`.`email_link` SET  `id_grupo` =  '".$idgrp."', `id_email` =  '".$idemail."' WHERE  `email_link`.`id` = ".$idlnk.";";
			$estado='actualizó';
		}
		$rs=$db2->Execute($consulta);	
		if ($rs === false) die("Fallo agregando grupo...".$consulta);
		
		echo "-> se $estado el registro correctamete.";
        break;
}


$db->close();
?>
