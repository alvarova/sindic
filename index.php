<?php

/* Sistema de Gestion Sindical
 *  Desarrollado bajo GPL V2
 *  VNDesign - Santa Fe - Argentina 2012
 * 
 * Trabajando bajo MySQL 5.5.16 / Apache 2.2.21 (Win32) PHP 5.3.8 / Versión del cliente: mysqlnd 5.0.8-dev - 20102224 - $Revision: 310735
 */
//Seteo de sesion, variables generales y limpieza de cache.

error_reporting(E_ALL);
//             E_ALL o 0

/************************************************
/******* INICIO DE SESION Y CACHE ****************
/************************************************/
session_start();
header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

/************************************************
//*********** CARGA DE MODULOS ***********
/************************************************/
$pre="";
include_once("localconf.php");
include_once("./library/logger.php");

/************************************************
//*********** VAR GLOBALES ***********
/************************************************/
if (isset($_SESSION['uid'])) { $uid=$_SESSION['uid']; } else { $uid=null; }
if (isset($_SESSION['usuario'])) { $nombre=$_SESSION['usuario']; } else { $nombre=null; $_SESSION['usuario']="";}
$error="";
$pre="./";

/************************************************
//*********** MANEJO DE ERROR SESION ***********
/************************************************/
if (isset($_POST['msg_error']))
{
  if($_POST['msg_error']=='1') {
  		$error=" -> Usuario o contrase&ntilde;a incorrecta.";
  }	else if ($_POST['msg_error']=='2') {
  		$error=" -> Inicie sesion";
  }
	$parseError='$("#slideDiv").show();';
}else{
	$parseError= '$("#slideDiv").hide();';
}
if (is_null($_SESSION['autenticado'])) { $_SESSION['autenticado']=""; }
if (( ! ($_SESSION['autenticado'] == 'SI' && isset($_SESSION['uid'])) ) && (!(isset($_POST['msg_error'])))) {
	    //En caso de que el usuario no este autenticado, crear un formulario y redireccionar a la
	    //pantalla de inicio impidiendo realizar tareas
	echo '<form name="formulario" method="post" action="index.php"><input type="hidden" name="msg_error" value="2"></form>';
	echo '<script type="text/javascript">document.formulario.submit();</script>';
	//echo "Sin errores.";
}else{
	//GetLogIn sin Login, poner boton para LOGUEO
	
	$boton='Cerrar Sesion'; $oculta="class='hide'";
	//if (is_null($_SESSION['usuario'])) { $_SESSION['usuario']=""; }
	$log= new Logger($_SESSION['usuario'], $_SERVER['REMOTE_ADDR']);	  //*********** SETEO DEL LOGGER ***********

}

/************************************************
/************ TOMAR VARIABLES PASADAS ***********
*************************************************/
if (isset($_GET["ac"])) {	$ac=$_GET["ac"]; } else { $ac=""; }
if (isset($_POST["criterio"])) 	$criterio=$_POST["criterio"];
if (isset($_POST["campo"])) 	$campo=$_POST["campo"];
if (isset($_GET["enviado"])) 	$enviado=$_GET["enviado"];
if (isset($_POST["enviado"])) 	$enviado=$_POST["enviado"];



	//--------------------------------------------------------------------
	// HOME
	//--------------------------------------------------------------------
	  
	  $plantilla= new HTML_Template_IT();
	  
	  $plantilla->loadTemplatefile("./head.tpl");
	 
	  if (isset($_GET["id"])) $id=$_GET["id"];
	
	  if ($ac=="") { 
	     include_once("./modulos/inicio.php");
	     $actual='m1';
         }
         
	//--------------------------------------------------------------------
	// CARGA DE CONTENIDO
	//--------------------------------------------------------------------
	
	 elseif ($ac=="afiliados")  {
//            include_once("./modulos/afiliados.php");
            include_once("./modulos/af_ficha.php");            
            $actual='m2';
         }         
	 elseif ($ac=="af_busca")  {
            include_once("./modulos/af_ficha.php");
            $actual='m2';
         }
	 elseif ($ac=="af_listados")  {
            include_once("./modulos/listados.php");
            $actual='m2';
         } 	 	 
     elseif ($ac=="af_impresiones")  {
            include_once("./modulos/impresiones.php");
            $actual='m2';
         } 
	 elseif ($ac=="email")  {
	 		$plantilla->setVariable("setOnHeader",'<script type="text/javascript" src="js/jquery.autocomplete.js"></script>');
            include_once("./modulos/email.php");
            $actual='m4';
         }
	 elseif ($ac=="sincro")  {
	 		$plantilla->setVariable("setOnHeader",'<script type="text/javascript" src="js/jquery.autocomplete.js"></script>');
            include_once("./modulos/sincro.php");
            $actual='m4';
         }         
	 elseif ($ac=="ordenes")  {
	 		//$plantilla->setVariable("setOnHeader",'<script type="text/javascript" src="js/jquery.autocomplete.js"></script>');
            include_once("./modulos/af_ordenes.php");
            $actual='m2';
         }
	 elseif ($ac=="registrocaja")  {
	 		$plantilla->setVariable("setOnHeader",' <link href="css/cupertino/jquery-ui-1.10.3.custom.css" rel="stylesheet"> 	<script src="js/jquery-1.9.1.js"></script> 	<script src="js/jquery-ui-1.10.3.custom.js"></script>');
            include_once("./modulos/registroCaja.php");
            $actual='m6';
         }
	 elseif ($ac=="fa_aportes")  {
	 		$plantilla->setVariable("setOnHeader",' <link href="css/cupertino/jquery-ui-1.10.3.custom.css" rel="stylesheet"> 	<script src="js/jquery-1.9.1.js"></script> 	<script src="js/jquery-ui-1.10.3.custom.js"></script>');
            include_once("./modulos/fa_aportes.php");
            $actual='m3';
         }
	 elseif ($ac=="volcadocaja")  {
	 		//$plantilla->setVariable("setOnHeader",'<script type="text/javascript" src="js/jquery.autocomplete.js"></script>');
            include_once("./modulos/volcadoCaja.php");
            $actual='m6';
         }
	 elseif ($ac=="consultacaja")  {
	 		//$plantilla->setVariable("setOnHeader",'<script type="text/javascript" src="js/jquery.autocomplete.js"></script>');
            include_once("./modulos/consultaCaja.php");
            $actual='m6';
         }
    elseif (($ac=="out") || ($ac=="in"))  {
    		//Registro la salida del usuario. Exclusivamente! viene desde cierrasesion.php
    		if (isset($_GET['name'])) 	{ 	$nombre=$_GET['name']; $accion="Cierre de sesion."; } 
    		else {  
    			$accion="Abre sesion."; 	
    			include_once("./modulos/inicio.php");
	     		$actual='m1';
    		}
    		$log= new Logger($nombre, $_SERVER['REMOTE_ADDR']);	  //*********** SETEO DEL LOGGER ***********
    		$lg=$log->capturar($accion);
    }

	//--------------------------------------------------------------------
	// Muestro la homex
	//--------------------------------------------------------------------
	    if ($error!="") $plantilla->setVariable("error", $error);
	    $plantilla->setVariable("errorJs", $parseError);
	    if (isset($uid) and ($uid!="")) {
	    	$plantilla->setVariable('bloqueanombre','disabled="disabled"');
	    	$plantilla->setVariable('ocultapassword','class="hide"');
	    	$plantilla->setVariable('acceder','Cerrar Sesion');
	    	$plantilla->setVariable('sesionlink','cierrasesion');
	    	$plantilla->setVariable('muestrausuario', ' en terminal: <strong>'.ucfirst($_SESSION['usuario']).'</strong>');
	    }else{
	    	$plantilla->setVariable('bloqueanombre','');
	    	$plantilla->setVariable('muestrausuario','');
	    	$plantilla->setVariable('ocultapassword','');
	    	$plantilla->setVariable('acceder','Acceder');
	    	$plantilla->setVariable('sesionlink','validarusr');

	    }
	    $plantilla->setVariable($actual,'class="current"');
        $plantilla->setVariable("fin","");
        $plantilla->show();


?>
