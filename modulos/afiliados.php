<?php
	require_once("./library/PEAR.php");
	require_once("./library/IT.php");
      
    	$body= new HTML_Template_IT();
    	$body->loadTemplatefile("./modulos/afiliados.tpl");


	/*

$dsn = "Driver={Microsoft Visual FoxPro Driver};SourceType=DBF;SourceDB=C:\\xampp\\htdocs\\sindicato\\Data\\afiliados.DBF;Exclusive=NO;collate=Machine;NULL=NO;DELETED=NO;BACKGROUNDFETCH=NO;";
$rs=odbc_connect($dsn,"","");

$result = odbc_exec ($rs, "select * from C:\\xampp\\htdocs\\sindicato\\Data\\afiliados.DBF ");

	while($array = odbc_fetch_array($result))
	{
		print_r($array);
	}
	*/


    	
     	$body->setVariable("fin","");	 
	$plantilla->setVariable("contenido", $body->get()); 
?>
