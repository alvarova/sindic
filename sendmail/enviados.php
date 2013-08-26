<?

/*
 * Modulo para el alta del newsletter envio por  AJAX
 * 
 */ 

	include_once "class.smtp.php";
	include_once "class.phpmailer.php";
	$pre="../";  //Nivel del directorio para agregar DB y Pear
	include_once($pre."localconf.php");


function envia_mail($destina, $mensaje, $adjunto){


	//Configuracion del server
	$Host = "smtp.arnet.com.ar";						// SMTP servers
	$Username = "stfsfe1818@arnet.com.ar";	// SMTP password
	$Password = "hijos2372";					// SMTP username
	
	
	$From = "stfsfe1818@arnet.com.ar";
	$FromName = "Asociacion Trabajadores de Farmacia";
	
	/*$To = "ppaol@hotmail.com";
	$ToName = "Paola";*/
	
	$Subject = "Mailing - Asociacion Trabajadores de Farmacia Santa Fe";
	$Body = $mensaje;


	$mail = new PHPMailer();

    $mail->IsSMTP();                 	// send via SMTP
    $mail->Host     = $Host; 
    $mail->SMTPAuth = true;     		// turn on SMTP authentication
    $mail->Username = $Username;  
    $mail->Password = $Password; 
    
    $mail->From     = $From;
    $mail->FromName = $FromName;
	echo "Set Variable: Ok.<br>";

 
   
//--------------------------------------------------------
    $mail->SMTPDebug  = 1;                     // Habilita información SMTP (opcional para pruebas)
                                             // 1 = errores y mensajes
                                             // 2 = solo mensajes
	//$mail->AddAddress($To , $ToName);
	$destinos=explode(',', $destina);

	foreach ($destinos as $key) {
	 $mail->AddAddress($key , "Contacto");

	}
	

	$mail->AddReplyTo($From, $FromName);

    $mail->WordWrap = 50;				// set word wrap
	$mail->Priority = 1; 
    $mail->IsHTML(true);  
    $mail->Subject  =  $Subject;
    $mail->Body     =  $Body;
    $extension=strtolower(end(explode('.', $adjunto)));
    if (($extension=='jpg')||($extension=='png')||($extension=='jpeg')) {
    		$mail->AddEmbeddedImage($adjunto, "1", "Imagen adjunta",  'base64', $extension);
    }else {
   			$mail->AddAttachment($adjunto); 	// attachment
    }
    //
//	$mail->AddAttachment("logo.jpg");      			// attachment
	echo "Add target and attachment: Ok.<br>";
	
	$mail->Send();
	echo $mail->ErrorInfo;
	/*
    if(!$mail->Send())
    {
        echo "Mailer Error: " . $mail->ErrorInfo;
    }
    else
    {
        echo "Mensaje enviado a ". $destina;
    }*/
    echo "--Terminando envio.<br/>";
}



	error_reporting(0);

	$sql="";
	$c=0;
	$ite=0;
	$ok=0;
	$msg="";
	$dst="";
	$adj="";
	$simultaneo=5; //Cantidad de elementos a enviar al mismo tiempo
	$dst = $_POST["destinatario"];
	$adj = $_POST["adjunto"];
    $msg = addslashes($_POST["msg"]);
    $dia = date('Y-m-d');
    echo "Insertando registros:";
	$sql="INSERT INTO `sindicatofarm`.`enviados` (`id`, `contenido`, `adjunto`, `destinatario`, `fecha`) 
	      VALUES (NULL, '".$msg."', '".$adj."', '".$dst."', '".$dia."')";
    
    if (($msg!="")&&($dst!="")){
	
		$db = ADONewConnection();
		//$db->debug = false ;
		$result = $db->Connect("$dbhost", "$dbuname", "", "$dbname");
		
		
		$rs=$db->Execute($sql);	
		//Arreglo con los ID que tiene  asignado el usr
		if ($rs === false) die("Fallo almacenando mensaje en enviados. Verificar o contactarse con el administrador.".$sql);
		
		$destino=explode(',', $dst);
		echo "Correcto.<br/>";
		echo "Procesando Direcciones:<br/>";
		foreach ($destino as $d) {
			$c++;
			$ite++;
			echo " > [".$c."/".count($destino)."] ".$d."<br>";
			if ($ite==$simultaneo) {
				$ite=0;
				$dest.=','.$d;
				$s=envia_mail($dest, $msg, $adj);
				if ($s) { $ok++; }
			} else {
				if ($ite>1) { $dest.=',';}
				$dest.=$d;
			}
			
		}

	}
//var_dump($sale);
echo "Se procesaron correctamente ".$ok." de un total de".$c." emails.".$sale;
?>

		 
