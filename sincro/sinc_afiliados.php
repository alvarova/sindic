<?
/**
* ----------------------------------------------------------------
*			Resinc Afiliados
*			Actualizacion de los datos en MYSql desde DBF	
* 
*  Developer        : Alvaro J. Vera
*  released at      : Nov 2005, Actual 2012
*  last modified by : Alvaro
* 
* --------------------------------------------------------------
*
*
**/

	/* load the required classes */
	error_reporting(0);

function existeItem($busca) {
	global $arr_sql;

	foreach ($arr_sql as $key => $value) {
		//echo $value[0]."_";
		if ($value[0]==$busca){
			$ret=true;
			break;
		}else{
			$ret=false;			
		}
	}

	return($ret);
}


require_once "Column.class.php";
require_once "Record.class.php";
require_once "Table.class.php";
include_once("../library/adodb_lite/adodb.inc.php"); 
//	include_once("../localconf.php");

$dbuname="sindicatofarm";
$dbpass="";
$dbhost="localhost";
$dbname="sindicatofarm";

$db = ADONewConnection();

$db->debug = false ;
$result = $db->Connect("$dbhost", "$dbuname", "", "$dbname");


$commit = ADONewConnection();
$result = $commit->Connect("$dbhost", "$dbuname", "", "$dbname");
$commit->debug = false ;

/*Setear los parametros de las tablas a resincronizar*/
//*****************************************************
$dbf='../../unidadc/sindfarm/Data/afiliados.dbf';
//*****************************************************

$consulta = "SELECT * FROM categorias";
$rs=$db->Execute($consulta);
$arr_cat=$rs->GetArray();  //Obtengo el arreglo con las categorias
//var_dump($arr_cat);

$consulta = "SELECT * FROM afiliados Order By id_afiliado";
$rs=$db->Execute($consulta);

/* create a table object and open it */
$table = new XBaseTable($dbf);
$table->open();

	/* print some header info 
    echo "Datos de la tabla DBF:$dbf<br/>";
    echo "version: ".$table->version."<br />";
    echo "foxpro: ".($table->foxpro?"yes":"no")."<br />";
    echo "modifyDate: ".date("r",$table->modifyDate)."<br />";
    echo "recordCount: ".$table->recordCount."<br />";   //cantidad de registros para controlar la cantidad existentes. Si cambio recargar.
    echo "headerLength: ".$table->headerLength."<br />";
    echo "recordByteLength: ".$table->recordByteLength."<br />";
    echo "inTransaction: ".($table->inTransaction?"yes":"no")."<br />";
    echo "encrypted: ".($table->encrypted?"yes":"no")."<br />";
    echo "mdxFlag: ".ord($table->mdxFlag)."<br />";
    echo "languageCode: ".ord($table->languageCode)."<br />";

	echo $rs->FieldCount()."<br/>";*/
	$cantidad_registros_sql = $rs->RecordCount();//."<br/>";

	$arr_sql=$rs->GetArray();

	

	$reg_found=0;
	$reg_no_encontrado="";	
	
	$md5creados=0;
	$md5actualizados=0;
	$altas=0;
	$sincambios=0;
	$registro_fila=0;
    while ($record=$table->nextRecord()) {
	    
	// -------   Iteracion entre las filas    -------

	    $col=0; //Zero to columna DBF
	    $cadenamd5=""; //Reseteamos la variable para el calculo del md5
	    $toInsertar="";
	    
	    foreach ($table->getColumns() as $i=>$c) {

	    	// ---------  Iteracion entre las columnas    -------

			$columna_dbf=$record->getString($c);			
			//echo "-".$columna_dbf."-";
			$cadenamd5.=$columna_dbf; //acumulamos la cadena completa para convertir en md5

			if ($col==0) {  //tomo el primer campo id_afiliado DBF y lo comparo con todos de SQL
				$idafiliado=$columna_dbf;
				if (existeItem($columna_dbf)) {
					//Existe en la DB buscar en tabla de md5 y comparar - se hace fuera de este bloque por que se necesita la cadena md5 completa
				    //echo "El elemento se encuentra ".$columna_dbf." buscar MD5 y actualizar si corresponde ";
				    $existe=true;
				} else {
					//No existe en DB levantar los datos y crear Md5
					//echo "NO SE ENCUENTRA ".$columna_dbf." dar de alta afiliado y crear MD5 ";
					$existe=false;
				}
			}
			// Navego en las otras columnas para generar un array que luego nos servira para actualizar o insertar elementos en la MySQL
			if ($col==2) {
			    foreach ($arr_cat as $categ) {
				            if (substr($categ[1],0,3)==substr($columna_dbf,0,3)) $cat=$categ[0];        
				        }
        		$toInsertar[$idafiliado][$col]=$cat; //conversion de categorias de fmt txt o id para tabla.
			} elseif (($col==13)||($col==20)||($col==22)||($col==23)||($col==33)||($col==35)||($col==40)||($col==41)||($col==45))  {   //conversion fechas a fmt DB
						$cf=date("Y-m-d", strtotime($columna_dbf));
						//echo "---> Original: ".$columna_dbf." converted=".$cf."<br>";
						if ($cf=="1970-01-01") $cf="";  // Por defecto el sistema viejo no tiene asignado una fecha, fecha en blanco.
				        $toInsertar[$idafiliado][$col]=$cf;
				        
			} else {
				        $toInsertar[$idafiliado][$col]=addslashes(htmlentities($columna_dbf));
			}	
		$col++;
		//echo "<br>";
		}
		

		$md5=md5($cadenamd5);
	    //echo $md5."->".$cadenamd5."<br/>";

	    //Existe el afiliado que importamos del DBF en nuestra MySQL -> entonces consulta MD5, si existe MD5 a)son iguales? no pasa nada b) difiere - actualizar datos. No existe MD5? -> Crearlo. 
		if ($existe) {
			
				$db2 = ADONewConnection();
				$db2->debug = false ;
				$res = $db2->Connect("$dbhost", "$dbuname", "", "$dbname");
				// Buscamos el md5 del afiliado en cuestion
				$consulta = "SELECT * FROM afiliadosmd5 where id_afiliado=".$idafiliado;
				$res=$db2->Execute($consulta);

				if ($db2->Affected_Rows()<1) { //No existe md5 asociado, crear
					echo "Creando MD5 para Afiliado ".$idafiliado;
					$consulta="INSERT INTO `sindicatofarm`.`afiliadosmd5` (`id_afiliado`, `md5`) VALUES ('".$idafiliado."', '".$md5."')";
					$sale=$commit->Execute($consulta);
					$md5creados++;
				} else {  //Existe obtener Md5 y comparar
					echo "Afiliado ".$idafiliado." SQLMD5=".$res->fields['md5']." MD5Calculado=".$md5."\n\n";
					if ($res->fields['md5']!=$md5) {
						$md5actualizados++;

						$updatemd5="UPDATE  `sindicatofarm`.`afiliadosmd5` SET  `md5` =  '".$md5."' WHERE  `afiliadosmd5`.`id_afiliado` =".$idafiliado;
						$updateafiliado="UPDATE  `sindicatofarm`.`afiliados` SET  
						`id_farmacia`=	".$toInsertar[$idafiliado][1].",
						`id_categoria`=	".$toInsertar[$idafiliado][2].",
						`id_parentesco`= ".$toInsertar[$idafiliado][3].",	
						`tipo_documento`= '".$toInsertar[$idafiliado][4]."',	
						`nro_documento`= '".$toInsertar[$idafiliado][5]."',	
						`nombre`= '".$toInsertar[$idafiliado][6]."',	
						`sexo`=	'".$toInsertar[$idafiliado][7]."',
						`domicilio`=	'".$toInsertar[$idafiliado][8]."',
						`localidad`=	'".$toInsertar[$idafiliado][9]."',
						`cod_postal`=	".$toInsertar[$idafiliado][10].",
						`nacionalidad`=	'".$toInsertar[$idafiliado][11]."',
						`telefono`=	'".$toInsertar[$idafiliado][12]."',
						`fecha_nacimiento`=	'".$toInsertar[$idafiliado][13]."',
						`estado_civil`=	'".$toInsertar[$idafiliado][14]."',
						`cuil`=	'".$toInsertar[$idafiliado][15]."',
						`sindicato`=	'".$toInsertar[$idafiliado][16]."',
						`obra_social`=	'".$toInsertar[$idafiliado][17]."',
						`mutual`=	'".$toInsertar[$idafiliado][18]."',
						`sepelio`=	'".$toInsertar[$idafiliado][19]."',
						`fecha_ingreso`=	'".$toInsertar[$idafiliado][20]."',
						`efectivo`=	'".$toInsertar[$idafiliado][21]."',
						`contrato_desde`=	'".$toInsertar[$idafiliado][22]."',
						`contrato_hasta`=	'".$toInsertar[$idafiliado][23]."',
						`jornada_completa`=	'".$toInsertar[$idafiliado][24]."',
						`dsnombre`=	'".$toInsertar[$idafiliado][25]."',
						`dsdomicilio`=	'".$toInsertar[$idafiliado][26]."',
						`dstelefono`=	'".$toInsertar[$idafiliado][27]."',
						`dslocalidad`=	'".$toInsertar[$idafiliado][28]."',
						`dscod_postal`=	'".$toInsertar[$idafiliado][29]."',
						`dsnacion`=	'".$toInsertar[$idafiliado][30]."',
						`dstipo_documento`=	'".$toInsertar[$idafiliado][31]."',
						`dsnro_documento`=	'".$toInsertar[$idafiliado][32]."',
						`dsfecha_nacimiento`=	'".$toInsertar[$idafiliado][33]."',
						`firma_formulario`=	'".$toInsertar[$idafiliado][34]."',
						`fecha_baja`=	'".$toInsertar[$idafiliado][35]."',
						`motivo_baja`=	'".$toInsertar[$idafiliado][36]."',
						`sueldo`=	'".$toInsertar[$idafiliado][37]."',
						`sindicato_cuota`=	'".$toInsertar[$idafiliado][38]."',
						`mutual_cuota`=	'".$toInsertar[$idafiliado][39]."',
						`sindicato_ingreso`=	'".$toInsertar[$idafiliado][40]."',
						`mutual_ingreso`=	'".$toInsertar[$idafiliado][41]."',
						`observaciones`=	'".$toInsertar[$idafiliado][42]."',
						`adicional1`=	'".$toInsertar[$idafiliado][43]."',
						`adicional2`=	'".$toInsertar[$idafiliado][44]."',
						`os_ingreso`= '".$toInsertar[$idafiliado][45]."'
						 WHERE  `afiliados`.`id_afiliado` =".$idafiliado;
						 //echo $updateafiliado."-".$updatemd5."<br/>";
						 $sale=$commit->Execute($updateafiliado);
						 $sale=$commit->Execute($updatemd5);
						 //var_dump($toInsertar);
					} else {
						$sincambios++;
					}
				}


		}else{ //No exste afiliado, es necesario darlo de alta en la MySQL y generar su MD5
			$insert="INSERT INTO afiliados  values ( ".$idafiliado.",
							 ".$toInsertar[$idafiliado][1].",
							 ".$toInsertar[$idafiliado][2].",
						 	 ".$toInsertar[$idafiliado][3].",	
						 	'".$toInsertar[$idafiliado][4]."',	
						 	'".$toInsertar[$idafiliado][5]."',	
						 	'".$toInsertar[$idafiliado][6]."',	
							'".$toInsertar[$idafiliado][7]."',
							'".$toInsertar[$idafiliado][8]."',
							'".$toInsertar[$idafiliado][9]."',
							 ".$toInsertar[$idafiliado][10].",
							'".$toInsertar[$idafiliado][11]."',
							'".$toInsertar[$idafiliado][12]."',
							'".$toInsertar[$idafiliado][13]."',
							'".$toInsertar[$idafiliado][14]."',
							'".$toInsertar[$idafiliado][15]."',
							'".$toInsertar[$idafiliado][16]."',
							'".$toInsertar[$idafiliado][17]."',
							'".$toInsertar[$idafiliado][18]."',
							'".$toInsertar[$idafiliado][19]."',
							'".$toInsertar[$idafiliado][20]."',
							'".$toInsertar[$idafiliado][21]."',
							'".$toInsertar[$idafiliado][22]."',
							'".$toInsertar[$idafiliado][23]."',
							'".$toInsertar[$idafiliado][24]."',
							'".$toInsertar[$idafiliado][25]."',
							'".$toInsertar[$idafiliado][26]."',
							'".$toInsertar[$idafiliado][27]."',
							'".$toInsertar[$idafiliado][28]."',
							'".$toInsertar[$idafiliado][29]."',
							'".$toInsertar[$idafiliado][30]."',
							'".$toInsertar[$idafiliado][31]."',
							'".$toInsertar[$idafiliado][32]."',
							'".$toInsertar[$idafiliado][33]."',
							'".$toInsertar[$idafiliado][34]."',
							'".$toInsertar[$idafiliado][35]."',
							'".$toInsertar[$idafiliado][36]."',
							'".$toInsertar[$idafiliado][37]."',
							'".$toInsertar[$idafiliado][38]."',
							'".$toInsertar[$idafiliado][39]."',
							'".$toInsertar[$idafiliado][40]."',
							'".$toInsertar[$idafiliado][41]."',
							'".$toInsertar[$idafiliado][42]."',
							'".$toInsertar[$idafiliado][43]."',
							'".$toInsertar[$idafiliado][44]."',
							'".$toInsertar[$idafiliado][45]."')";
						 $insertmd5="INSERT INTO  `sindicatofarm`.`afiliadosmd5` values ('".$idafiliado."', '".$md5."')";
						 $sale=$commit->Execute($insert);
						 $sale=$commit->Execute($insertmd5);
			$altas++;
			//var_dump($toInsertar);
		}
		$registro_fila++;

	}
				//if $c==

	//var_dump($arr_sql);
	echo "\n --- RESUMEN de OPERACIONES ---";
	echo "\n Se realizaron altas: ".$altas;
	echo "\n Se realizaron sin cambios: ".$sincambios;
	echo "\n Se realizaron altas en md5: ".$md5creados;
	echo "\n Se realizaron updates segun md5: ".$md5actualizados;
	$table->close();
	$consulta="UPDATE `sindicatofarm`.`parametros` SET (`valor`= '".date("Y-m-d")."') WHERE `variable`='sincronizacion'";
	$sale=$commit->Execute($consulta)
?>
