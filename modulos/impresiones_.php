<?php
header("Content-Type: text/html; charset=iso-8859-1 ");
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
$vertodos=" AND (`afiliados`.fecha_baja =  '0000-00-00') ";

$body= new HTML_Template_IT();
$body->loadTemplatefile("./modulos/impresiones.tpl");


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

		
		require_once('./library/RemitoPDF.php');

		if (isset($_POST['interior'])) { 
			$interior = " AND (`farmacias`.`cod_postal` <> '3000') ";
		} else { $interior = "";}
		if (isset($_POST['rotulo'])) {  $rotulo = true; } else { $rotulo = false;}

		//var_dump($_POST['interior']);
		
		$sql="SELECT * FROM `afiliados`  LEFT OUTER JOIN `farmacias` ON `afiliados`.`id_farmacia` = `farmacias`.`id_farmacia`  WHERE (`fecha_nacimiento` LIKE '%%%%-".$_POST["mes"]."-%%' AND `sindicato`='1' ) ".$vertodos.$interior." ORDER BY  `farmacias`.`localidad` ASC ";
		$db = ADONewConnection();
		$result = $db->Connect("$dbhost", "$dbuname", "", "$dbname");
		$rs=$db->Execute($sql);

		$listadopdf = new RemitoPDF('Regalo de CumpleaÑos', $rotulo);

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



} else if (((isset($_POST['lista']))&&($_POST['filtro']=='1'))) {
	require_once('./library/RemitoPDF.php');
	if (isset($_POST['rotulo'])) {  $rotulo = true; } else { $rotulo = false;}
	$lista=$_POST['lista'];
 //Hacer la impresion de los elementos que pasan por array
	$consulta = "SELECT `afiliados`.id_afiliado, `afiliados`.nombre, `afiliados`.id_farmacia, `afiliados`.sindicato_ingreso, `farmacias`.localidad, `farmacias`.razon_social,`farmacias`.domicilio , `afiliados`.sindicato_ingreso 
					FROM  farmacias    LEFT JOIN afiliados	ON  `afiliados`.id_farmacia =`farmacias`.id_farmacia
 					WHERE  (`afiliados`.sindicato = 1)  AND (`afiliados`.fecha_baja =  '0000-00-00')  AND (`afiliados`.id_afiliado in ( ".$lista." ))";

		$db = ADONewConnection();
		$result = $db->Connect("$dbhost", "$dbuname", "", "$dbname");
		$rs=$db->Execute($consulta);

		$listadopdf = new RemitoPDF($_POST['titulo'], $rotulo);
//var_dump($consulta);
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
					substr(html_entity_decode($rs->fields['nombre']),0,22), 					$rs->fields['fecha_nacimiento'],
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

}



$consulta = "SELECT `afiliados`.id_afiliado, `afiliados`.nombre, `afiliados`.id_farmacia, `afiliados`.sindicato_ingreso, `farmacias`.localidad, `farmacias`.razon_social, `afiliados`.sindicato_ingreso 
					FROM  farmacias    LEFT JOIN afiliados	ON  `afiliados`.id_farmacia =`farmacias`.id_farmacia
 					WHERE  (`afiliados`.sindicato = 1)  AND (`afiliados`.fecha_baja =  '0000-00-00')  ";

$db = ADONewConnection();
$result = $db->Connect("$dbhost", "$dbuname", "", "$dbname");
$rs=$db->Execute($consulta);	
$cantidadafiliados=0;
				
while (!$rs->EOF) {
				$cantidadafiliados++;
				$body->setCurrentBlock("listado");
					$body->setVariable("nro", $cantidadafiliados);                   //Col 4
					$body->setVariable("id_afiliado", $rs->fields['0']);                   //Col 4
					$body->setVariable("nombre", $rs->fields['1']);                   //Col 4
					$body->setVariable("razon_social", $rs->fields['5']);                    //Col 5*
					$body->setVariable("localidad", $rs->fields['4']);            //Col 6*
					$body->setVariable("fechaalta", $rs->fields['6']);

				$body->parseCurrentBlock("listado");
				
				$rs->MoveNext();

}     	
$body->setVariable("totallista", $cantidadafiliados);

$body->setVariable("fin","");
$plantilla->setVariable("contenido", $body->get()); 
?>