<?

/*
 * MODULO: Loader.php
 * DEVELOPER: ALVERAL (C) 2012
 * Permite el volcado de los datos de las DBF a MySQL
 * Elimina informacion anterior y vuelve a volcar la info. NO SE MODIFICA estructura, solo datos de las tablas.
 * DBF Original permanece intacto.
 * Implementar seguridad para acceso y bandera para solo reemplazar o actualizar campos
 */
 
 function procesa($dbtabla, $dbfile)
 {
	echo "1.Inicializando $dbtabla<br/>";
	if (($dbtabla=="") || ($dbfile=="")) {
		print_r("ERROR: No es posible procesar tablas sin nombre, ni definicion de registro. Parametro/s vacio/s.");
		break;
	}else{
			
			$Adodb = ADONewConnection();
			$result = $Adodb->Connect("localhost", "root", "", "sindicatofarm");
			echo "= Enlace MySQL [".($result)."]<br/>" ;
			
			$dsn = "Driver={Microsoft Visual FoxPro Driver};SourceType=DBF;SourceDB=$dbfile;Exclusive=NO;collate=Machine;NULL=NO;DELETED=NO;BACKGROUNDFETCH=NO;";
			$rs=odbc_connect($dsn,"","");
			echo "= Enlace DBF [".($rs)."]<br/>" ;
			
			echo "2.Purgando $dbtabla<br/>";
			$mysql="TRUNCATE TABLE  `$dbtabla`";
			if ($Adodb->Execute($mysql) === false) { print "Se produjo un error en el proceso de la tabla $dbtabla - Consulte al administrador: ".$db->ErrorMsg()."<br/>"; break; }

			$reset="ALTER TABLE `$dbtabla` AUTO_INCREMENT=1";
			if ($Adodb->Execute($reset) === false) { print "Se produjo un error al resetear la tabla $dbtabla - Consulte al administrador: ".$db->ErrorMsg()."<br/>"; break; }


			$sql = "SELECT * from  $dbfile";
			echo "3.Procesando $dbfile<br/>";
			$result = odbc_exec ($rs, $sql); 
			$total=0;			
			$c=0;  //Contador para volcado de inserts simultaneos
			$inserts="";
			$campos='';
			$valores='';					
			echo "4.Volcando $dbfile a $dbtabla<br/>";													   
							
							while($array = odbc_fetch_array($result))   //Tengo el volcado completo en Array, a navegar por filas
								{
									$total++;
									foreach ($array as $clave=>$valor)  //Tengo la fila con los campos, a navegar la fila completa por columna, Formar sentencia SQL
									{
									  if ($c==10) { $campos.="`".$clave."`,"; }
									  $valores.="'".addslashes($valor)."',";
									}
									
									$inserts.="(".substr($valores, 0, -1)."),";  //sacamos coma y agregamos parentesis
									$valores="";
									if ($c==10) {  //hacer commit
									   $campos=substr($campos, 0, -1);  //sacamos coma
									   $inserts=substr($inserts, 0, -1);  //sacamos coma
									   $mysql="INSERT INTO `sindicatofarm`.`$dbtabla` ($campos) VALUES $inserts;";
									 
									   //print_r("$mysql<br/><br/>");	
									   if ($Adodb->Execute($mysql) === false) { print "Se produjo un error en el proceso de la tabla $dbtabla - Consulte al administrador: ".$Adodb->ErrorMsg()."<br/>"; break; }
									   $c=0;
									   $inserts="";
									   $campos='';
									   $valores='';																		   
									}
									
									$c++;
																			
									
								} //Final While para el ARRAY de los registros			
			echo "5. Cerrando. Proceso concluido para $dbtabla con $total registros ejecutados.<br/>";													   
			//$Adodb->close();
	}	 
 }

include_once('localconf.php');


	/*
	 * Tomo los valores para todas las tablas de Visual Fox
	 * Verificar existencia de archivos, mostrar error en caso negativo.
	 */
	 
	$tFamiliares='C:\\xampp\\htdocs\\sindicato\\Data\\familiares.DBF';
	$tAfiliados='C:\\xampp\\htdocs\\sindicato\\Data\\afiliados.DBF';
	$tFarmacias='C:\\xampp\\htdocs\\sindicato\\Data\\farmacias.DBF';
	$tParientes='C:\\xampp\\htdocs\\sindicato\\Data\\parientes.DBF';		
	$tCategorias='C:\\xampp\\htdocs\\sindicato\\Data\\categorias.DBF';		

	//Get de boolean one!!! Vamos quien me dice que no?
	$bAfiProcesa=is_file($tAfiliados);
	$bCatProcesa=is_file($tCategorias);
	$bFamProcesa=is_file($tFamiliares);
	$bFarProcesa=is_file($tFarmacias);
	$bParProcesa=is_file($tParientes);		
	
	
	/*
	 * Comienza el trabajo sucio: Comencemos a volcar las tablas de a uno
	 * sobre MySQL.
	 * Paso1. TRUNCATE TABLE  `parientes`
	 * Paso2. INSERT INTO `sindicatofarm`.`parientes` (`id_parentesco`, `parentesco`) VALUES (NULL, 'HIJO'), (NULL, 'ESPOSA');
	 */
	 echo "***Inicio del Proceso de Importacion***<br/>";
	 
	 if ($bAfiProcesa) { procesa("afiliados",$tAfiliados); }else{ echo "Error no se encuentra ".$tAfiliados; break; }
	 
	 if ($bFarProcesa) { procesa("farmacias",$tFarmacias); }else{ echo "Error no se encuentra ".$tFarmacias; break; }
	 
	 if ($bParProcesa) { procesa("parientes",$tParientes); }else{ echo "Error no se encuentra ".$tParientes; break; }
	 
	 if ($bFamProcesa) { procesa("familiares",$tFamiliares); }else{ echo "Error no se encuentra ".$tFamiliares; break; }
	 
	 if ($bCatProcesa) { procesa("categorias",$tCategorias); }else{ echo "Error no se encuentra ".$tCategorias; break; }
	 echo "**** Fin de Proceso de Importacion ****<br/>";
	 
	 

?>
