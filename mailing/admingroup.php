<?
	$pre="../";  //Nivel del directorio para agregar DB y Pear
	include_once($pre."localconf.php");
	error_reporting(0);
	

	  $plantilla= new HTML_Template_IT();
	  
	  $plantilla->loadTemplatefile("./admingroup.html");



$db = ADONewConnection();
//$db->debug = false ;
$result = $db->Connect("$dbhost", "$dbuname", "", "$dbname");

	
	$consulta = "SELECT * FROM email_grupo order by nombregrupo asc"; 
	$rs=$db->Execute($consulta);	

  if ($rs === false) die("Fallo en la consulta de grupo...");

    while (!$rs->EOF) {
		
		$id=$rs->fields['id'];
		$grupo=$rs->fields['nombregrupo'];
		
		$plantilla->setCurrentBlock("grupos");
				$plantilla->setVariable("id", $id);
				$plantilla->setVariable("nombre", ucfirst($grupo));
		$plantilla->parseCurrentBlock("grupos");		
		
		$rs->MoveNext();
    }

		

	    $plantilla->setVariable($actual,'class="current"');
        $plantilla->setVariable("fin","");
        $plantilla->show();
?>
