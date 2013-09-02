<?php
    //Caja es la clase, define todos procedimientos
    include_once("./modulos/AdministraCaja.php");

	$body= new HTML_Template_IT();
	$body->loadTemplatefile("./modulos/registroCaja.tpl");
	//instanciamos la clase AdministrarCaja
	$gestionar = new Caja();


	//-- Gestionar formulario para ingreso de gastos o depositos
		//Separador
		$body->setCurrentBlock("listacodigos"); 	$body->setVariable("codigo", 0);  	$body->setVariable("concepto", "&nbsp;&nbsp;&nbsp;&nbsp;-- De Administracion --"); 	$body->parseCurrentBlock("listacodigos");
		$listaItems = $gestionar->volcarItemGastos('a');
		$bander_separador=true;
		foreach ($listaItems as $item) {
			if (($item[0]>42200) && ($bander_separador)){
				$body->setCurrentBlock("listacodigos"); 	$body->setVariable("codigo", 0);  	$body->setVariable("concepto", "&nbsp;&nbsp;&nbsp;&nbsp;-- De Sector --"); 	$body->parseCurrentBlock("listacodigos");
				$bander_separador=false;
			}
			$body->setCurrentBlock("listacodigos");
			$body->setVariable("codigo", $item[0]); 
			$body->setVariable("concepto", $item[0]." - ".$item[1]); 
			$body->parseCurrentBlock("listacodigos");
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
	if (isset($_POST['fechaconsulta'])) {
		$fc=$_POST['fechaconsulta'];
		$fechaMov=str_replace('-', "", $fc);
	} else {
		$fechaMov=date("Ym");
	}

	//var_dump($fechaMov);

    if ((isset($_POST['fecha'])) && (isset($_POST['importe'])) && (isset($_POST['codigo']))) {
    	$monto = $_POST['importe'];
    	$codigo = $_POST['codigo'];
    	$fecha = $_POST['fecha'];
    	$concepto = $_POST['concepto'];
    	$fecha=str_replace('-', "", $fecha);
    	//var_dump($fecha);
		$gestionar->movimiento($monto, $fecha, $codigo, $concepto);
    		
    }
    $listaMovimientos=$gestionar->volcarMovimientos($fechaMov);
    foreach ($listaMovimientos as $item) {
    	//	fecha	importe	movimiento	comprobante	codigo	concepto
    		$fondo="";
    		if ($item['codigo']<42200) { $columna="d"; }
    		if ($item['codigo']>42200) { $columna="i"; }
    		if ($item['codigo']==999) {
    			$fondo="fondo_celda";
    		}
			$body->setCurrentBlock("listado");
			$body->setVariable("fecha", $item['fecha']);
			$body->setVariable("nro".$columna, $item['comprobante']);
			$body->setVariable("codigo".$columna, $item['codigo']);
			$body->setVariable("concepto".$columna, $item['concepto']);
			$body->setVariable("importe".$columna, $item['importe']);
			$body->setVariable("saldo", ""); 
			$body->setVariable("fondo_celda", $fondo);
			$body->parseCurrentBlock("listado");
		}

		
		//Parsear Saldo del periodo actual
		$body->setCurrentBlock("listado");
		$body->setVariable("saldo", $gestionar->volcarSaldo($fechaMov)); 
		$body->setVariable("fondo_celda", "fondo_saldo");
		$body->parseCurrentBlock("listado");
		//Parsear ultimo comprobante para el ingreso de recibos.
		$body->setVariable("comprobantesiguiente", $gestionar->ultimoComprobante($fechaMov)+1);

	$body->setVariable("totallista", "0");

	$body->setVariable("fin","");
	$plantilla->setVariable("contenido", $body->get()); 
?>