<?php

	require_once("./library/PEAR.php");
	require_once("./library/IT.php");
	include_once("./library/mod_qfunciones.php");

class Saldo {
 	
 	private $_periodoactual=0;
 	private $_saldo;
	private $_db;
	private $_dbhost="127.0.0.1";
	private $_dbuname="root";
	private $_dbname="sindicatofarm";

	//Fecha actual debe ser del formato mmaaaa ej: 201307
	public function __construct($fechaActual='0')
	{
		if ($fechaActual<201000) $fechaActual='0'; //No existen registros anteriores a 2010
		if ($fechaActual==0) {
			$this->_periodoactual=date("ym");
		} else {
			$this->_periodoactual=$fechaActual;
		}
		$this->_db=ADONewConnection();
		//$this->_dbhost
		$db->debug = true ;
		$result = $this->_db->Connect("$this->_dbhost", "$this->_dbuname", "", "$this->_dbname");
			
 	}

 	public function obtenerSaldo($periodo){
		$consulta = "SELECT saldo FROM adm_saldo_mensual where periodo = $periodo limit 0,1";
		$rs=$this->_db->Execute($consulta);
		if (!($rs === false)) {
			$this->_saldo=$rs->fields['saldo'];
			return ($rs->fields['saldo']);
		} else {
			$this->_saldo=0;
			return (0);
		}
 	}
 	

 	/*
 	* recalcularSaldo
 	*				Toma un periodo inicial y recalcula el saldo mes a mes, segun el ingreso realizado en un periodo viejo o anterior.
 	*				Devuelve la cantidad de registros actualizado. Si no se proceso = 0
 	*/
 	public function recalcularSaldo($periodo, $valor){
		$consulta = "SELECT id,saldo FROM adm_saldo_mensual where periodo >= $periodo order by periodo asc";
		$rs=$this->_db->Execute($consulta);
		$inn=""; $upd="";
		if (!($rs === false)) {
			$primero = true;
			while (!$rs->EOF) {
				 $nuevoSaldo=$rs->fields['saldo']+($valor);
				 if ($primero) { 
				 	$fechaModifica=date("Y-m-d H:i:s"); 
				 	$primero=false;
				 }
				 $saldo[$rs->fields['id']]=$nuevoSaldo;
				 $rs->MoveNext();	 
			}
			$upd="";
			foreach ($saldo as $key => $value) {
				$upd.="WHEN id=".$key." THEN `".$value."` ";
				$inn.=",".$key;
			}
			$inn =substr($inn,1);
			$update1="UPDATE adm_saldo_mensual SET saldo = CASE ".$upd." END WHERE id IN (".$inn.")";
			$rs=$this->_db->Execute($update1);
			return (count($saldo));
		} else {
			return (0);
		}

 	}

}

class Caja {
 	private $_periodoactual=0;
	private $_db;
	private $_dbhost="127.0.0.1";
	private $_dbuname="root";
	private $_dbname="sindicatofarm";
	private $_saldo;
	private $_timestamp;

	//Fecha actual debe ser del formato aaaamm ej: 20130831
	public function __construct($fechaActual='0')
	{
		if ($fechaActual==0) {
			$this->_periodoactual=date("ym");
		} else {
			$this->_periodoactual=$fechaActual;
		}
		$this->_db=ADONewConnection();
		$this->_timestamp=date("Y-m-d H:i:s"); 
		//$this->_dbhost
		$db->debug = true ;

		$result = $this->_db->Connect("$this->_dbhost", "$this->_dbuname", "", "$this->_dbname");

 	}

 	//Fecha debe ser del formato aaaamm ej: 20130831
 	public function movimiento($monto, $fecha, $codigo, $detalle){
		
		$this->_saldo= new Saldo();
		$periodo = substr($fecha, 6);
		$saldo = $this->_saldo->obtenerSaldo($periodo);
		
		if ($codigo=='999')  //incremento monto x ingreso de deposito
		{
			$movimiento="d"; $rotulo=" Debe ";
		}else{
			$movimiento="h"; $rotulo=" Haber ";
		}
		
		$comprobante=$this->ultimoCompobante($fecha)+1;

		$consulta = "INSERT INTO  `sindicatofarm`.`adm_caja_mensual` (`fecha` , `importe`,`movimiento` ,`comprobante` ,`codigo` ,`concepto`, `fecha_transaccion`)
					 VALUES (  '$fecha',  '$monto',  '$movimiento','$comprobante',  '$codigo',  '$detalle', $this->_timestamp );";
		$rs=$this->_db->Execute($consulta);

		$this->_saldo->recalcularSaldo($periodo, $monto);
		echo "registra movimiento al ".$rotulo." por un monto de ".$monto; 		

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

	public function ultimoCompobante($fecha){
		$periodo = substr($fecha,6);
		$ini = $fecha."00";
		$fin = $fecha."31";
		$consulta = "SELECT comprobante FROM adm_caja_mensual WHERE fecha > $ini  AND   fecha < $fin ORDER BY fecha, id DESC limit 0,1";
		$rs=$this->_db->Execute($consulta);
		
		if (!($rs === false)) {
			return ($rs->fields['comprobante']);
		} else {
			return (0);
		}
		//echo "ultimo comprobante ".$this->_saldo;
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