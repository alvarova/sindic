<?

/*
 * Modulo de consulta para campos de tipo select-option AJAX
 * 
 */ 
	$pre="../";  //Nivel del directorio para agregar DB y Pear
	include_once($pre."localconf.php");
	error_reporting(0);
	$consulta="";
	$sql="";
	$consulta = $_POST["accion"];
	$llave="";
	$llave = $_POST["idemail"];

switch ($consulta) {
    case "linkemail":
		$sql = "Select 	L.*, S.* 	From	email_grupo L 	Inner join 		email_link S 	On	L.id = S.id_grupo 	Where S.id_email = ".$llave;  //listado de los grupos al que pertenece el email
    break;
    default:
      $sale="Sin resultados";
    break;
    }
    
    if ($llave!=""){
	
		$db = ADONewConnection();
		//$db->debug = false ;
		$result = $db->Connect("$dbhost", "$dbuname", "", "$dbname");
		$db2 = ADONewConnection();
		//$db->debug = false ;
		$result2 = $db2->Connect("$dbhost", "$dbuname", "", "$dbname");
		
		
		
		$rs=$db->Execute($sql);	
		//Arreglo con los ID que tiene  asignado el usr
		if ($rs === false) die("Fallo consultando grupos para el email.".$sql);
		$c=0;
		while (!$rs->EOF) {	
				$grupos[]=$rs->fields['id_grupo'];		
				$rs->MoveNext();
				$c++;
		}
		//Enlistamos todos los 
		$sql="SELECT *  FROM  `email_grupo`";
		$rs2=$db2->Execute($sql);	
		
			if ($rs2 === false) die("Fallo consultando lista de grupos...".$sql);
			$val=$db2->Affected_Rows();
					
			$sale="";
			while (!$rs2->EOF) {	
					$selected="";
					foreach ($grupos as $grp){
						if ($grp==$rs2->fields['id']) 
							{ 
								$selected=' selected="selected" '; 
							}
					}
					$sale.='<option value="'.$rs2->fields['id'].'"'.$selected.'>'.$rs2->fields['nombregrupo'].'</option>';
					$rs2->MoveNext();
			}
	}
//var_dump($sale);
echo $sale;
?>

		 
