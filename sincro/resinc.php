<?
/**
* ----------------------------------------------------------------
*			Resinc via XBase
*			Actualizacion de los datos en MYSql desde DBF	
* 
*  Developer        : Erwin Kooi, adapted by Alvaro
*  released at      : Nov 2005, Actual 2012
*  last modified by : Alvaro
* 
* --------------------------------------------------------------
*
*
**/

	/* load the required classes */
	//error_reporting(0);

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

$base=$_GET["file"];



$consulta = "SELECT * FROM categorias";
$rs=$db->Execute($consulta);
$arr_cat=$rs->GetArray();  //Obtengo el arreglo con las categorias
//var_dump($arr_cat);

$consulta = "SELECT * FROM afiliados Order By id_afiliado";
$rs=$db->Execute($consulta);

	
	
	
	/* create a table object and open it */
	$table = new XBaseTable("../".$base.".DBF");
	$table->open();

	/* print some header info */
    echo "Datos de la tabla DBF:$base<br/>";
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

	echo $rs->FieldCount()."<br/>";
	$cantidad_registros_sql = $rs->RecordCount();//."<br/>";

	$arr_sql=$rs->GetArray();

	$reg_found=0;
$reg_no_encontrado="";	
    while ($record=$table->nextRecord()) {
	    
	    $col=0; //Zero to columna DBF
	    
	    foreach ($table->getColumns() as $i=>$c) {
		    //echo "-".$record->getString($c)." ";
			//Aqui viene lo lindo... 
			//Tomar el Dato del DBF pregunto si esta en la SQL, (tengo cargado todo el array por filas bajo $arr_sql)
			//	opc a) no? Agrego
			//      opc b) si? Verifico si existió modificacion
			//      -mientras voy acumulando en un array los id_afiliado para luego eliminar los que no estan mas.
			//      Posibles problemas al momento de sincronizar contra la DBF ya que se eliminarian los datos cargados en SQL
			//      se recomienda dejar registros antiguos y proceder a la actualización.
			//      -Importante tomar categoria y pasarla a formato tabla, ya que en SQL esta normalizada
			//      -Verificar pasos.
			$columna_dbf=$record->getString($c);			
			if ($col==0) {  //tomo el primer campo id_afiliado DBF y lo comparo con todos de SQL
				$reg_found_bandera=true;
				$registro_fila=$columna_dbf; //Aqui guarda el id_afiliado
				for ($i=0; $i<$cantidad_registros_sql; $i++) {  //Busco el registro DBF dentro del array de todos los reg SQL
					if ($arr_sql[$i][0]==$columna_dbf) {
						$reg_found++;                   //Cantidad de registros encontrados
						$reg_found_bandera=false;       //Registro encontrado poner en false.
						$sql_fila=$i;
						//echo " -".$columna_dbf;
						//Para optimizar se puede ver de ir eliminando del registro los items encontrados
						//array_splice
						break;
					}			
				}
				if ($reg_found_bandera) {
					//Dar de alta registros no encontrados... los acumulo en un array luego los hago x funcion
					$reg_no_encontrado[]=$columna_dbf;
					
					//echo "<br> -".$columna_dbf."-<br/>";				
				}
			}
			if ($reg_found_bandera) { 
				//Epa! no estaba el registro, anotemos los datos para luego insertarlos en la SQL
				if ($col==2) {
				        foreach ($arr_cat as $categ) {
				            if (substr($categ[1],0,3)==substr($columna_dbf,0,3)) $cat=$categ[0];        
				        }
        				$toInsertar[$registro_fila][$col]=$cat; //conversion de categorias de fmt txt o id para tabla.
				} elseif (($col==13)||($col==20)||($col==33)||($col==40)||($col==45))  {   //conversion fechas a fmt DB
				        $toInsertar[$registro_fila][$col]=date("Y-m-d", strtotime($columna_dbf));
				        
				} else {
				        $toInsertar[$registro_fila][$col]=htmlentities($columna_dbf);
				}
//echo "- $columna_dbf -".$columna_dbf;
			} else {    //Ok, significa que se encontraba, ahora a verificar campo por campo el registro si hay dif.

				//HACER! y Crear UPDATE
				//Opcion 1:  crear una tabla afiliado_crc32 con id_afiliado y crc. Se debe cargar el CRC de todos los
				//campos de afiliados.dbf en la tabla sql mensionada
				//luego en cada resinc, realizar el crc32 con los datos del DBF y cargar al array. comprar. Los diferentes
				//se hacen un update
				//Opcion 2: FUERZA BRUTA, ir comparando con el array cargado en memoria.
				if ($col==2) {
				        foreach ($arr_cat as $categ) {
				            if (substr($categ[1],0,3)==substr($columna_dbf,0,3)) $cat=$categ[0];        
				        }
        				$toUpdate[$registro_fila][$col]=$cat; //conversion de categorias de fmt txt o id para tabla.
				} elseif (($col==13)||($col==20)||($col==33)||($col==40)||($col==45))  {   //conversion fechas a fmt DB
				        if (date("Y-m-d", strtotime($columna_dbf))!=$arr_sql[$sql_fila][$col])
				        //$toUpdate[$registro_fila][$col]=date("Y-m-d", strtotime($columna_dbf));
				        echo "UPDATE FECHAS en reg.".$sql_fila." col.".$col;
				} else {
				        if (htmlentities($columna_dbf)!=$arr_sql[$sql_fila][$col])
				        echo "UPDATE reg.".$sql_fila." col.".$col."-".htmlentities($columna_dbf)."_".$arr_sql[$sql_fila][$col]."<br/>";
				        //$toUpdate[$registro_fila][$col]=htmlentities($columna_dbf);
				}  

			}			


			$col++;

	    }
//var_dump($table->getColumns());

    }
	    echo "<br>";
echo "<br/>Cantidad de registros totales en SQL:".$cantidad_registros_sql;
echo "<br/>Registros DBF encontrados coincidentes en SQL:".$reg_found;
echo "<br/>Registros DBF no encontrados en SQL:".count($reg_no_encontrado)." (para a sincronizar)<br/>";

foreach ($reg_no_encontrado as $registro) {
  echo "Insertando registro ".$registro."<br/>";

}
foreach ($toInsertar as $afiliado){

	$datos=implode('","', $afiliado);	
	$eco='"'.$datos;
	$eco=substr($eco, 0, -2);
	echo $eco."<br/>";
}
	$table->close();
?>
