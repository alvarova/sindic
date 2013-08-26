<?php
/*
* * Procedimiento para realizar Update automaticos cada determinada secuencia o ejecutando un comando de Cron en el server
* * La secuencia es ingresar al sitio, verificar el archivo de version, si es diferente descargar ambos archivos
* * descomprimir y sobreescribir los archivos, dejar backup del anterior (solo conservar un backup)
*/

$hostfile = fopen("www.gestadmin.com.ar/update/sinfarm/ultimo.zip", 'r');
$accion="";
$ultimo="../update/ultimo.zip";
$backup="../update/backup.zip";
$state=false;  //Estado del backup, si se logra concretar la accion pasa a True, y si no existe el ultimo archivo, primera vez que se usa, pasa a TRUE

/*
 * Descargo archivo ultimo.ver y comparo con la version actualmente instalada. Formato del archivo  VER-1.05.50  =  VER-[version].[subversion].[revision]
 * Subversion y Revision los numeros deben ser de DOS digitos. Para el valor de la version es indistinto.
 */
echo "Verificando version...";

$fhv = fopen("../update/ultimo.ver", 'r');
	$verinstaled = fread($fhv, filesize($fhv));
fclose($fhv);


$hostverfile = fopen("www.gestadmin.com.ar/update/sinfarm/ultimo.ver", 'r');
	$veronline = fread($hostverfile, filesize($hostverfile)); 
fclose($hostverfile);


$temp1=explode("-",$verinstaled); $temp2=explode("-",$veronline); //Quitamos encabezado
//Quito puntos y junto
$instalada=explode(".",$temp1); $verinstaled=implode($instalada);
$ainstalar=explode(".",$temp2); $veronline=implode($ainstalar);

if (is_numeric($verinstaled) && is_numeric($veronline)) {
	if ($verinstaled>=$veronline)  $update=false; //no actualiza 
}
else $update=true; //Actualiza cambio de version/sub o rev. probablemente no existe el archivo ver abajo. Intentar hacer igual.

echo "0k.<br/>";

if ($update){
		echo "Preparando directorio...";
		/*
		 *  Preaparo directorio, procedo a limpiar/elimino el archivo de backup.zip viejo, y renombro ultimo.zip a backup.zip - Verifico que se pueda realizar sin problemas 
		 */
		if (file_exists($ultimo)) 
		{ 
			if (file_exists($backup)) {
				//elimino backup anterior
				if (unlink($backup)) { $accion.="Se elimino backup anterior.<br/>"; }else{ $accion.="No se elimino backup anterior. Protegido o no existia<br/>";}
			}
			$state=rename($ultimo, $backup);
			if ($state) { $accion.="Se actualizo el ultimo backup.<br/>"; } 	else{ $accion.="No se pudo actualizar backup anterior. Existe archivo backup anterior.<br/>";}
		} 
		else
		{
			$state=true; //No existia el ultimo.zip, pasa a TRUE
			$accion.="No existia ni backup.zip, ni anterior.zip, se procede a crear un nuevo punto de restauracion.";
		}

		//
		echo "0k.<br/>";
		echo "Descargando Update...";
		stream_set_timeout(480);
		set_time_limit(480); 
		if ($state ){

		$fh = fopen("../update/ultimo.zip", 'w');

		while (!feof($hostfile)) {

			$output = fread($hostfile, filesize($hostfile));

			fwrite($fh, $output);

		}

		fclose($hostfile);

		fclose($fh);
		echo "0k.<br/>";
		echo "Descomprimiendo...";
		// Se encuentra descargado el archivo, ahora a descomprimir el zip en la carpeta correspondiente

		  require_once('pclzip.lib.php');
		  
		  $archive = new PclZip('latest.zip');
		  $list = $archive->extract(PCLZIP_OPT_PATH, "../sistema/",
									PCLZIP_OPT_REPLACE_NEWER); 

		  if (($v_result_list = $archive->extract()) == 0) {

			die("Error : ".$archive->errorInfo(true));

		  }
		  //Elimino y actualizo la version que se encuentra arriba
		  unlink("../update/ultimo.ver");
		  $fhv1 = fopen("www.gestadmin.com.ar/update/sinfarm/ultimo.ver", 'r'); 	$version = fread($fhv1, filesize($fhv1)); fclose($fhv1);
		  $fhv2 = fopen("../update/ultimo.ver", 'w'); 	fwrite($fhv2, $version); fclose($fhv2);
		  
		echo "0k.<br/>";
		
		}
} else {
	$accion.="No supero la version - Verificar versiones";
}

echo "<pre>";
echo $accion."<br/>";
echo "</pre>";
	
//echo "<meta http-equiv=\"refresh\" content=\"3;url=EXTRA SCRIPT OPTIONS.PHP\" />";

?>
