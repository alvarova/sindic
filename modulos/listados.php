<?php

/*Listar afiliados agrupados por edad, da la cantidad de afiliados con 30 añós, 40 años etc.. ideal estadisticas:
SELECT (
YEAR( CURDATE( ) ) - YEAR( fecha_nacimiento )
) - ( RIGHT( CURDATE( ) , 5 ) < RIGHT( fecha_nacimiento, 5 ) ) AS edad, COUNT( * ) AS cantidad
FROM afiliados
GROUP BY edad
ORDER BY  `edad` ASC 
LIMIT 0 , 30


Filtro afiliados por rango de edad
SELECT *  FROM afiliados 
WHERE ( ( YEAR( CURDATE( ) ) - YEAR( fecha_nacimiento ) ) - ( RIGHT( CURDATE( ) , 5 ) < RIGHT( fecha_nacimiento, 5 ) ) BETWEEN 30  AND 40 )
*/

require_once("./library/PEAR.php");
require_once("./library/IT.php");

	$mes['01']='Enero';$mes['02']='Febrero';$mes['03']='Marzo';$mes['04']='Abril';$mes['05']='Mayo';$mes['06']='Junio';
	$mes['07']='Julio';$mes['08']='Agosto';$mes['09']='Septiembre';$mes['10']='Octubre';$mes['11']='Noviembre';$mes['12']='Diciembre';
	//error_reporting(0);
    $parentesco="";
    $ocultalista=true;  
   	$body= new HTML_Template_IT();
   	$body->loadTemplatefile("./modulos/listados.tpl");
   	

   	/*
   	 *  Cargando rango de meses para el dropdown de cumpleaños
   	 */
	   	$inc=-3;
	   	$ss="";
    	for ($i=0; $i<12; $i++){
	    	$mes_sig  = mktime(0, 0, 0, date("m")+$inc+$i, date("d"),   date("Y"));
    		if ($inc+$i!=0) {
	    		$mes_nom = date("m-Y", $mes_sig);
	    		$m=explode("-",$mes_nom);
	    		$mes_nom=$mes[current($m)].'-'.end($m);
	    	}else{
		    	$mes_nom = "--Actual--";
		    	$ss="selected='selected'";
	    	}
	    	$body->setCurrentBlock("meslista");
	    		$body->setVariable("mesval", date("m",$mes_sig));	
	    		$body->setVariable("mesnombre", $mes_nom);	
	    		$body->setVariable("ss", $ss);	
	    	$body->parseCurrentBlock("meslista");
	    	$ss='';
    	}



    /* FILTRO 1 - Listar Afiliados segun el cumpleaños del mes en curso o seleccionado (solo PDF)
     * Comenzar con la detección del GENERADOR
     * postea FILTRO y MES  vuelco planilla con los cumple de los afiliados
     * postea FILTRO y TIPO afiliado generamos listado segun filtro de categorias
     */
    if ((isset($_POST['mes']))&&($_POST['filtro']=='0')) {


    	require_once('./library/ListadoPDF.php');

		$sql="SELECT * FROM `afiliados`  LEFT OUTER JOIN `farmacias` ON `afiliados`.`id_farmacia` = `farmacias`.`id_farmacia`  WHERE (`fecha_nacimiento` LIKE '%%%%-".$_POST["mes"]."-%%' AND `sindicato`='1' ) ".$vertodos." ORDER BY  `farmacias`.`razon_social` ASC ";
		$db = ADONewConnection();
		$result = $db->Connect("$dbhost", "$dbuname", "", "$dbname");
		$rs=$db->Execute($sql);

		
		$listadopdf = new ListadoPDF('Afiliados por natalicio - Mes '.$mes[$_POST["mes"]]);

		$w = array(42, 20, 34, 38, 21, 25, 19); //Ancho de las columnas
		$header = array('Nombre', 'Nacimiento', 'Farmacia', 'Direccion', 'Localidad', 'Tel.Farmacia','Alta Sindic');
	
			for($i=0;$i<count($header);$i++) {  //Cargo los rotulos de las columnas y sus anchos en el objeto
				$listadopdf->agregar_rotulo($header[$i]);
				$listadopdf->agregar_posicion($w[$i]);
			}

			//Inicializo la cabecera e imprimo los rotulos y defino las columnas
			$listadopdf->inicializar(); //Parsea cabezera completa
			//Defino la alineacion del texto * verificar
			$alinea = array('L','L','C','C','R','R','C');  //Como alinear los elementos en las columnas
			$listadopdf->alinea_columnas($alinea);
			$fuente = array('8','8','8','8','7','7','7','8'); //No importa que sobren elementos
			$listadopdf->altura_fuente_columnas($fuente);
			// Cargar los datos de cada columna e ir volcando al PDF
			while (!$rs->EOF) 
			{
					$arreglo = array(
						substr(html_entity_decode($rs->fields['nombre']),0,22),
						$rs->fields['fecha_nacimiento'],
						substr(html_entity_decode($rs->fields['razon_social']),0,19)."(".$rs->fields['id_farmacia'].")",
						$rs->fields['domicilio'],
						substr(html_entity_decode($rs->fields['localidad']),0,14),
						$rs->fields['telefono'],
						substr( $rs->fields['sindicato_ingreso'],0,11)
						);
					$listadopdf->cargaelemento($arreglo); //Parsea cabezera completa
					$rs->MoveNext();
			}
			//Imprimo pie y cierro PDF
		$listadopdf->finalizar();


	/*  FILTRO 2 al 12 - Listar Afiliados segun las categorias (Planilla o PDF)
	 *
	 *
	 */
    } else if ((isset($_POST['tipo']))&&(isset($_POST['filtro']))) {
    	
    	if ($_POST['tipo']!=1) {$vertodos="";} else { $vertodos=" AND (`afiliados`.fecha_baja =  '0000-00-00') ";}

    	if ($_POST['filtro']<12){
				// Títulos de las columnas
				$header = array('Nombre', 'Nacimiento', 'Farmacia', 'Direccion', 'Localidad', 'Tel.Farmacia','Alta Sindic');

				$sql ="select * from categorias";		
				$db = ADONewConnection();
				$result = $db->Connect("$dbhost", "$dbuname", "", "$dbname");
				$rs=$db->Execute($sql);
				while (!$rs->EOF) {
		    			$categoria[$rs->fields['id_categoria']] = $rs->fields['descripcion'];
		    			$rs->MoveNext();
		    	}

		    	$afiltro="";
		    	$filtroaf="";
		    	if (isset($_POST['afiliacion'])) { $afiliacion = $_POST['afiliacion'];} else {$afiliacion = 0;}
		    	if ($afiliacion==1) { $afiltro=' AND (`afiliados`.sindicato = 1) '; $filtroaf=' Afiliados';  }
		    	else if ($afiliacion==2) { $afiltro=' AND (`afiliados`.sindicato = 0) '; $filtroaf=' NO Afiliados'; }

				$sql="SELECT DISTINCT (`afiliados`.nro_documento),  `afiliados`.id_farmacia,  `afiliados`.nombre,  `farmacias`.razon_social,  `afiliados`.localidad,  `afiliados`.telefono, `farmacias`.telefono, `afiliados`.`id_afiliado`  FROM  `afiliados` LEFT OUTER JOIN  `farmacias` ON  `afiliados`.`id_farmacia` =  `farmacias`.`id_farmacia` WHERE (id_categoria =  '".$_POST['filtro']."'".$vertodos.$afiltro." ) ORDER BY  `afiliados`.`localidad` ASC,`farmacias`.`razon_social`,`afiliados`.`nombre` ASC ";
				$rs=$db->Execute($sql);
				
				//SELECT distinct (nro_documento), nombre FROM `afiliados` WHERE (id_categoria = '7' and fecha_baja = '0000-00-00') order by id_afiliado desc
		    	//**SELECT DISTINCT (`afiliados`.nro_documento),  `afiliados`.id_farmacia,  `afiliados`.nombre,  `farmacias`.razon_social FROM  `afiliados` LEFT OUTER JOIN  `farmacias` ON  `afiliados`.`id_farmacia` =  `farmacias`.`id_farmacia` WHERE (id_categoria =  '10' AND (fecha_baja =  '0000-00-00')) ORDER BY  `afiliados`.`id_farmacia` DESC 
		    	

		    	if (isset($_POST['pdf'])) {
		    		//require_once('./library/fpdf.php');
		    		require_once('./library/ListadoPDF.php');
		    		
					$listadopdf = new ListadoPDF("Volcado de Afiliados para CATEGORIA ".$categoria[$_POST['filtro']]);

					$w = array(15, 40, 40, 28, 25, 25);
					$header = array('DNI', 'Nombre', 'Farmacia', 'Localidad', 'Telefono', 'Tel.Farmacia');
					
					for($i=0;$i<count($header);$i++) {  //Cargo los rotulos de las columnas y sus anchos en el objeto
						$listadopdf->agregar_rotulo($header[$i]);
						$listadopdf->agregar_posicion($w[$i]);
					}

					//Inicializo la cabecera e imprimo los rotulos y defino las columnas
					$listadopdf->inicializar(); //Parsea cabezera completa
					//Defino la alineacion del texto * verificar
					$alinea = array('L','L','R','R','R','R');  //Como alinear los elementos en las columnas
					$listadopdf->alinea_columnas($alinea);
					$fuente = array('7','8','8','8','7','7','7','7');
					$listadopdf->altura_fuente_columnas($fuente);
					// Cargar los datos de cada columna e ir volcando al PDF
					while (!$rs->EOF) 
					{
							$arreglo = array(
								substr(html_entity_decode($rs->fields['nro_documento']),0,22),
								substr(html_entity_decode($rs->fields['nombre']),0,22),
								substr(html_entity_decode($rs->fields['razon_social']),0,19),
								substr(html_entity_decode($rs->fields['localidad']),0,14),
								(html_entity_decode($rs->fields['5'])),
								(html_entity_decode($rs->fields['6']))
								);
							$listadopdf->cargaelemento($arreglo); //Parsea cabezera completa
							$rs->MoveNext();
					}
					//Imprimo pie y cierro PDF
					$listadopdf->finalizar();


			/*
			 *
			 *
			 */
		    	}else{
		    		
		    		$cantidadafiliados=0;
		    		$body->setVariable("titulo",  "Volcado de personal para CATEGORIA ".$categoria[$_POST['filtro']].$filtroaf);
		    		while (!$rs->EOF) {
		    			$body->setCurrentBlock("listado");
				    		$body->setVariable("nro_documento", $rs->fields['nro_documento']);	
				    		if ($rs->fields['nro_documento']=="") {
				    			$sdni_abre="<a href='#' class='veraf_sindni' id='".$rs->fields['id_afiliado']."'>";
				    			$sdni_cierra="</a>";
				    		}else { $sdni_cierra=""; $sdni_abre="";}
				    		$body->setVariable("nombre", $sdni_abre.$rs->fields['nombre'].$sdni_cierra);	
				    		$body->setVariable("razon_social", $rs->fields['razon_social']);
				    		$body->setVariable("localidad", $rs->fields['localidad']);
				    		$body->setVariable("telefono", $rs->fields['5']);
				    		$body->setVariable("telfarmacia", $rs->fields['6']);
				    		$body->setVariable("tooltip", $rs->fields['telefono']);
				    		//var_dump($rs);
			    		$body->parseCurrentBlock("listado");
			    		$cantidadafiliados++;
		    			$rs->MoveNext();
		    			$ocultalista=false;
		    		}
					$body->setVariable("totallista", $cantidadafiliados);    		
					$body->setVariable("encabezado-tabla", "<th>DNI</th><th>Nombre</th><th>Farmacia</th><th>Localidad</th><th>Telefono</th><th>Tel.Farmacia</th>");    		
		    	}
     	}else{  //es mayor a 12, por lo tanto es otro listado fuera de las categorias
     	//Momentaneamente solo para fliares. agregar condicion para determinar el tipo de lista
     	$where=false;
     	/* 
     	 * Filtro FAMILIARES
     	 * Falta unificar procedimiento, para no repetir código.
     	*/
     	//Seteo de los filtros
     	// FILTRO AFILIADOS por FILIACION
     	if (isset($_POST['afiliacion'])) {
			switch ($_POST['afiliacion']) {
			    case '1':
			    	$where=true;
			        $af_afiliacion=" `afiliados`.`sindicato`='1' "; //Son afiliados
			        $w[]=$af_afiliacion;
			        break;
			    case '2':
			    	$where=true;
			        $af_afiliacion=" `afiliados`.`sindicato`='0' "; //No lo son
			        $w[]=$af_afiliacion;
			        break;
			    default:
			       $af_afiliacion="";
			}
			
     	}
     	// FILTRO FLIARES/AFILIADOS POR SEXO
     	if (($_POST['filtro']==21)||($_POST['filtro']==22)) { $quien='afiliados'; } else {$quien='familiares';}
     	
     	if (isset($_POST['sexo'])) {
			switch ($_POST['sexo']) {
			    case '1':
			    	$where=true;
			        $fam_sexo=" `$quien`.`sexo`='M' "; //Son afiliados
					$w[]=$fam_sexo;
			        break;
			    case '2':
			    	$where=true;
			        $fam_sexo=" `$quien`.`sexo`='F' "; //No lo son
        			$w[]=$fam_sexo;
			        break;
			    default:
			       $fam_sexo="";
			}

     	}

     	

     	//FILTRO POR RANGO DE EDAD - entra por que esta definido de 0 a 100
     	if (isset($_POST['edad1']) && isset($_POST['edad2'])) {
     		$where=true;
     		if ($_POST['edad1']>$_POST['edad2']) {
     			$edad_ini=$_POST['edad2'];
     			$edad_fin=$_POST['edad1'];

     		}else {
     			$edad_ini=$_POST['edad1'];
     			$edad_fin=$_POST['edad2'];
     		
     		}
     		$fam_edad="( ( YEAR( CURDATE( ) ) - YEAR( `$quien`.fecha_nacimiento ) ) - ( RIGHT( CURDATE( ) , 5 ) < RIGHT( `$quien`.fecha_nacimiento, 5 ) ) >= ".$edad_ini." ) AND ( ( YEAR( CURDATE( ) ) - YEAR( `$quien`.fecha_nacimiento ) ) - ( RIGHT( CURDATE( ) , 5 ) < RIGHT( `$quien`.fecha_nacimiento, 5 ) ) <= ".$edad_fin." ) ";
     	    if (($edad_ini==0) && ($edad_fin==100)) {
     	    	$w[]= " true ";
     	    }else{
     	    	$w[]=$fam_edad;
     		}

     	}

     	if ($where) { 
     		if ((count($w)<2) && ($edad_ini==0) && ($edad_fin==100)) { 
     			$where=" WHERE true ";  
     		}else{
     			$where=" WHERE  ".implode(" AND ", $w);
     		}
     		//' WHERE '.$af_afiliacion." AND ".$fam_sexo." AND ".$fam_edad;
     	}

		$db = ADONewConnection();
		$result = $db->Connect("$dbhost", "$dbuname", "", "$dbname");


		if ($_POST['filtro']==20)	{	
		     	$sql='select * from parientes';
				$rs=$db->Execute($sql);	
				//Arreglo con los ID que tiene  asignado el usr
				if ($rs === false) die("Fallo consultando parentescos.".$sql);
				$c=0;
				while (!$rs->EOF) {	
						$parentesco[$rs->fields['id_parentesco']]=$rs->fields['parentesco'];		
						$rs->MoveNext();
						$c++;
				}


		     	$cantidadafiliados=0;
		     	//DATE(fecha) >= SUBDATE( CURDATE( ) , INTERVAL 90 DAY )
				$consulta = "SELECT `familiares`.id_familiar, `familiares`.id_afiliado,`familiares`.id_parentesco, `familiares`.nro_documento, `familiares`.nombre,`afiliados`.localidad, `afiliados`.telefono, `familiares`.fecha_nacimiento, `familiares`.sexo     FROM  familiares    LEFT JOIN parientes		ON familiares.id_parentesco=parientes.id_parentesco	LEFT JOIN afiliados ON familiares.id_afiliado=afiliados.id_afiliado ".$where.$vertodos." ORDER BY `familiares`.fecha_nacimiento ";
				$textocontador="familiares de afiliados listados ";
				$encabezadotabla="<th>DNI</th><th>Nombre</th><th>Edad</th><th>Localidad</th><th>Telefono</th><th>Fecha Nacimiento</th><th>Sexo</th>";
				$rs=$db->Execute($consulta);
				while (!$rs->EOF) {
				
					$body->setCurrentBlock("listado_fam");
				
				    		$body->setVariable("col1", $rs->fields['3']);	         //Col 1
								//verifico si dispongo de DNI sino pongo enlace para ver ficha (ES UN ERROR)    		
				    			$sdni_abre="<a href='#' class='veraf_sindni' id='".$rs->fields['1']."'>";
				    			$sdni_cierra="</a>";
				    		
				    		
				    		$body->setVariable("col2", $sdni_abre.$rs->fields['4'].$sdni_cierra); //Col 2	
				    		
				    		$idp=$parentesco[$rs->fields['id_parentesco']];
				    		
				    		//var_dump($idp);
				    		require_once("./library/mod_qfunciones.php");
				    		$body->setVariable("col3", antiguedad($rs->fields['7']));      //Col 3
				    		$body->setVariable("col4", $rs->fields['5']);                  //Col 4
				    		$body->setVariable("col5", $rs->fields['6']);                  //Col 5*
				    		$body->setVariable("col6", $rs->fields['7']);           		//Col 6*
				    		$body->setVariable("col7", $rs->fields['8']);                   //Col 7
				    		$body->setVariable("tooltip", '5');
				    		//var_dump($rs);
			    		$body->parseCurrentBlock("listado_fam");
			    		$cantidadafiliados++;
		    			$rs->MoveNext();
		    			$ocultalista=false;
		    		}

		} elseif (($_POST['filtro']==21)||($_POST['filtro']==22)) {
				if ($_POST['filtro']==21) { 
					$filtrolista=	" and `afiliados`.sindicato = 1"; 
					//if ($_POST['afiliacion']==1) { $where.=" and (`afiliados`.sindicato_ingreso != '0000-00-00') "; }
					//if ($_POST['afiliacion']==2) { $where.=" and (`afiliados`.sindicato_ingreso = '0000-00-00') "; }
				} 
				 else 
				{ 
					$filtrolista=	" and `afiliados`.obra_social = 1"; 
					//if ($_POST['afiliacionos']==1) { $where.=" and (`afiliados`.os_ingreso != '0000-00-00') "; }
					//if ($_POST['afiliacionos']==2) { $where.=" and (`afiliados`.os_ingreso = '0000-00-00') "; }
				}
				
				

				$consulta = "SELECT `afiliados`.id_afiliado, `afiliados`.nro_documento, `afiliados`.nombre, `afiliados`.fecha_nacimiento, `farmacias`.localidad, `farmacias`.razon_social, `farmacias`.domicilio, `afiliados`.sexo     FROM  afiliados    LEFT JOIN farmacias		ON afiliados.id_farmacia=farmacias.id_farmacia	 ".$where.$vertodos." ORDER BY `farmacias`.cod_postal, `farmacias`.razon_social  ";			
				 //var_dump($consulta);
				
				$textocontador=" afiliados listados ";
				$encabezadotabla="<th>DNI</th><th>Nombre</th><th>Edad</th><th>Localidad</th><th>Farmacia</th><th>Domicilio</th><th>Sexo</th>";
				$rs=$db->Execute($consulta);
				while (!$rs->EOF) {
				
					$body->setCurrentBlock("listado_fam");
				
				    		$body->setVariable("col1", $rs->fields['1']);	         //Col 1
								//verifico si dispongo de DNI sino pongo enlace para ver ficha (ES UN ERROR)    		
				    			$sdni_abre="<a href='#' class='veraf_sindni' id='".$rs->fields['0']."'>";
				    			$sdni_cierra="</a>";
				    		
				    		
				    		$body->setVariable("col2", $sdni_abre.$rs->fields['2'].$sdni_cierra); //Col 2	
				    		
				    		$idp=$parentesco[$rs->fields['id_parentesco']];
				    		
				    		//var_dump($idp);
				    		require_once("./library/mod_qfunciones.php");
				    		$body->setVariable("col3", antiguedad($rs->fields['3']));      //Col 3
				    		$body->setVariable("col4", $rs->fields['4']);                  //Col 4
				    		$body->setVariable("col5", $rs->fields['5']);                  //Col 5*
				    		$body->setVariable("col6", $rs->fields['6']);           		//Col 6*
				    		$body->setVariable("col7", $rs->fields['7']);                   //Col 7
				    		$body->setVariable("tooltip", '5');
				    		//var_dump($rs);
			    		$body->parseCurrentBlock("listado_fam");
			    		$cantidadafiliados++;
		    			$rs->MoveNext();
		    			$ocultalista=false;
		    		}
		} 




			$body->setVariable("totallista", $textocontador.$cantidadafiliados);
			$body->setVariable("encabezado-tabla", $encabezadotabla);

     } //Cierra ELSE general



	}
    if ($ocultalista) {
    	$body->setVariable("listado","$('#listado').hide();"); 
    }
    $body->setVariable("fin","");
	$plantilla->setVariable("contenido", $body->get()); 
?>