<?php

	/*
	* Para subir archivos y purgar el anterior. Nada loco.
	* si es solo arnet, solo configurar como corresponda.
	*/

  $pre="../";  //Nivel del directorio para agregar DB y Pear
  require_once("../library/PEAR.php");
  require_once("../library/IT.php");
  include_once($pre."localconf.php");

  $tpl= new HTML_Template_IT();
  $tpl->loadTemplatefile("../sendmail/upload.tpl");
  $value="";
  if (isset($_POST['up'])) {  $value=$_POST['up'];}
  if ($value) {
    $sale='./adjuntos/';
  if(!empty($_FILES['files']['name'])){
    var_dump($sale.$_FILES['files']['name']);
      if (is_uploaded_file($_FILES['files']['tmp_name'])) {
        if (move_uploaded_file($_FILES['files']['tmp_name'], $sale.$_FILES['files']['name'])) {
           $tpl->setVariable("adjunto", '<div class="notice success">Se completo el proceso. El archivo se encuentra adjunto.</div>');
           $tpl->setVariable("setscript", 'localStorage.setItem("adjunto","'.$sale.$_FILES['files']['name'].'");');
        }else{
           $tpl->setVariable("adjunto", '<div class="notice error">No se pudo completar el proceso. Verificar el archivo adjunto.</div>');
           $tpl->setVariable("setscript", 'localStorage.setItem("adjunto","");');

        }

      }
   }

     
}else{
  $tpl->setVariable("adjunto", '<div class="notice warning">Seleccione un archivo segun las especificaciones.</div>');
  $tpl->setVariable("setscript", 'localStorage.setItem("adjunto","");');
}

$tpl->setVariable("fin","");
$tpl->show();

?>