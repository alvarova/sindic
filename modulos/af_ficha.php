<?php
	require_once("./library/PEAR.php");
	require_once("./library/IT.php");
	include_once("./library/mod_qfunciones.php");	

/* UPDATE EMAIL
 * Verificar si se realiza Update/Insert de email para el afiliado indicado. Se parsea por el FORM _Get y _Post
 */
if (isset($_GET["update"])) {
		
		if ($_GET["update"]=='mail'){
		
			$idafiliado=$_POST['id_afiliado'];
			$email=$_POST['email'];
			$db= ADONewConnection();
			//$db->debug = true ;
			$result = $db->Connect("$dbhost", "$dbuname", "", "$dbname");
			$consulta = "SELECT * FROM email WHERE id_afiliado = '$idafiliado'";
			$rs=$db->Execute($consulta);
			if (!empty($idafiliado)){
				if ($db->Affected_Rows()>0){			  	
	 			  	$consulta="UPDATE `sindicatofarm`.`email` SET `direccion` = '$email' WHERE `email`.`id_afiliado` = '$idafiliado';";
	 			  	//*********** SETEO DEL LOGGER ***********
	 			  	$log->capturar($consulta);
				}
				else
				{
	 			  $consulta="INSERT INTO `sindicatofarm`.`email` (`id_email` , `id_afiliado` , `direccion` ) VALUES ( NULL , '$idafiliado', '$email');";
	 			  $log->capturar($consulta);
				}
			}

			$rs=$db->Execute($consulta);
			//var_dump($consulta);
			$campo="nro_documento";
			$criterio=$_POST["nro_documento"];
		}
}

/* <---------------- INICIO DE CARGA DE PLANTILLA  -------------------> */

$body= new HTML_Template_IT();
$body->loadTemplatefile("./modulos/af_ficha.tpl");


	
$selected="selected='selected'"; // Para dejar default el criterio de busqueda o lo ultimo que haya buscado
$itemsearched="s3";  // por default es s3=dni	

if (!($campo=="" || $criterio==""))
{
	
//Si el campo de busqueda es nombre, debo evaluar si parsean 1 o 2 palabras:
// 1 palabra = emplear metodo like
// 2 o mas  = emplear Match Against al no disponer de indexacion procedo a emular con like
	


if ($campo=="nombre")	{
	$itemsearched="s2";
	$criterios=explode(" ",$criterio);
	if (count($criterios)>1) {
		$c=0;
		$and="";
		$cadena="";
		foreach ($criterios as $palabra) {
			// (nombre LIKE '%palabra%' and nombre LIKE 'A%')
			
			$cadena.=$and." $campo LIKE '%".$palabra. "%'";
			if ($c==0) {$c=1; $and=" AND ";}
		}
		$postwhere=$cadena." ORDER BY id_afiliado DESC";
	}else{
		$postwhere="nombre LIKE '%$criterio%' ORDER BY id_afiliado DESC";
	}
} else {
	if ($campo=='id_afiliado') { $itemsearched="s1"; }
	$postwhere = "$campo = $criterio ORDER BY id_afiliado DESC limit 1";
}



$db = ADONewConnection();
$db2= ADONewConnection();
$result = $db->Connect("$dbhost", "$dbuname", "", "$dbname");
$result2 = $db2->Connect("$dbhost", "$dbuname", "", "$dbname");

//$consulta = "SELECT * FROM afiliados LEFT JOIN email ON afiliados.id_afiliado = email.id_afiliado WHERE afiliados.$campo = $criterio";

$consulta = "SELECT * FROM afiliados WHERE $postwhere";
//var_dump($consulta);
$rs=$db->Execute($consulta);
//$db->debug = true ;
//$db2->debug = true ;
if ($rs === false) {
			$aviso="No se encontraron registros segun el criterio de busqueda.";
}else{
			if ($db->Affected_Rows()<1) {$aviso2="No se encontraron registros segun el criterio de busqueda. Intente nuevamente o redefina la consulta"; $sinid=true;}else{$sinid=false;}
			
			// Busqueda de emails
			$consulta2= "SELECT * FROM email WHERE email.id_afiliado = ".$rs->fields['id_afiliado'];
			$rs2=$db->Execute($consulta2);

			if ($rs->fields['obra_social']=="1") {
							$body->setCurrentBlock("osocial");						
								$body->setVariable("Ordenes", "Ordenes");
								$body->setVariable("id_afiliado", $rs->fields['id_afiliado']);
								$obrasocial=true;
							$body->parseCurrentBlock("osocial");

			}

			if ($rs === false) { $email=""; } else { $email=$rs2->fields['direccion']; }
			
								$body->setVariable("col1", $rs->fields['id_afiliado']);
								$body->setVariable("col2", $rs->fields['nombre']);
								$body->setVariable("id_afiliado", $rs->fields['id_afiliado']);
								
								$body->setVariable("col3", $rs->fields['domicilio']); 
										if (envia_aviso($rs->fields['domicilio'])) { $aviso.=" Domicilio,";}

								$body->setVariable("col4", $rs->fields['localidad']);
										if (envia_aviso($rs->fields['localidad'])) { $aviso.=" localidad,";}

								$body->setVariable("col5", $rs->fields['nro_documento']);
										if (envia_aviso($rs->fields['nro_documento'])) { $aviso.=" DNI,";}

								$body->setVariable("col6", $rs->fields['estado_civil']);
										if (envia_aviso($rs->fields['estado_civil'])) { $aviso.=" Estado civil,";}

								$body->setVariable("col7", $email);
								$body->setVariable("col8", $rs->fields['nacionalidad']);
								$body->setVariable("col9", $rs->fields['estado_civil']);
								$body->setVariable("col10", $rs->fields['cuil']);
								$body->setVariable("col11", $rs->fields['cod_postal']);
										if (envia_aviso($rs->fields['cod_postal'])) { $aviso.=" Codigo postal,";}

								$body->setVariable("col12", $rs->fields['telefono']);
										if (envia_aviso($rs->fields['telefono'])) { $aviso.=" Telefono, ";}

								$body->setVariable("sexo", $rs->fields['sexo']);

								$body->setVariable("fecha_nacimiento", $rs->fields['fecha_nacimiento']);
										if (($rs->fields['fecha_nacimiento']=='0000-00-00')) { $aviso.=" Fecha de nacimiento,";}

								$fb=$rs->fields['fecha_baja']; 		$ab=explode('-',$fb);
								if ($ab[0]<1900) {
										$fb='Activo';
									}
								$body->setVariable("fecha_baja", $fb);
								$body->setVariable("motivo_baja", $rs->fields['motivo_baja']);

								$body->setVariable("sueldo", add_decimal($rs->fields['sueldo'],','));
								$body->setVariable("adicional1", add_decimal($rs->fields['adicional1'],','));
								$body->setVariable("adicional2", add_decimal($rs->fields['adicional2']));
								$body->setVariable("sindicato_ingreso", $rs->fields['sindicato_ingreso']);
								$body->setVariable("mutual_ingreso", $rs->fields['mutual_ingreso']);
								$body->setVariable("os_ingreso", $rs->fields['os_ingreso']);
								$body->setVariable("sexo", $rs->fields['sexo']);
								$body->setVariable("fecha_ingreso", $rs->fields['fecha_ingreso']);
								$body->setVariable("antiguedad", antiguedad($rs->fields['fecha_ingreso']));
								
								if ($rs->fields['efectivo']=="") {$aviso.=" Falta tipo de empleo,";}
								$body->setVariable("te".$rs->fields['efectivo'], "selected='selected'");
								
								if ($rs->fields['jornada_completa']=="") {$aviso.=" Falta tipo de jornada,";}
								$body->setVariable("tj".$rs->fields['jornada_completa'], "selected='selected'");
								
								$body->setVariable("mutual_cuota", add_decimal($rs->fields['mutual_cuota'],','));
								$body->setVariable("sindicato_cuota", add_decimal($rs->fields['sindicato_cuota']));



								$fb=$rs->fields['contrato_desde']; 		$ab=explode('-',$fb);
								if ($ab[0]<1900) $fb='Activo';
								if ((anio($rs->fields['contrato_desde'])>1900) || (anio($rs->fields['contrato_hasta'])>1900)){
								  
								}
								$body->setVariable("contrato_desde", $rs->fields['contrato_desde']);
								$body->setVariable("contrato_hasta", $rs->fields['contrato_hasta']);

								if ($rs->fields['sindicato']=='1') 	 { 	$body->setVariable("sindicatoc", 'green');   } else { $body->setVariable("sindicatod", 'disabled="disabled"');  }
								if ($rs->fields['obra_social']=='1') { 	$body->setVariable("osocialc", 'green');  	} else { $body->setVariable("osociald", 'disabled="disabled"');  }
								if ($rs->fields['mutual']=='1') 	 { 	$body->setVariable("mutualc", 'green');  	} else { $body->setVariable("mutuald", 'disabled="disabled"');  }
															

		//Beneficiario por fallecimiento
								$body->setVariable("dsnombre", $rs->fields['dsnombre']);
								$body->setVariable("dsdomicilio", $rs->fields['dsdomicilio']);
								$body->setVariable("dstelefono", $rs->fields['dstelefono']);
								$body->setVariable("dslocalidad", $rs->fields['dslocalidad']);
								$body->setVariable("dscod_postal", $rs->fields['dscod_postal']);
								$body->setVariable("dsnacion", $rs->fields['dsnacion']);
								$body->setVariable("dsnro_documento", $rs->fields['dsnro_documento']);
								$body->setVariable("dsfecha_nacimiento", $rs->fields['dsfecha_nacimiento']);
								$body->setVariable("firma_formulario", $rs->fields['firma_formulario']);
								
		// **********************						
		// Busqueda de categorias
		// **********************
			$consulta2= "SELECT * FROM categorias";
			$rs2=$db2->Execute($consulta2);
			if ($rs2 === false) { $categorias="--sin categorias--"; } 
				else { 
					$afilcat=$rs->fields['id_categoria'];  //Categoria del empleado
						while (!$rs2->EOF) {
							$body->setCurrentBlock("categorias");	
								$body->setVariable("id_categoria", $rs2->fields['id_afiliado']);
								$body->setVariable("descripcion", $rs2->fields['descripcion']);
								if ($rs2->fields['id_categoria']==$afilcat) $body->setVariable("selected", "selected='selected'");
							$body->ParseCurrentBlock("categorias");
							$rs2->MoveNext();	
						}					
					$email=$rs2->fields['id_categoria']; 
				}
										
		//Empleado para las consulta de empleador y flirs.a Cargo
			$idfa=$rs->fields['id_farmacia'];
			$idaf=$rs->fields['id_afiliado'];
		//*******************************
		//**     Datos del empleador   **
		//*******************************

		$consulta = "SELECT * FROM farmacias WHERE id_farmacia = $idfa";
		//var_dump($consulta);
		$rs2=$db2->Execute($consulta);
		if (($rs === false) && ($aviso=="")) {
		  $aviso="No se encontraron datos de relacion Empleador - Afiliado, [Verificar]";
		}
				
		$body->setVariable("fcuit", $rs2->fields['cuit']);
		$body->setVariable("frazon_social", $rs2->fields['razon_social']);
		$body->setVariable("fnombre_titular", $rs2->fields['nombre_titular']);
		$body->setVariable("fdomicilio", $rs2->fields['domicilio']);
		$body->setVariable("ftelefono", $rs2->fields['telefono']);
		$body->setVariable("ffax", $rs2->fields['fax']);
		$body->setVariable("femail", $rs2->fields['email']);
		$body->setVariable("flocalidad", $rs2->fields['localidad']);
		$body->setVariable("fcod_postal", $rs2->fields['cod_postal']);
		$body->setVariable("fiva", $rs2->fields['iva']);
		$body->setVariable("fobservaciones", $rs2->fields['observaciones']);
			
								
		//*******************************
		//**     Familiar a Cargo      **
		//*******************************
		$idaf=$rs->fields['id_afiliado'];

		$consulta = "SELECT * FROM 
							   familiares 
							   LEFT JOIN parientes
										ON familiares.id_parentesco=parientes.id_parentesco
							   WHERE id_afiliado = $idaf";
		$rs=$db->Execute($consulta);
		//$db->debug = true ;

		  if (!($rs === false)) {
			while (!$rs->EOF) {
							$body->setCurrentBlock("familiar");						
								
								if ($obrasocial && dar_pmi($rs->fields['fecha_nacimiento'])) { $PMI="[PMI]"; } else { $PMI="";}
								$body->setVariable("idfamiliar",$rs->fields['id_familiar']);	
								$body->setVariable("fparentesco",$rs->fields['parentesco']);	
								//$rs->fields['id_afiliado']);	
								$body->setVariable("fnombre", $rs->fields['nombre']);	
								$body->setVariable("ffecha_nacimiento", $rs->fields['fecha_nacimiento']);
								$body->setVariable("fedad", antiguedad($rs->fields['fecha_nacimiento']).$PMI);	
								$body->setVariable("fdni", $rs->fields['nro_documento']);	
								$body->setVariable("fsexo", $rs->fields['sexo']);	
								$body->setVariable("ffecha_alta", $rs->fields['fecha_alta']);	
								$body->setVariable("fincapacidad", $rs->fields['incapacidad']);
								
								
							$body->parseCurrentBlock("familiar");

			$rs->MoveNext();
			}
		  }
		}
} 	

$body->setVariable($itemsearched, $selected); //Seteo el campo de busqueda
$body->setVariable("criterio", $criterio);    // parseo el criterio de busqueda

	if ($aviso!=""){
		$body->setCurrentBlock("aviso");	
		if ($sinid) $aviso=$aviso2."-";
		$body->setVariable("aviso", "Atenci&oacute;n, complete los datos faltantes:<br/>&nbsp;&nbsp;&nbsp;-".substr($aviso, 0, -1).".");

		$body->parseCurrentBlock("aviso");

	}

    $body->setVariable("fin","");
	$plantilla->setVariable("contenido", $body->get()); 
?>
