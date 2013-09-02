<?php

	require_once("./library/PEAR.php");
	require_once("./library/IT.php");
	include_once("./library/mod_qfunciones.php");

class Saldo {
 	
 	private $_periodoactual=0;
 	private $_saldo;
	private $_db;
	private $_dbhost="127.0.0.1";
	private $_dbuname="sindicatofarm";
	private $_dbname="sindicatofarm";
	private $_timestamp;

	/* Constructor Clase Saldo
	//
	// Se define el periodo para calcular saldo del mes, y luego realizar un calculo de los saldos de los meses subsiguientes hasta el acutal.
	// 
	*/
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
		$this->_timestamp=date("Y-m-d H:i:s");
			
 	}

 	/* obtenerSaldo($periodo)
 	// 						Retorna el valor correspondiente al saldo operativo de ese periodo con la forma aaaamm
 	//
 	*/
 	public function obtenerSaldo($periodo){
		$consulta = "SELECT saldo FROM adm_saldo_mensual where periodo = $periodo limit 0,1";
		//var_dump($consulta);
		$rs=$this->_db->Execute($consulta);
		//var_dump($rs);
		if (!($rs === false)) {
			$this->_saldo=$rs->fields['saldo'];
			return ($rs->fields['saldo']);
		} else {
			$this->_saldo=0;
			return (0);
		}
 	}
 	

 	/*
 	* recalcularSaldo (periodo, valor)
 	*				Toma un periodo inicial y recalcula el saldo mes a mes, segun el ingreso realizado en un periodo viejo o anterior.
 	*				Devuelve la cantidad de registros actualizado. Si no se proceso = 0
 	*/
 	public function recalcularSaldo($periodo, $valor){
		$consulta = "SELECT id,saldo FROM adm_saldo_mensual where periodo >= $periodo order by periodo asc";
		//var_dump($consulta);
		$rs=$this->_db->Execute($consulta);
		$inn=""; $upd="";
		//var_dump($rs);
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
				$upd.="WHEN `id`=".$key." THEN ".$value." ";
				$inn.=",".$key;
			}
			$inn =substr($inn,1);
			$update1="UPDATE adm_saldo_mensual SET `fecha_modifica`= '".$this->_timestamp."', `saldo` = (CASE ".$upd." END)  WHERE `id` IN (".$inn.")";
			//var_dump($update1);
			$rs=$this->_db->Execute($update1);
			//var_dump($rs);
			return (count($saldo));
		} else {
			$fechaModifica=date("Y-m-d H:i:s"); 
			$inserta="INSERT INTO  `sindicatofarm`.`adm_saldo_mensual` (`id` , `periodo` , `saldo` , `fecha_modifica` ) VALUES ( NULL ,  '".$periodo."',  '".$valor."',  '".$fechamodifica."');";
			$rs=$this->_db->Execute($inserta);
			return (1);
		}

 	}

}

class Caja {
 	private $_periodoactual=0;
	private $_db;
	private $_dbhost="127.0.0.1";
	private $_dbuname="sindicatofarm";
	private $_dbname="sindicatofarm";
	private $_saldo;
	private $_timestamp;

	/*
	/	constructor de la clase CAJA. 
	/				Define la fecha actual, si no se establece periodo, se realiza conexion a DB y establece timestamp
	*/
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
 	/* movimiento
 	/				Registra el movimiento debe/haber de un monto determinado, la fecha establecida, con el codigo de ingreso/egreso y su
 	/				detalle correspondiente. Ademas retorno el Nro. de comprobantes. Actualiza registro de tabla saldos.
 	*/

 	//Fecha debe ser del formato aaaamm ej: 20130831
 	public function movimiento($monto, $fecha, $codigo, $detalle){
		
		$this->_saldo= new Saldo();
		$periodo = substr($fecha, 0,6);
		
		$saldo = $this->_saldo->obtenerSaldo($periodo);
		
		if ($codigo=='999')  //incremento monto x ingreso de deposito
		{
			$movimiento="d"; $rotulo=" Debe ";
			$valor = $monto;
		}else{
			$movimiento="h"; $rotulo=" Haber ";
			$valor = ($monto * -1);
		}
		
		//var_dump($fecha);
		$comprobante=$this->ultimoComprobante($fecha)+1;

		$consulta = "INSERT INTO  `sindicatofarm`.`adm_caja_mensual` (`fecha` , `importe`,`movimiento` ,`comprobante` ,`codigo` ,`concepto`, `fecha_transaccion`, `id_usuario`)
					 VALUES (  '$fecha',  '$monto',  '$movimiento','$comprobante',  '$codigo',  '$detalle', '$this->_timestamp', '".$_SESSION['uid']."');";
		//var_dump($consulta);
		//var_dump($_SESSION['uid']);
		$rs=$this->_db->Execute($consulta);
		//var_dump($rs);
		$this->_saldo->recalcularSaldo($periodo, $valor);
		//echo "registra movimiento al ".$rotulo." por un monto de ".$valor; 		

 	}
 	
	/*
	* ultimoComprobante
	*					Devuelve el ultimo comprobante empleado en el periodo mensual establecido por fecha. Fecha es completa aaaammdd.
	*/
	public function ultimoComprobante($fecha){
		$periodo = substr($fecha,0,6);
		$ini = $periodo."00";
		$fin = $periodo."31";
		$consulta = "SELECT `comprobante` FROM adm_caja_mensual WHERE `fecha` > $ini  AND   `fecha` <= $fin ORDER BY `comprobante` DESC limit 0,1";
		//var_dump($consulta);
		$rs=$this->_db->Execute($consulta);
		//var_dump($rs);
		if (!($rs === false)) {
			return ($rs->fields['comprobante']);
		} else {
			return (0);
		}
		//echo "ultimo comprobante ".$this->_saldo;
	}

	/* volcarMovimientos(periodo) 
	/							devuelve el listado de movimientos realizado en el periodo aaaamm
	/
	*/
	public function volcarMovimientos($fecha){
		$periodo = substr($fecha, 0, 6);
		//var_dump($periodo);
		//realizar un arreglo con todos los movimientos para su post-procesamiento	

		$consulta = "SELECT * FROM adm_caja_mensual WHERE ( `fecha` >  '".$periodo."00' AND  `fecha` <=  '".$periodo."31' ) ORDER BY  `fecha` ,  `movimiento` , `codigo`,`fecha_transaccion` DESC ";
		$rs=$this->_db->Execute($consulta);
		//var_dump($consulta);
		if (!($rs === false)) {
			return ($rs->GetArray());
		} else {
			return (0);
		}		
	}

	/* volcarItemGastos (sector)
	/							devuelve en un arreglo el listado de elementos segun las categorias para imputar una erogacion.
	/
	*/
	public function volcarItemGastos($sector="") {
		if (!(empty($filtro))) { $where=' WHERE sector "= '.$sector.'"'; } else { $where=""; }
		//if (!(empty($sector))) { $where=' WHERE sector "= '.$sector.'"'; } else { $where=""; }
		$consulta = "SELECT codigo, concepto FROM adm_gastos $where ORDER BY codigo, tipo DESC";
		$rs=$this->_db->Execute($consulta);
		return ($rs->GetArray());
		
	}

	public function volcarSaldo($fecha) {

		$this->_saldo= new Saldo();
		$periodo = substr($fecha, 0,6);	
		$saldo = $this->_saldo->obtenerSaldo($periodo);
		return ($saldo);
	}
}


?>