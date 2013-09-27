<?php
    //Caja es la clase, define todos procedimientos
    include_once("./modulos/AdministraAportes.php");

	$body= new HTML_Template_IT();
	$body->loadTemplatefile("./modulos/fa_aportes.tpl");
	$idfarmacia="";
	//instanciamos la clase AdministrarCaja
	if (isset($_POST['idfarmacia'])) {

		$afiliados = new Afiliado();
		$liquidaMes=0;
		$idfarmacia=$_POST['idfarmacia'];

    	$listado=$afiliados->farmacia($idfarmacia);
    	foreach ($listado as $item) {
    		if ($item['fecha_baja']=="0000-00-00") {
				$body->setCurrentBlock("listado");
					if ($item['nro_documento']=="") {
						$dni="<a href='' class='veraf_sindni' id='".$item['id_afiliado']."'>[ver]</a>";
					}else { $dni=$item['nro_documento']; }
					$body->setVariable("dni", $dni);
					$body->setVariable("nombre", $item['nombre']);
					$body->setVariable("fecha_ingreso", $item['fecha_ingreso']);
					$body->setVariable("categoria", $item['basico']);
					$calAntiguedad=round($item['basico']*$afiliados->calculaPorcentajeAntiguedad($item['fecha_ingreso']), 2);
					$body->setVariable("antiguedad", $calAntiguedad);
					$calSindical = round(($item['basico']*0.03),2);
					$body->setVariable("sindical", $calSindical); 
					$calArt = round(($item['basico']*0.01),2);
					$body->setVariable("art47", $calArt);
					$liquidaMes = $liquidaMes + round(($item['basico'] + $calAntiguedad + $calSindical + $calArt),2);
				$body->parseCurrentBlock("listado");
			}
		}
	$body->setVariable("liquidames", $liquidaMes);
	} else { $body->setVariable("noselected", " selcted='selected' ");  }


	$farmacias = new Farmacia();
	//-- Gestionar formulario para ingreso de gastos o depositos
		//Separador

	$listaItems = $farmacias->listar();
	foreach ($listaItems as $item) {
			if ($item[1]!="") {
				$body->setCurrentBlock("listacodigos");
				$body->setVariable("codigo", $item[0]);
				if ($item[0]==$idfarmacia) { $body->setVariable("selected", " selected='selected' "); }
				$body->setVariable("concepto", $item[1]." (".$item[3].")"); 
				$body->parseCurrentBlock("listacodigos");
			}
	}
		//Separador
		/*$body->setCurrentBlock("listacodigos"); 	$body->setVariable("codigo", 0);  	$body->setVariable("concepto", "-- De Sector --"); 	$body->parseCurrentBlock("listacodigos");
		$listaItems = $gestionar->volcarItemGastos('a');
		//var_dump($listaItems);
		foreach ($listaItems as $item) {
			$body->setCurrentBlock("listacodigos");
			$body->setVariable("codigo", $item[0]); 
			$body->setVariable("concepto", $item[0]."-".$item[1]); 
			$body->parseCurrentBlock("listacodigos");
		}*/


	//-- Gestion de administracion de volcado de movimiento de caja



	//var_dump($fechaMov);
    /*

    if ((isset($_POST['fecha'])) && (isset($_POST['importe'])) && (isset($_POST['codigo']))) {
    	$monto = $_POST['importe'];
    	$codigo = $_POST['codigo'];
    	$fecha = $_POST['fecha'];
    	$concepto = $_POST['concepto'];
    	$fecha=str_replace('-', "", $fecha);
    	//var_dump($fecha);
		$gestionar->movimiento($monto, $fecha, $codigo, $concepto);
    		
    }

		
		//Parsear Saldo del periodo actual
		$body->setCurrentBlock("listado");
		$body->setVariable("saldo", $gestionar->volcarSaldo($fechaMov)); 
		$body->setVariable("fondo_celda", "fondo_saldo");
		$body->parseCurrentBlock("listado");
		//Parsear ultimo comprobante para el ingreso de recibos.
		$body->setVariable("comprobantesiguiente", $gestionar->ultimoComprobante($fechaMov)+1);
		$body->setVariable("rango", $fc);
*/
	$body->setVariable("totallista", "0");

	$body->setVariable("fin","");
	$plantilla->setVariable("contenido", $body->get()); 
?>