<!DOCTYPE html>
<html><head>
<title>Administrador de eMails</title>
<meta charset="UTF-8">
<meta name="description" content="Administrador de eMails" />
<script type="text/javascript" src="../js/jquery.min.js"></script>

<!--[if lt IE 9]><script src="../js/html5.js"></script><![endif]-->

<script type="text/javascript" src="../js/prettify.js"></script>                                   <!-- PRETTIFY -->
<script type="text/javascript" src="../js/kickstart.js"></script> 
<script type="text/javascript" src="../js/jquery.jeditable.js"></script> 


<link rel="stylesheet" type="text/css" href="../css/kickstart.css" media="all" /> 
<link rel="stylesheet" type="text/css" href="../style.css" media="all" /> 
 

<style>
#blagrega, #blgrupo
{
	margin: 10px 10px 10px 25px;
	display: none;
}
</style>
{fin}
</head>
<body>
<a id="top-of-page"></a><div id="wrap" class="clearfix">
<div class="col_12">
		
		<h3>Cargador de adjuntos</h3> 
		<p>Seleccione un archivo para enviar por mailing a los contactos o lista que se seleccione. Tenga presente evitar enviar archivos con extensiones ejecutables (exe, com, pif, js, etc.) y minimizar el tamaño de los archivos. <br/>
			El tamaño (espacio que ocupa) idealmente debe ser inferior a 1 Mb. o 1024 Kbytes.<br/>
			Ante cualquier duda, consulte con el administrador.
		</p> 
		<hr>
		<div id='adjuntoactual'>{adjunto}</div>
		 	<form action="./upload.php"  enctype="multipart/form-data"  method="post">
		   	<p>	
		   		Seleccione el archivo a adjuntar <input type="file" name="files">
		   		<input type="hidden" name="up" value='true'>
		   		<input type="submit" value="Subir"> <input type="reset">
		   	</p>
		 	</form>

	<hr class="alt1 col_12" />
	<h5>Presione para continuar o anular el proceso</h5>
	  <a href='#' onclick='window.opener.location.reload(false); window.close();'><button class=" pop small"><span class="icon red small" data-icon="C"></span>Continuar</button></a>
	</div>

</div>
<script>
$(document).ready(function(){
	
	/*
	 * /Permite agregar el elemento seleccionado en el localstorge
	 */
	$("a.edita").click(function(e){ 
		e.preventDefault();
		id=$(this).attr('id');
		
		ta_email=$("#"+id+" .dblclicke").text();
		alert('Agregando email='+ta_email);
		
		var iem=localStorage.getItem('individuallistemails');
		if (iem==null) { iemail=ta_email; }else {	iemail=iem+","+ta_email;}

		var lst=localStorage.getItem('listado');
		if (lst==null) {ulemail='<li class="first last">'+ta_email+'</li>';} else {ulemail=localStorage.getItem('listado')+'<li class="first last">'+ta_email+'</li>';}
		alert("Error: No tiene asignado destinatarios de grupo ni individuales.");
		console.log(ulemail);
		console.log(iemail);
		

		localStorage.setItem('listado', ulemail);
		localStorage.setItem('individuallistemails', iemail);

	});
	

  $('#contenedor').html(localStorage.getItem('mensajeemail'));
  var listado=localStorage.getItem('individuallistemails');
  elementos = listado.replace(",","\r");
  //linea = listado.split(",");
  $('#emails').text(elementos);
  {setscript}
})
</script>

</body>
