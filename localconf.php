<?php
//Localconf para portada, es neceario modificar Localconf en el administrador o
//evaluar unificar la ubicacion de los archivos para evitar crear 2 localconf.php

include_once($pre."library/IT.php");     //Carga PEAR
include_once($pre."library/adodb_lite/adodb.inc.php"); 
require_once($pre."library/PEAR.php");


$dbuname="sindicatofarm";
$dbpass="";
$dbhost="localhost";
$dbname="sindicatofarm";
$ubicacion_imagen="recursos/imagenes/";

?>
