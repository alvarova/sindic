<?

/*  Consulta 3 tablas

		 Select 	L.*, S.*
		From		lineaproducto L
		Inner join 
					lineaProd_Sucursal  rl
		On			L.cod_linea = rl.cod_lin
		Inner join
					sucursal S
		On			rl.cod_suc = s.cod_sucursal
		Where		rl.cod_suc = @Codigo_Sucursal_Requerido

 */ 

	$pre="../";  //Nivel del directorio para agregar DB y Pear
	include_once($pre."localconf.php");
	error_reporting(0);

	// PAGINADOR
		if (strlen($_POST['p'])>0) {
		$pagina=$_POST['p'];
		}else{
		$pagina=$_GET['p'];}
		$limite=10; 											//-> Cantidad de elementos por pagina
		if ($pagina>0) { $pag=$pagina-1;} else { $pagina=1; }	//-> Control de los limites izquierdos contra Cero.
		$inicio=$pag*$limite;									//-> Calculo del inicio del offset en la DB  
		$cemail=0;   											//-> Cantidad de emails que se cargan si son menores a 10 no hay siguiente.
		$sig=$pagina+1;
		$ant=$pagina-1;
	  
	//Carga de la plantilla  
		$plantilla= new HTML_Template_IT();  
		$plantilla->loadTemplatefile("./adminemail.html");

	//Conexiones a la DB para futuras consultas
		$db = ADONewConnection();
		$db2= ADONewConnection();
		//$db->debug = false ;
		$result = $db->Connect("$dbhost", "$dbuname", "", "$dbname");
		$result2 = $db2->Connect("$dbhost", "$dbuname", "", "$dbname");



	
	if (strlen($_POST['buscar'])) {
		$consulta = "SELECT * FROM email where (`observacion` like '%".$_POST['buscar']."%' OR `direccion` like '%".$_POST['buscar']."%') order by id_email desc limit 0,$limite";
		//echo $consulta;
	}else{	
		$consulta = "SELECT * FROM email $filtro order by id_email asc limit $inicio,$limite"; 
	}
	$rs=$db->Execute($consulta);	

	
	/* Adecuar el listado de grupos para presentarlo como option, ya que en principio no se puede anidar bloques para el parseo al template */
	$consulta2 = "SELECT * FROM email_grupo order by id asc"; 
	$rs2=$db2->Execute($consulta2);	
	$optgrp="";
	$c=0;
	foreach ($rs2 as $grp){
		$ngrp=$rs2->fields['nombregrupo'];
		$idgrp=$rs2->fields['id'];
		if ($ngrp!="") {
			$optgrp.=" <option value='$idgrp'>$ngrp</option> ";
			$c++;
		}
		$plantilla->setCurrentBlock("linkgroup");
				$plantilla->setVariable("idgrupo", $idgrp);
				$plantilla->setVariable("grupolnk", $ngrp);
		$plantilla->parseCurrentBlock("grupos");
		$rs2->MoveNext();
		
	}

//var_dump($c);

  if ($rs === false) die("Fallo en la consulta de emails...");

    while (!$rs->EOF) {
		
		$id=$rs->fields['id_email'];
		$email=$rs->fields['direccion'];
		$nombre=$rs->fields['observacion'];

		$consulta2 = "SELECT L . * , S . *  FROM email_grupo L  INNER JOIN email_link rl ON L.id = rl.id_grupo INNER JOIN email S ON rl.id_email = s.id_email WHERE rl.id_email =$id LIMIT 0 , 1"; 
		//$rs2=$db2->Execute($consulta);
		$rs2=$db2->Execute($consulta2);	
		
		if ($rs2->RecordCount()){
			$nombregrp=$rs2->fields['nombregrupo'];
			$idgrp=$rs2->fields['id']; //id del grupo al que corresponede
		}else  {
			$nombregrp="-ninguno-";
			}
		$plantilla->setCurrentBlock("grupos");
				$plantilla->setVariable("id", $id);
				$plantilla->setVariable("nombre", ucfirst($nombre));
				$plantilla->setVariable("email", $email);
				$plantilla->setVariable("grupo", $nombregrp);
				$plantilla->setVariable("idgrupo", $idgrp);
				$plantilla->setVariable("listag", $optgrp);
				$cemail++;
		$plantilla->parseCurrentBlock("grupos");		
		$nombregrp=""; 
		$idgrp="";
		$rs->MoveNext();
    }


	//Completar el paginador segun los resultados
		if ($cemail<10) { $sig=$pagina; $plantilla->setVariable("psd", "disabled='disabled'"); }
		if ($ant<1) { $plantilla->setVariable("pad", "disabled='disabled'"); }
		$plantilla->setVariable("ps", $sig);
	    $plantilla->setVariable("pa", $ant);
	    $plantilla->setVariable("pagina", $pagina);
	    
	    
	    
	    
	    
	//Parseo de las ultimas variables y cerrar conexiones a la DB
        $plantilla->setVariable("fin","");
        $plantilla->show();
?>
