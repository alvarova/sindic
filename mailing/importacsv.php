<?
$pre="../";  //Nivel del directorio para agregar DB y Pear

	include_once($pre."localconf.php");
	error_reporting(0);
	$archivo="./temporal/listado.csv";
	
	function is_email($cadena){
		$sale=explode("@",$cadena);
		if ($sale[1]=="") {$retorna=FALSE;}
		else {$retorna=TRUE;}
		return ($retorna);
	}
?>
<DOCTYPE html><html><head><title>Importador de contactos - CSV</title><meta charset="UTF-8">
<script type="text/javascript" src="../js/jquery.min.js"></script>
<!--[if lt IE 9]><script src="../js/html5.js"></script><![endif]-->
<script type="text/javascript" src="../js/prettify.js"></script>                                   <!-- PRETTIFY -->
<script type="text/javascript" src="../js/kickstart.js"></script> 
<link rel="stylesheet" type="text/css" href="../css/kickstart.css" media="all" /> 
<link rel="stylesheet" type="text/css" href="../style.css" media="all" />

</head>
<body>
<a id="top-of-page"></a><div id="wrap" class="clearfix">
	<div class="col_9">
		<h4>Direcciones procesadas</h4> 
<?
if ($_FILES["file"]["error"] > 0)
  {
  echo "Error: " . $_FILES["file"]["error"] . "<br />";
  }
else
  {
  echo "Upload: " . $_FILES["file"]["name"] . "<br />";
  echo "Type: " . $_FILES["file"]["type"] . "<br />";
  $tamanio=($_FILES["file"]["size"] / 1024);
  $arre=explode('.', $tamanio);
  $seg=substr($arre[1],0,2);
  echo "Size: " . $arre[0] .".".$seg. " Kb<br />";
  echo "Temporal: " . $_FILES["file"]["tmp_name"];
  }

echo '<hr class="alt2" />';

//var_dump($_FILES); echo "<br/>";


$db = ADONewConnection();
//$db->debug = false ;
$result = $db->Connect("$dbhost", "$dbuname", "", "$dbname");

$db2 = ADONewConnection();
$result2 = $db2->Connect("$dbhost", "$dbuname", "", "$dbname");

$rslt= move_uploaded_file($_FILES["file"]["tmp_name"], $archivo );
//var_dump($rslt); echo "<br/>";



if ($rslt) {

	$gestor = fopen($archivo, "r");
	//var_dump($gestor); echo ":gestor<br/>";
	$contenido = fread($gestor, filesize($archivo));
	fclose($gestor);
	
	$arr=explode("\n", $contenido);
	
	//var_dump($arr);echo ":array<br/>";
	//var_dump($contenido);echo "<br/>";
	
	
	/*
	 * Filtrado de emails repetidos
	 */ 
	
	$i=0;     		//Contador de elementos a importar
	$arr[0]="";
	foreach ($arr as $line){
		$l=explode(",", $line);
		if (($l[1]!=NULL) && (is_email($l[1]))){        //si la direccion de correo es nula no se carga - quitar encabezado
			$arreglo[0][]=$l[0]; //nombres
			$arreglo[1][]=$l[1]; //direcciones
			$i++;
		}
	}
	var_dump($i);
	$indices = implode(',',$index);
	//var_dump($arreglo);echo "<br/>";echo "<br/>";
	
	$consulta = "SELECT * FROM email WHERE direccion IN ('".implode("','",$arreglo[1])."')";  //Busco direcciones repetidas para omitirlas
	$rs=$db->Execute($consulta);	
	//echo "<br/>"; var_dump($consulta); echo "<br/>";
  if ($rs === false) die("Fallo en la consulta de repeticiones...");
   
    echo "<h5>Direcciones repetidas</h5>(se omiten)<br/><ol>";
    $cantidad=0;
    while (!$rs->EOF) {
		echo "<li>".$rs->fields['direccion']."</li>";
		$emailrpt[]=$rs->fields['direccion'];
		$cantidad++;
		$rs->MoveNext();
    }
	echo "</ol>";
	echo "Total de repetidos: $cantidad de ".count($arreglo[1])." total/es. ($i)<br/>";
	
	
	/*
	 * Proceso de los email - Dando de Alta
	 */
	
	//INSERT INTO `sindicatofarm`.`email` (`id_email`, `id_afiliado`, `direccion`) VALUES (NULL, '0', 'flora@newwavemktg.com');
	$inserta="INSERT ";
	$indice=0;
	echo "<hr class='alt1 col_12' /><h5>Procesando lista</h5><br/>";
	
	$grupo=""; 
	$linked=0;
	
	if ($_POST['grupo']!="") { $grupo=$_POST['grupo'];}  //Si se envia el id del grupo, se habilita $grupo, sino nulo.
	

	foreach ($arreglo[1] as $emails){
		
		$procesar=true;
		foreach ($emailrpt as $omitir){  ///Evaluo que no se encuentre en la lista de emails ya dados de alta.
			if ($omitir==$emails) { $procesar=false; }
		}	
		if ($procesar){
			$sql= "INSERT INTO `sindicatofarm`.`email` (`id_email`, `id_afiliado`, `direccion`, `observacion`) VALUES (NULL, '0', '".addslashes($emails)."', '".addslashes($arreglo[0][$indice])."');";
			echo addslashes($emails)."<br/>";
			//echo $sql."<br/>";
			$rs=$db->Execute($sql);
			if ($rs === false) die("Fallo en consulta de inserción de elementos...");
			//Ok, ahora obtengo el ultimo Id insertado y con el lo linkeo al grupo en el caso que se haya especificado grupo de pertenencia.
			$elId = sprintf( "%u", mysql_insert_id() );
			
			/*
			 * Proceso de vinculado de email con email_link en el caso de disponer de grupo preasignado a la lista importada
			 */ 
			
			if ($grupo!=""){   //Si existe el ID_Grupo lo vinculamos en la tabla 
				
				$sql2= "INSERT INTO `sindicatofarm`.`email_link` (`id_email`, `id_grupo`) VALUES ('".$elId."', '".$grupo."');";	
				$rs2=$db2->Execute($sql2);	
				if ($rs2 === false) die("Fallo al crear enlace de Id_Grupos $grupo - Id_Email $email...");
				$linked++;
			}
		}	
		$indice++;
	}
	if ($grupo) { echo "Se enlazaron ".$linked." direcciones de email<br/>"; }
	
} else {
	echo "Problemas con el archivo subido. (Directorio Erroneo-Permisos-ExisteArchivo)";
	
}
	echo "Eliminando temporales<br/>";
	unlink($archivo);
	echo "Cerrando conexiones<br/>";
	echo "<hr class='alt1 col_12' /> <h5>Otras acciones</h5> <a href='./importador.html' ><button class=' pop small'><span class='icon gray small' data-icon=')'></span> Agregar otro CSV</button></a> <a href='#' onclick='window.close();'><button class=' pop small'><span class='icon gray small' data-icon='Q'></span> Salir  </button></a><br/><br/> 	</div> </div> </body> </html>";

	close($gestor);
?>
