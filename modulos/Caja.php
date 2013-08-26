<?php

	require_once("./library/PEAR.php");
	require_once("./library/IT.php");
	include_once("./library/mod_qfunciones.php");


class AdministraCaja {
	
	// Clase Administracion de Caja
	//       Permite generar abm de los gastos e ingresos de la caja diaria
	//		 es necesario llevar un control del saldo en tabla separada (de momento)

	private $_saldo=0;
	private $_comprobante=0;
	private $_fechaActual=0;
	private $_db;
	private $_dbhost="127.0.0.1";
	private $_dbuname="root";
	private $_dbname="sindicatofarm";

	//Fecha actual debe ser del formato mmaaaa ej: 072013
	public function __construct($fechaActual='0')
	{
		//Defino fecha de procesamiento, si no posee coloco actual
		if ($fechaActual=='0') $fechaActual=date("dY");
		$this->_fechaActual = $fechaActual;

		//Establezco conexion con la DB		
		$this->_db=ADONewConnection();
		//$this->_dbhost
		//$db->debug = true ;

		$result = $this->_db->Connect("$this->_dbhost", "$this->_dbuname", "", "$this->_dbname");
		$this->_saldo=$this->buscaSaldo();		
	}

	
	// Consulta de Saldo actual, del mes en curso
	private function buscaSaldo($fecha=""){
		//Es necesario obtener el ultimo estado de saldo.
		// Si es = 0 no existe, arranca en cero, 
		// Si es 0.00 termino el mes en cero.
		//Falta obtener el ultimo estado del saldo.
		$consulta = "SELECT saldo FROM adm_saldo_mensual ORDER BY periodo DESC limit 0,1";
		$rs=$this->_db->Execute($consulta);
		
		if (!($rs === false)) {
			/*$buscar = TRUE;
			while (!$rs->EOF && $buscar) {
				if $rs->fields['nombre']
			}*/
			return ($rs->fields['saldo']);
		} else {
			return (0);
		}
	}

	private function set_ultimo_comprobante(){

	}




	// Evalua la existencia del periodo en vigencia, si existe hace un update del item nuevo, sino inserta un nuevo periodo
	private function verifca_periodo($periodo) { //El parametro de periodo debe entrar como 2013-08-01
		
		$periodo=explode($fecha,'-');
		$consulta = "SELECT * FROM adm_saldo_mensual WHERE periodo='".$periodo[0].$periodo[1]."'' DESC limit 0,1";
		$rs=$this->_db->Execute($consulta);
		$nulo=true;
		//Entra fecha con la forma 2013-08-01
		if (is_null($rs)) {
			$this->_comprobante=1;
			$consulta="INSERT INTO  `sindicatofarm`.`adm_saldo_mensual` (`id` ,`periodo` ,`saldo` ,`ultimo_comprobante`)VALUES (NULL ,  $periodo,  '2000',  '1')";	
		} else {
			$this->_comprobante=$rs->fields['ultimo_comprobante'];
			$periodo=$rs->fields['periodo'];
			$saldo=$rs->fields['saldo'];
			$consulta="UPDATE  `sindicatofarm`.`adm_saldo_mensual` SET  `saldo` =  '2500.00' WHERE  `adm_saldo_mensual`.`id` =2;";
		}
		$rs=$this->_db->Execute($consulta);
	}

	//Registra ingreso de capital a la caja
	public function ingreso($monto, $codigo, $detalle, $fecha) {
		
		//$this->_saldo=$this->_saldo+$monto;

		
		//Averiguar si existe periodo, sino existe se hace insert, sino update
		if ($codigo=='999')  //incremento monto x ingreso de deposito
		{
			$saldo=$saldo+$monto;
			$campo="debe";
		}else{
			$saldo=$saldo-$monto;
			$campo="haber";
		}
		$this->verifca_periodo($fecha);
		
		$consulta = "INSERT INTO  `sindicatofarm`.`adm_caja_mensual` (`fecha` ,`".$campo."` ,`comprobante` ,`codigo` ,`concepto`) VALUES (  '$fecha',  '$monto',  '3',  '$codigo',  '$detalle' );";
		$rs=$this->_db->Execute($consulta);

		


		echo "ingresa deposito de ".$monto;
	}



	//Registra erogación de diferentes conceptos
	public function egreso($monto, $codigo, $detalle, $fecha) {
		$this->_saldo=$this->_saldo-$monto;
		
		$consulta = "SELECT saldo FROM adm_saldo_mensual ORDER BY periodo DESC limit 0,1";
		$rs=$this->_db->Execute($consulta);

		echo "egreso pago de ".$monto;
		$consulta = "INSERT INTO  `sindicatofarm`.`adm_caja_mensual` (`fecha` ,`importe` ,`comprobante` ,`codigo` ,`concepto`) VALUES (  '$fecha',  '$monto',  '3',  '$codigo',  '$detalle' );";
		$rs=$this->_db->Execute($consulta);

	}

	
	// Recalcular todo el saldo a partir de una fecha dada o desde el comienzo.
	// Si se toma una fecha dada (mes) se toma el saldo del mes anterior.
	public function recomputarSaldo($periodo_inicio){
		$consulta = "SELECT saldo FROM adm_saldo_mensual ORDER BY periodo ASC";
		$rs=$this->_db->Execute($consulta);
		if (!($rs === false)) {
			/*$buscar = TRUE;
			while (!$rs->EOF && $buscar) {
				if $rs->fields['nombre']
			}*/
			return ($rs->fields['saldo']);
		}		

	}
	
	public function volcarMovimientos($fecha){
		//realizar un arreglo con todos los movimientos para su post-procesamiento	
		$consulta = "SELECT * FROM adm_saldo_mensual WHERE MONTH(fecha_modifica) = MONTH($fecha) AND   YEAR(fecha_modifica) = YEAR($fecha) ORDER BY fecha_modifica DESC limit 0,1";
		$rs=$this->_db->Execute($consulta);
		
		if (!($rs === false)) {
			return ($rs->GetArray());
		} else {
			return (0);
		}		
	}

	public function volcarSaldo(){
		echo "sale resultado de caja mensual ".$this->_saldo;
	}

	public function volcarItemGastos($sector="") {
		if (!(empty($filtro))) { $where=' WHERE sector "= '.$sector.'"'; } else { $where=""; }
		$consulta = "SELECT codigo, concepto FROM adm_gastos $where ORDER BY codigo, tipo DESC";
		$rs=$this->_db->Execute($consulta);
//		var_dump($rs);
		return ($rs->GetArray());
		
	}

}


?>