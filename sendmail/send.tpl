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
		
		<h3>Proceso de envio de eMails</h3> 
		<h5>Vista previa</h5> 
		<hr>
		<h6>Asunto: <span id='asunto'></span> </h6>
		<div id='contenedor'></div>
		<hr>
		<div class='col_9'>
			<h5>Archivo adjunto</h5> 
			<span id='adjunto'></span>
		</div>
		<div class='col_9'>
			<h5>Lista de las direcciones destino</h5> 
			<textarea id='emails' rows="1" class='col_8'></textarea>
		</div>

	<br/>
	<hr class="alt1 col_12" />
	<h5>Presione para continuar o anular el proceso</h5>
	  <a href='#' onclick='window.opener.location.reload(false); window.close();'><button class=" pop small"><span class="icon red small" data-icon="x"></span> Anular  </button></a>
	  <a href='#' id='procesar'><button class=" pop small"><span class="icon green small" data-icon="C"></span> Enviar  </button></a>
</div>
	<div id="resultado" class="col_12">
		<h5>Ultima Operación</h5>
	</div>
	<hr>
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
//	$dst = $_POST["destinatario"];  	$adj = $_POST["adjunto"];      $msg = addslashes($_POST["msg"]);
	$("#procesar").click(function(e){ 
		e.preventDefault();
		console.log('pasa por edita');
		$('#resultado').append("<div id='wait' class='col_3'>-Aguarde un instante-<br/> <img src='loading6.gif'/></div>");
		$.ajax({
				type: "POST",
				url: "enviados-swift.php",
				data: "asunto="+localStorage.getItem('asunto')+"&destinatario="+localStorage.getItem('individuallistemails')+"&adjunto="+localStorage.getItem('adjunto')+"&msg="+localStorage.getItem('mensajeemail'),
				success: function(datos){   
					alert( "Resultado de la operacion: " + datos); 
					//window.opener.location.reload(false); 
					//window.close();
					$("#wait").remove();  
					$('#resultado').append(datos);

				}
		 });
		// Implementar AJAX para levanta la info en la DB y procesar el envio del mailing
	});	

  $('#contenedor').html(localStorage.getItem('mensajeemail'));
  $('#adjunto').html("<a href='"+localStorage.getItem('adjunto')+"' target=_blank>Verificar Archivo</a>");
  var listado=localStorage.getItem('individuallistemails');
  elementos = listado.replace(",","\r");
  //linea = listado.split(",");
  $('#emails').text(elementos);
  $('#asunto').html(localStorage.getItem('asunto'));
})
</script>

</body>
