<?php
    //Caja es la clase, define todos procedimientos
    include_once("./modulos/AdministraCaja.php");

	$body= new HTML_Template_IT();
	$body->loadTemplatefile("./modulos/registroCaja.tpl");
	//instanciamos la clase AdministrarCaja
	$gestionar = new Caja();

	//-- Gestionar formulario para ingreso de gastos o depositos
		//Separador
		$body->setCurrentBlock("listacodigos"); 	$body->setVariable("codigo", 0);  	$body->setVariable("concepto", "-- De Administracion --"); 	$body->parseCurrentBlock("listacodigos");
		$listaItems = $gestionar->volcarItemGastos('a');
		//var_dump($listaItems);
		foreach ($listaItems as $item) {
			$body->setCurrentBlock("listacodigos");
			$body->setVariable("codigo", $item[0]); 
			$body->setVariable("concepto", $item[0]."-".$item[1]); 
			$body->parseCurrentBlock("listacodigos");
		}
		//Separador
		$body->setCurrentBlock("listacodigos"); 	$body->setVariable("codigo", 0);  	$body->setVariable("concepto", "-- De Sector --"); 	$body->parseCurrentBlock("listacodigos");
		$listaItems = $gestionar->volcarItemGastos('a');
		//var_dump($listaItems);
		foreach ($listaItems as $item) {
			$body->setCurrentBlock("listacodigos");
			$body->setVariable("codigo", $item[0]); 
			$body->setVariable("concepto", $item[0]."-".$item[1]); 
			$body->parseCurrentBlock("listacodigos");
		}


	//-- Gestion de administracion de volcado de movimiento de caja

    if ((isset($_POST['fecha'])) && (isset($_POST['importe'])) && (isset($_POST['codigo']))) {
    	$monto = $_POST['importe'];
    	$codigo = $_POST['codigo'];
    	$fecha = $_POST['fecha'];
    	$detalle = $_POST['detalle'];
		$gestionar->ingreso($monto, $codigo, $detalle, $fecha);
    		
    }
    

    $gestionar->movimiento(1000, '20130703', '999', 'Retiro e Ingreso para caja diara', '');
    
    //$gestionar->volcarSaldo();


	$body->setVariable("totallista", "0");

	$body->setVariable("fin","");
	$plantilla->setVariable("contenido", $body->get()); 
?>