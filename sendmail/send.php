<?php

	/*
	* Si el envio es por Gmail utilizando Xampp podes comenzar por habilitar openssl en el php.ini
	* si es solo arnet, solo configurar como corresponda.
	*/

  $pre="../";  //Nivel del directorio para agregar DB y Pear
  require_once("../library/PEAR.php");
  require_once("../library/IT.php");
  include_once($pre."localconf.php");

	include "class.smtp.php";
	include "class.phpmailer.php";
	
	$Host = "smtp.arnet.com.ar";						// SMTP servers
	$Username = "stfsfe1818@arnet.com.ar";	// SMTP password
	$Password = "hijos2372";					// SMTP username
	
	

	$From = "stfsfe1818@arnet.com.ar";
	$FromName = "Asociacion Trabajadores de Farmacia";
	
	/*$To = "ppaol@hotmail.com";
	$ToName = "Paola";*/
	
	$Subject = "Mailing - Verificando Tiempo de entrega - Responder";
	$Body = "Realizando envio de prueba. Verificando Horario y tiempo de respuesta del servidor de ARNET <br/> Por favor Responder HORARIO de llegada de email. <br/> Horario SALIDA: 9.18 AM <br/> Javier.-";


      $tpl= new HTML_Template_IT();
      $tpl->loadTemplatefile("../sendmail/send.tpl");



 /*if (isset($_GET['enviar'])){
	$mail = new PHPMailer();

    $mail->IsSMTP();                 	// send via SMTP
   $mail->Host     = $Host; 
    $mail->SMTPAuth = true;     		// turn on SMTP authentication
    $mail->Username = $Username;  
    $mail->Password = $Password; 
    
    $mail->From     = $From;
    $mail->FromName = $FromName;
	

 
 $correo_emisor="sindicatotrabajadoresfarmacia@gmail.com";     //Correo a utilizar para autenticarse
  //con Gmail o en caso de GoogleApps utilizar con @tudominio.com
  $nombre_emisor="Sindicato Trabajadores de Farmaca SFE";               //Nombre de quien envía el correo
  $contrasena="chacabuco1800";          //contraseña de tu cuenta en Gmail
//--------------------------------------------------------
  $mail->SMTPDebug  = 2;                     // Habilita información SMTP (opcional para pruebas)
                                             // 1 = errores y mensajes
                                             // 2 = solo mensajes
 /* $mail->SMTPAuth   = true;                  // Habilita la autenticación SMTP
  $mail->SMTPSecure = "ssl";                 // Establece el tipo de seguridad SMTP
  $mail->Host       = "smtp.gmail.com";      // Establece Gmail como el servidor SMTP
  $mail->Port       = 465;                   // Establece el puerto del servidor SMTP de Gmail
  $mail->Username   = $correo_emisor;         // Usuario Gmail
  $mail->Password   = $contrasena;           // Contraseña Gmail*/

//sindicatotrabajadoresfarmacia@gmail.com,
//chacabuco1800
/*
*/

	//$mail->AddAddress($To , $ToName);
	/*$mail->AddAddress("alvarojaviervera@hotmail.com" , "Alvaro Hotmail");
	$mail->AddAddress("alvarojaviervera@gmail.com" , "Alvaro Gmail");

	$mail->AddReplyTo("alvarojaviervera@gmail.com", "Email de respuesta");

    $mail->WordWrap = 50;				// set word wrap
	$mail->Priority = 1; 
    $mail->IsHTML(true);  
    $mail->Subject  =  $Subject;
    $mail->Body     =  $Body;
    $mail->AddEmbeddedImage("logo.jpg", "1", "Logo",  'base64', "jpg");
	
//	$mail->AddAttachment("logo.jpg");      			// attachment
	$mail->AddAttachment("text-attachment.txt"); 	// attachment
	
    if(!$mail->Send())
    {
        echo "Mailer Error: " . $mail->ErrorInfo;
    }
    else
    {
        echo 'Mensaje enviado '. $lista;
    }
}*/

$tpl->setVariable("fin","");
$tpl->show();

?>