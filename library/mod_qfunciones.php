<?
/*
Pasar a objetos
Funciones de calculo de edad - generar modulo de calculo general
Modulo Quick Functions
*/

function anio($fecha)
{
	$sale=explode("-", $fecha);
	return($sale[0]);
}

function antiguedad($fecha_nac){ 
//en formato aaaa/mm/dd calcula la antiguedad en números enteros 

$dia=date("j"); 
$mes=date("n"); 
$anno=date("Y"); 

//descomponer fecha de nacimiento 
$dia_nac=substr($fecha_nac, 8, 2); 
$mes_nac=substr($fecha_nac, 5, 2); 
$anno_nac=substr($fecha_nac, 0, 4); 

//echo "dia:".$dia_nac." mes:".$mes_nac." anio".$anno_nac;
if($mes_nac>$mes){ 
$calc_edad= $anno-$anno_nac-1; 
}else{ 
if($mes==$mes_nac AND $dia_nac>$dia){ 
$calc_edad= $anno-$anno_nac-1;  
}else{ 
$calc_edad= $anno-$anno_nac; 
} 
} 
return $calc_edad; 
} 

/*
*	Obtener la antiguedad en meses y dar si corresponde PMI
*
*/
//((Year ( fecha2 ) - Year ( fecha1 ))*12) +(Month ( fecha2 ) - Month ( fecha1 ))+If ( Day ( fecha2 ) > Day ( fecha1 ) ; 1 ; 0 )
function dar_pmi($fecha_nac)
{
//descomponer fecha de nacimiento 2004-12-31
$dia_nac=substr($fecha_nac, 8, 2); 
$mes_nac=substr($fecha_nac, 5, 2); 
$anno_nac=substr($fecha_nac, 0, 4);
//$dia=date("j"); 
//$mes=date("n"); 
///$anno=date("Y"); 
  // end date is 2008 Oct. 11 00:00:00
  $_endDate = mktime(0,0,0,date("m"),date("d"),date("Y"));
  // begin date is 2007 May 31 13:26:26
  $_beginDate = mktime(0,0,0,$mes_nac,$dia_nac,$anno_nac);

  $timestamp_diff= $_endDate-$_beginDate +1 ;
  // how many days between those two date
  $days_diff = $timestamp_diff/86400;
  if ($days_diff<365) { $sale=true;}else{$sale=false;}
  return $sale;
}

function dar_orden($fecha_ultima_orden){
//descomponer fecha  2004-12-31
$dia_nac=substr($fecha_ultima_orden, 8, 2); 
$mes_nac=substr($fecha_ultima_orden, 5, 2); 
$anno_nac=substr($fecha_ultima_orden, 0, 4);

  // end date is 2008 Oct. 11 00:00:00
  $_endDate = mktime(0,0,0,date("m"),date("d"),date("Y"));
  // begin date is 2007 May 31 13:26:26
  $_beginDate = mktime(0,0,0,$mes_nac,$dia_nac,$anno_nac);

  $timestamp_diff= $_endDate-$_beginDate +1 ;
  // how many days between those two date
  $days_diff = $timestamp_diff/86400;
if ($days_diff<31) { $sale=false;}else{$sale=true;}

return $sale;

}

/* AVISA DATOS FALTANTES
 * Funcion para evaluar faltantes de datos.
 */
function envia_aviso($cadena){
	if (strlen($cadena)<2)
	{
		$sale=true;
	}else{
		$sale=false;
	}
 return $sale;
}
$aviso="";

/* Agregar COMA decimal al valor pasado*/
function add_decimal($value, $pon_punto = "."){
	if ($value=="") {$value="0";}

	$coma=strrchr($value,','); //Busco si tiene comas
	$dot=strrchr($value,'.');  //Busco si tiene puntos
	
	if (!($coma && $dot)) {  //Si no tienen ninguna
			$sale=$value.$pon_punto."00";
	}else { 
		$sale = $value;
	}
	return $sale;
}



?>
