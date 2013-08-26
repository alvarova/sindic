<?php
	require_once("./library/PEAR.php");
	require_once("./library/IT.php");
      
    	$body= new HTML_Template_IT();
    	$body->loadTemplatefile("./modulos/af_busca.tpl");



$db = ADONewConnection();
//$db->debug = false ;
$result = $db->Connect("$dbhost", "$dbuname", "", "$dbname");




$c=0;
//ID_AFILIAD	ID_FARMACI	ID_CATEGOR,C,5	ID_PARENTE	TIPO_DOCUM,C,3	NRO_DOCUME,C,8	NOMBRE,C,30	SEXO,C,1	DOMICILIO,C,25	LOCALIDAD,C,30	COD_POSTAL	NACIONALID,C,15	TELEFONO,C,12	FECHA_NACI,D	ESTADO_CIV,C,20	CUIL,C,13
//var_dump($criterio."-".$campo);

	if (($criterio=="") && ($campo=="")) {
		
		if ($enviado=="") {   //No se consulto ni se envio todavia el formulario, aviso que no hay datos
				$body->setVariable("error",'<div class="notice warning"><span style="display: inline-block;" class="icon medium" data-icon="!">
				</span>Atención: Faltan datos para realizar la consulta
				<a style="display: inline-block;" href="#close" class="icon close" data-icon="x">
				</a></div>');	 
		} else {  //Se envió el formulario igualmente vacio para consultar la nomina completa de afiliados

				$tAfiliados="C:\\xampp\\htdocs\\sindicato\\Data\\afiliados.DBF";
				$dsn = "Driver={Microsoft Visual FoxPro Driver};SourceType=DBF;SourceDB=$tAfiliados;Exclusive=NO;collate=Machine;NULL=NO;DELETED=NO;BACKGROUNDFETCH=NO;";
				$rs=odbc_connect($dsn,"","");
				
				$result = odbc_exec ($rs, "select * from $tAfiliados ".$set. 'order by nombre');
				//var_dump($result);
					while($array = odbc_fetch_array($result))
						{
							$sMbaja = trim($array['motivo_baja']);
							$sSin = trim($array['sindicato_ingreso']);
							//print_r(strlen($array['motivo_baja'])." ");
							$sNomAfil=trim($array['nombre']);
							$sDNI=trim($array['nro_documento']);
						  if ((strlen($sNomAfil)>3) && (strlen($sDNI)>3))	{   //consulta si existe nombre afiliado - falla en DBF por afiliados fantasmas
							//if ((strlen($sMbaja)<3) && (strlen($sSin)>2)){
							$body->setCurrentBlock("bloque");
							     
								$body->setVariable("col1", $array['id_afiliado']);
								$body->setVariable("col2", $array['nombre']);
								$body->setVariable("col3", $array['domicilio']);
								$body->setVariable("col4", $array['localidad']);  //localidad
								//$body->setVariable("col4", $array['motivo_baja']);  //localidad
								$body->setVariable("col5", $array['nro_documento']);
								$body->setVariable("col6", $array['estado_civil']);
								$body->setVariable("col7", $array['id_afiliado']);
								$c++;
							$body->parseCurrentBlock("bloque");
							//}
						 } //Cierra consulta si existe nombre afiliado	
						}
		
		}
		
	}else{ //Se ingresaron datos para consultas determinadas en los formularios
		
		$dsn = "Driver={Microsoft Visual FoxPro Driver};SourceType=DBF;SourceDB=C:\\xampp\\htdocs\\sindicato\\Data\\afiliados.DBF;Exclusive=NO;collate=Machine;NULL=NO;DELETED=NO;BACKGROUNDFETCH=NO;";
		$rs=odbc_connect($dsn,"","");



		switch ($campo) {
			case 1:
				$field='id_afiliado';
				$set= ' where '.$field.'='.$criterio;
				break;
			case 2:
				$field='nombre';
				$set= " where ".$field." like '%".strtoupper($criterio)."%'";
				break;
			case 3:
				$field='nro_documento';
				$set= ' where '.$field.'='.$criterio;
				
				$consulta = "SELECT * FROM afiliados ".$set;
								
				
				break;
			case 4:
				$field='ID_FARMACI';
				$set= ' where '.$field.'='.$criterio;
				break;				
			default:
				//$field='NRO_DOCUME';	//ID_AFILIAD  NOMBRE   ID_FARMACI
				$set= '';;
		}
		
	
		
		//var_dump($set);
		//$result = odbc_exec ($rs, "select * from C:\\xampp\\htdocs\\sindicato\\Data\\afiliados.DBF ".$set);
$rs=$db->Execute($consulta);
//$db->debug = true ;

  if ($rs === false) die("Fallo en consulta...");
    //var_dump($rs);
    while (!$rs->EOF) {
					$body->setCurrentBlock("bloque");
					//print_r($array);
						$body->setVariable("col1", $rs->fields['id_afiliado']);
						$body->setVariable("col2", $rs->fields['nombre']);
						$body->setVariable("col3", $rs->fields['domicilio']);
						$body->setVariable("col4", $rs->fields['localidad']);
						$body->setVariable("col5", $rs->fields['nro_documento']);
						$body->setVariable("col6", $rs->fields['estado_civil']);
						$body->setVariable("col7", '<a href="./index.php?ac=af_modifica&afil='.$rs->fields['id_afiliado'].'"><span style="display: inline-block;" class="icon gray" data-icon="7"></span></a>');
						$c++;
					$body->parseCurrentBlock("bloque");

    $rs->MoveNext();
    }


	
		
		
	}

	/*


	*/
//var_dump($c);

    	$body->setVariable("items", "[Resultados encontrados:".$c."]");
     	$body->setVariable("fin","");	 
	$plantilla->setVariable("contenido", $body->get()); 
?>
