<?php
	require_once("./library/PEAR.php");
	require_once("./library/IT.php");
	include_once("./library/mod_qfunciones.php");
	
      
    	$body= new HTML_Template_IT();
    	$body->loadTemplatefile("./modulos/af_ordenes.tpl");

    	

    	//error_reporting(consulta);
		//$1 = "SELECT * FROM afiliados LEFT JOIN email ON afiliados.id_afiliado = email.id_afiliado WHERE afiliados.$campo = $criterio";
		//SELECT * FROM familiares WHERE fecha_nacimiento >= SUBDATE( CURDATE( ) , INTERVAL 60 DAY ) ORDER BY fecha_nacimiento DESC LIMIT 0 , 30;
		if (isset($_GET['id_afiliado'])) {
		 $db = ADONewConnection();
		 $db2= ADONewConnection();
		 //$db->debug = true ;
		 $result = $db->Connect("$dbhost", "$dbuname", "", "$dbname");
		 $result2 = $db2->Connect("$dbhost", "$dbuname", "", "$dbname");
		 
		 $id_afiliado = $_GET['id_afiliado'];
		 $consulta = "SELECT * FROM afiliados WHERE id_afiliado = $id_afiliado order by id_afiliado limit 0,1";

		 $rs=$db->Execute($consulta);
		 //$lg=$log->capturar($consulta);


		 /*
		 *  Comienzo del parse del afiliados y sus dats
		 */
		 if ($rs === false) {
			$aviso="No se encontraron registros segun el criterio de busqueda.";
		 }else{

		  if ($db->Affected_Rows()<1) {$aviso2="No se encontraron registros segun el criterio de busqueda. Intente nuevamente o redefina la consulta"; $sinid=true;}else{$sinid=false;}
		 
		  //Obtener timestamp del formulario al hacer submit, si tiene mas de 4 minutos no levantar datos. AVISAR
    	  /*if (isset($_POST['timestamp'])) {
    	  	if (($_POST['timestamp']+240)>time()) { $procesar=true; } else { $procesar=false; }   //Habilito timestamp para evaluar si el proceso demora mas de 4 minutos no se ingrese datos. 
    	  }	*/	 
		  // - Si tengo definido POST con el Nro. de ORDEN doy ALTA la entrega de chequera al afiliado correspondiente.	
		  if (isset($_POST['nro_orden'])) {
		  	if (isset($_POST['PMI'])) { $observacion2="[PMI]";} else {$observacion2="";}
		  	if (isset($_POST['embarazo'])) { $observacion2="[PMI-EMB]";} 
		  	$now = mktime(0,0,0,date("m"),date("d"),date("Y"));
			$fecha=date("Y-m-d", $now);
			//$db2->debug = true ;
		  	$observacion=addslashes($observacion2.$_POST['observacion']);
		  	if ($importe=="") $importe='0';
		  	$consulta="INSERT INTO `sindicatofarm`.`obra_social` (`id_afiliado` , `nro_orden` , `importe`, `fecha`, `observacion` ) VALUES ( ".$rs->fields['id_afiliado'].", ".$_POST['nro_orden'].", '".$_POST['importe']."', '$fecha', '".$observacion."');";
		  	$log->capturar($consulta);
		  	//if ($procesar) {
		  		$rs2=$db2->Execute($consulta); $avisa="Se registro la entrega de ordenes al afiliado.";
		  		//} else { $avisa.='No se proceso el registro de entrega. Tiempo de espera superado. Intente nuevamente'; }
		  }

    	  $body->setVariable("id_afiliado", $rs->fields['id_afiliado']);
    	  $body->setVariable("dni", $rs->fields['nro_documento']);
    	  $body->setVariable("nombre", $rs->fields['nombre']);
    	  $body->setVariable("timestamp", time());

    	  if ($rs->fields['obra_social']=="1") { $obrasocial=true;} else {$obrasocial=false; die('Afiliado sin cobertura OSocial. Verificar origen.');}
    	 
    	 //$db2->debug = true ;

    	  $consulta = "SELECT * FROM    familiares    LEFT JOIN parientes		ON familiares.id_parentesco=parientes.id_parentesco	   WHERE id_afiliado = $id_afiliado";
		  $rs2=$db2->Execute($consulta);
		
		  $PMI=false;  //Para determinar que el cuadro de habilitacion permita dar ordenes PMI
		  $afil_pmi=""; //Para colocar TXT en tooltip
		  if (!($rs2 === false)) {
			while (!$rs2->EOF) {
					if ($obrasocial && dar_pmi($rs2->fields['fecha_nacimiento'])) 
						{ 
							$PMI=true; 
							$body->setCurrentBlock("ordenespmi");
							$body->setVariable("pmi","PMI");
							$body->parseCurrentBlock("ordenespmi");
							$afil_pmi="[<strong>PMI</strong>]&nbsp;";
							//break;
						}

					$body->setCurrentBlock("familiares");
					$body->setVariable("familiar", $afil_pmi.$rs2->fields['nombre']."(DNI:".$rs2->fields['nro_documento'].")");
					$body->setVariable("familiartt", $afil_pmi.$rs2->fields['nombre']." DNI:".$rs2->fields['nro_documento']."| Nac:".$rs2->fields['fecha_nacimiento']);
					$body->parseCurrentBlock("familiares");
					$afil_pmi="";
					$rs2->MoveNext();
			}
		  } // Busqueda de parientes con PMI AGREGAR tipo de parentesc HIJO!

		  //Parseo las ultimas ordenes solicitadas el ultimo mes.
		  $consulta = "SELECT * FROM obra_social WHERE DATE(fecha) >= SUBDATE( CURDATE( ) , INTERVAL 90 DAY ) and id_afiliado=$id_afiliado ORDER BY fecha DESC LIMIT 0 , 10;";
		  $rs2=$db2->Execute($consulta);

		  
		  if (!($rs2 === false)) {
			while (!$rs2->EOF) {
						
							$pos=strpos($rs2->fields['observacion'], "[PMI]");
							$pos2=strpos($rs2->fields['observacion'], "[PMI-EMB]");
							if (($pos === false) && ($pos2 === false)){	//No es PMI solo filtrar 30 dias						
								$fechaPedido=$rs2->fields['fecha'];
								$fechaActual=

								$dia_nac=substr($fechaPedido, 8, 2); 
								$mes_nac=substr($fechaPedido, 5, 2); 
								$anno_nac=substr($fechaPedido, 0, 4);
 
								  // end date is 2008 Oct. 11 00:00:00
								  $_endDate = mktime(0,0,0,date("m"),date("d"),date("Y"));
								  // begin date is 2007 May 31 13:26:26
								  $_beginDate = mktime(0,0,0,$mes_nac,$dia_nac,$anno_nac);
								  $timestamp_diff= $_endDate-$_beginDate  ;
								  // how many days between those two date
								  $days_diff = ($timestamp_diff/86400)-1;
								  if ($days_diff<0) $days_diff=0;
								  if ($days_diff<30) { $sale=true;}else{$sale=false;}
								  


							} else {  //Mostrar por que es PMI
								$sale=true;
								//echo "Entro x PMI";
							}

					//if ($obrasocial && dar_pmi($rs2->fields['fecha_nacimiento'])) 						{ 
							//$PMI=true; 
							if ($sale){
								$body->setCurrentBlock("ordenes");
									$body->setVariable("ofecha",$rs2->fields['fecha']);
									$body->setVariable("onro_orden",$rs2->fields['nro_orden']);
									$body->setVariable("omonto-observacion",$rs2->fields['importe'].' '.$rs2->fields['observacion']);
								$body->parseCurrentBlock("ordenes");
								$body->setVariable("varpmi","true");
							}
							//var_dump($rs2);
							//break;
						//}
					$rs2->MoveNext();
			}
		  }

    	 } //--> Fin if con la consulta inicial a la DB si no falla muestra - Entro por que se encontro el afiliad

		}


     	$body->setVariable("fin","");	 

	$plantilla->setVariable("contenido", $body->get()); 
?>
