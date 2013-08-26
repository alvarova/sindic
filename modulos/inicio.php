<?php
	require_once("./library/PEAR.php");
	require_once("./library/IT.php");
      
    	$body= new HTML_Template_IT();
    	$body->loadTemplatefile("./modulos/inicio.tpl");
    	
		$db= ADONewConnection();
			//$db->debug = true ;
		$result = $db->Connect("$dbhost", "$dbuname", "", "$dbname");
    	$consulta = "SELECT * FROM parametros where variable= 'sincronizacion'";
		$rs=$db->Execute($consulta);
		//var_dump($rs);
		$fecha=$rs->fields['valor'];
    	$body->setVariable("update", $fecha);	
		$ultimo = strtotime($fecha);
		$ayer = mktime(0,0,0,date("m"),date("d")-1,date("Y"));
		if ($ultimo<=$ayer) {
			    	$body->setVariable("mensaje", "Atenci&oacute;n: registro desactualizado. Aguarde un instante o <a href='./index.php?ac=sincro'>acceda al sincronizador</a>");	
			    	$body->setVariable("tipo", "error center");	
			    	$body->setVariable("iniciar", "inicia_proceso();");	

		} else {
			    	$body->setVariable("mensaje", "El registro se encuentra actualizado dentro de las 24hs. Recuerde renovarlo peri&oacute;dicamente.");	
			    	$body->setVariable("tipo", "warning center");	
		}
     	$body->setVariable("fin","");	 
	$plantilla->setVariable("contenido", $body->get()); 
?>
