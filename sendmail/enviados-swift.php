<?

/*
 * Modulo para el alta del newsletter envio por  AJAX
 * 
 */ 

	require_once 'swift/swift_required.php';
	$pre="../";  //Nivel del directorio para agregar DB y Pear
	include_once($pre."localconf.php");

function envia_gmail()
{


	$pEmailGmail = 'alvarojaviervera@gmail.com';
	$pPasswordGmail = 'dni26907257';
	$pFromName = 'Alvaro desde GMail'; //display name

	$pTo = 'alvarojaviervera@gmail.com'; //destination email
	$pSubjetc = "Hello MundialSYS"; //the subjetc 
	$pBody = '<html><body><p>Hello MundialSYS</p></html></body>'; //body html

	$transport = Swift_SmtpTransport::newInstance('smtp.googlemail.com', 465, 'ssl')
	            ->setUsername($pEmailGmail)
	            ->setPassword($pPasswordGmail);

	$mMailer = Swift_Mailer::newInstance($transport);

	$mEmail = Swift_Message::newInstance();
	$mEmail->setSubject($pSubjetc);
	$mEmail->setTo($pTo);
	$mEmail->setFrom(array($pEmailGmail => $pFromName));
	$mEmail->setBody($pBody, 'text/html'); //body html

	if($mMailer->send($mEmail) == 1){
	    echo 'send ok';
	}
	else {
	    echo 'send error';
	}


}

function envia_live($asunto="Mailing - Asociacion Trabajadores de Farmacia Santa Fe", $destina, $mensaje, $adjunto){

	$transport = Swift_SmtpTransport::newInstance('smtp.live.com', 25, 'TLS')
	  ->setUsername('alvarojaviervera@hotmail.com')
	  ->setPassword('d26907257')
	  ;
	
	$mailer = Swift_Mailer::newInstance($transport);

	// Create a message
	$target=explode(",", $destina);
	$message = Swift_Message::newInstance($asunto)
	  ->setFrom(array('alvarojaviervera@hotmail.com' => ' Asociacion Trabajadores de Farmacia Santa Fe (Sistemas)'))
	  ->setTo($target) //array('alvarojaviervera@gmail.com', 'alvarojaviervera@hotmail.com' => 'Javier en hotmail'))
	  ->setBody($mensaje, 'text/html')
	  ;

	// Send the message
	$result = $mailer->send($message);
	if ($result)
	{
  		echo "Estado: Enviado.\n";
	} else {
  		echo "Estado: Fallo al enviar\n";
	}
	return($result);
}

function envia_mail($asunto="Mailing - Asociacion Trabajadores de Farmacia Santa Fe", $destina, $mensaje, $adjunto){

	$transport = Swift_SmtpTransport::newInstance('smtp.arnet.com.ar', 25)
	  ->setUsername('stfsfe1818@arnet.com.ar')
	  ->setPassword('hijos2372')
	  ;
	
	$mailer = Swift_Mailer::newInstance($transport);

	// Create a message
	$target=explode(",", $destina);
	$message = Swift_Message::newInstance($asunto)
	  ->setFrom(array('stsfe1818@arnet.com.ar' => ' Asociacion Trabajadores de Farmacia Santa Fe'))
	  ->setTo($target) //array('alvarojaviervera@gmail.com', 'alvarojaviervera@hotmail.com' => 'Javier en hotmail'))
	  ->setBody($mensaje, 'text/html')
	  ;

	// Send the message
	$result = $mailer->send($message);
	if ($result)
	{
  		echo "Estado: Enviado.\n";
	} else {
  		echo "Estado: Fallo al enviar\n";
	}
	return($result);
}



error_reporting(-1);   // E_ALL   o   0

$sql="";
$c=0;
$ite=0;
$ok=0;
$msg="";
$dst="";
$adj="";
$simultaneo=5; //Cantidad de elementos a enviar al mismo tiempo

$asu = $_POST["asunto"];
$dst = $_POST["destinatario"];
$adj = $_POST["adjunto"];
$msg = addslashes($_POST["msg"]);
$dia = date('Y-m-d');
echo "Insertando registros:\n";
$sql="INSERT INTO `sindicatofarm`.`enviados` (`id`, `contenido`, `adjunto`, `destinatario`, `fecha`) VALUES (NULL, '".$msg."', '".$adj."', '".$dst."', '".$dia."')";
echo " - Verificando parametros minimos - \n"; 
if (($msg!="")&&($dst!="")){
		echo "Enviando a DB.\n-";
		$db = ADONewConnection();
		//$db->debug = false ;
		$result = $db->Connect("$dbhost", "$dbuname", "", "$dbname");
		
		
		//$rs=$db->Execute($sql);	
		//Arreglo con los ID que tiene  asignado el usr
		//if ($rs === false) die("\nFallo almacenando mensaje en enviados. Verificar o contactarse con el administrador.".$sql);
		
		echo "Correcto.\n";
		echo "Procesando Direcciones:\n";
		
		$s=envia_mail($asu,$dst, $msg, $adj);
		//$s=envia_live($asu,$dst, $msg, $adj);
		//$s=envia_gmail();


} else { echo " - Atencion:Sin elementos minimos -\n "; }
echo "- fin proceso -\n";
//var_dump($sale);
//echo "Se procesaron correctamente ".$ok." de un total de".$c." emails.".$sale;

?>