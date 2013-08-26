<?php
	$pre="../";  //Nivel del directorio para agregar DB y Pear
	require_once("./library/PEAR.php");
	require_once("./library/IT.php");
	include_once($pre."localconf.php");
      
    	$body= new HTML_Template_IT();
    	$body->loadTemplatefile("./modulos/email.tpl");


    	$db2 = ADONewConnection();
		$db->debug = false ;
		$result2 = $db2->Connect("$dbhost", "$dbuname", "", "$dbname");
		
		

		//Enlistamos todos los 
		$sql="SELECT *  FROM  `email_grupo` WHERE 1";
		$rs2=$db2->Execute($sql);	
		
			if ($rs2 === false) die("Fallo consultando lista de grupos...".$sql);
			$val=$db2->Affected_Rows();
			$c=0;		
			$sale="";
			while (!$rs2->EOF) {	
					$c++;
					$id=$rs2->fields['id'];
					$grp=$rs2->fields['nombregrupo'];
					$body->setCurrentBlock("grps");
						$body->setVariable("grupodb", $grp);	 
						$body->setVariable("grupoval", $id);
					$body->parseCurrentBlock("grps");
					$rs2->MoveNext();
			}
    	
     	$body->setVariable("fin","");	 
	$plantilla->setVariable("contenido", $body->get()); 
?>
