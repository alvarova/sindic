<?

/*
 * Modulo de consulta para grupos volcado en select del importador.php
 * No modificar Exclusivamente para el uso de la funcion incrustada.
 */ 
	$pre="../";  //Nivel del directorio para agregar DB y Pear
	include_once($pre."localconf.php");
	error_reporting(E_ALL);
	$sql="";

  
		$db2 = ADONewConnection();
		$db->debug = false ;
		$result2 = $db2->Connect("$dbhost", "$dbuname", "", "$dbname");
		
		

		//Enlistamos todos los 
		$sql="SELECT *  FROM  `email_grupo` WHERE 1";
		$rs2=$db2->Execute($sql);	
		
			if ($rs2 === false) die("Fallo consultando lista de grupos...".$sql);
			$val=$db2->Affected_Rows();
			$c=0;		
			$sale="";
			echo "<select id='grupo' name='grupo' class='fancy'>";
			echo "<option value=''>-Ninguno-</option>";
			while (!$rs2->EOF) {	
					$c++;
					echo $c;
					$id=$rs2->fields['id'];
					$grp=$rs2->fields['nombregrupo'];
					echo '<option value="'.$id . '">' . $grp . '</option>';
//					$sale.="<option value='$id'>$grp</option>";
					$rs2->MoveNext();
			}
			echo "</select>";
	
//var_dump($sale);
echo $sale;
?>

		 
