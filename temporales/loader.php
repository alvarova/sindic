<?

/*
 * MODULO: Loader.php
 * DEVELOPER: ALVERAL (C) 2012
 * Permite el volcado de los datos de las DBF a MySQL
 * Elimina informacion anterior y vuelve a volcar la info. NO SE MODIFICA estructura, solo datos de las tablas.
 * DBF Original permanece intacto.
 * Implementar seguridad para acceso y bandera para solo reemplazar o actualizar campos
 */
 
 

include_once('localconf.php');




	/*
	 * Abro conexión a MySQL y seteo parametros de la DB cargados en el modulo local
	 */
	$db = ADONewConnection();
	//$db->debug = true ;
	$result = $db->Connect("$dbhost", "$dbuname", "$dbpass", "$dbname"); 

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
	 * Creo el DSN para levantar localmente las DBF y luego poder extraer los datos
	 * Si todo esta Ok, se instalo el ODBC y el Runtime VFP funciona Ok!
	 */
	 
	$dsn = "Driver={Microsoft Visual FoxPro Driver};SourceType=DBF;SourceDB=$tAfiliados;Exclusive=NO;collate=Machine;NULL=NO;DELETED=NO;BACKGROUNDFETCH=NO;";
	$rs=odbc_connect($dsn,"","");
	
	/*
	 * Comienza el trabajo sucio: Comencemos a volcar las tablas de a uno
	 * sobre MySQL.
	 * Paso1. TRUNCATE TABLE  `parientes`
	 * Paso2. INSERT INTO `sindicatofarm`.`parientes` (`id_parentesco`, `parentesco`) VALUES (NULL, 'HIJO'), (NULL, 'ESPOSA');
	 */
	 if ($bFarProcesa) {
			$mysql="TRUNCATE TABLE  `farmacias`";
			if ($db->Execute($mysql) === false) { print "Se produjo un error en el proceso de la tabla $tFarmacias - Consulte al administrador: ".$db->ErrorMsg()."<br/>"; break; }
	
			$sql = "SELECT * from  $tFarmacias";
						
			$result = odbc_exec ($rs, $sql); 
						
						//var_dump($result);
							$c=0;  //Contador para volcado de inserts simultaneos

									   $inserts="";
									   $campos='';
									   $valores='';																		   
							
							while($array = odbc_fetch_array($result))   //Tengo el volcado completo en Array, a navegar por filas
								{
									//var_dump($array);
									
									//print_r($array);
									//print_r("<br/><br/>");
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
									   $mysql="INSERT INTO `sindicatofarm`.`farmacias` ($campos) VALUES $inserts;";
									 
									   print_r("$mysql<br/><br/>");	
									   if ($db->Execute($mysql) === false) { print "Se produjo un error en el proceso de la tabla $tFarmacias - Consulte al administrador: ".$db->ErrorMsg()."<br/>"; break; }
									   $c=0;
									   $inserts="";
									   $campos='';
									   $valores='';																		   
									}
									
									$c++;
									
									//$db->Execute($sql);
										/*echo "ID-Afiliado:".$array['id_afiliado']."-";
										echo "ID-familiares:".$array['id_familiar']."-";
										echo "ID-Parentesco:".$array['id_parentesco']."-";
										echo $array['nombre']."-";
										echo $array['fecha_nacimiento']."-";
										echo $array['nro_documento']."<br/><br/>";*/
										// 
										
									
								}
							
			}
/*
 * 			SELECT cursos.*, zona.ciudad, zona.ubicacion    FROM relCursoZona LEFT JOIN cursos  
			ON relCursoZona.id = cursos.idCurso    LEFT JOIN zona   ON zona.id=relCursoZona.idZona   ORDER BY relCursoZona.id
}*/
	 

?>
