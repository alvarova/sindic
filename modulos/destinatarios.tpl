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
		
		<h3>Listado de eMails</h3> 
		
		
	<a href="#" class="addgrupo" ><button class="inset small"><span class="icon" data-icon="p"></span>Agregar eMail</button></a>
	<div id='blagrega'>
		
		<form  id='insertform' name='insertform'>
			<hr />
			<h5>Agregar correo nuevo</h5>
			<label for="nombre" class='tooltip' title="Para finalizar y agregar el contacto presione [ENTER] en el campo de la direccion de email.">Ingrese los datos de contacto y email</label>
			<input id="nombre" type="text" placeholder="Nombre del Contacto" />
			<input id="email" type="text" placeholder="direccion@email.com" class='tooltip' title="Para finalizar y agregar el contacto presione [ENTER]."/>
		</form>
	</div>
	
	
	<br/>
	
	
	<div id='blgrupo'>
		<hr />
		<h5>Detalles de los grupos del email</h5>
		<form  id='detallegrupo' name='detallegrupo'>
			<label for="nombre">EMail de </label>
			<input id="dgnombre" type="text" disabled="disabled" />
			<input id="dgemail" type="text" disabled="disabled" />
			<input id="dgidemail" type="hidden" />
				<label for="select2"> en grupos </label>
					<select id="serializegrupos"  style="width:20%;"  multiple="multiple">
						<!-- BEGIN linkgroup -->
						<option value="{idgrupo}">{grupolnk}</option>
						<!-- END linkgroup -->
					</select>	
			<a href="#" class="agregagrupo" ><button class="inset small"><span class="icon" data-icon="U"></span>Actualizar Grupo</button></a>		
		</form>
	</div>
	
		
	
	<br/>
	<table id="tabla" class="striped sortable">
		<thead><tr>
			<th>Id.eMail</th>
			<th>Nombre</th>
			<th>eMail</th>
			<th>Apuntar</th>
		</tr></thead>
		<tbody>
			<!-- BEGIN grupos -->
			<tr id='{id}'>
				<td>{id}</td>
				<td>
					<span id="{id}" value='{nombre}' class="dblclick">{nombre}</span>
				</td>
				<td>
					<span id="{id}" value='{email}' class="dblclicke">{email}</span>
				</td>
				
				<td>
					<a href="#" id='{id}' class='edita tooltip' title='Agrega este contacto en la lista de emails individuales.'><span class="icon green" data-icon="7"></span></a> 
				</td>
			</tr>
			<!-- END grupos -->
		</tbody>			
	</table>		
	
	<br/>
	<form id='buscador' action='./destinatarios.php' method='post' >
			<label for="buscar">Buscar:</label>
			<input id="buscar"  name='buscar' class="col_3" type="text" placeholder="Filtrar por nombre o email" />
			<input type='hidden' name='p' value='{pagina}'>
	</form>	<br/>
	<a href="./destinatarios.php?p={pa}" class="button pop small" {pad}><span class="icon gray small" data-icon="{"></span> Anterior></a>
	<a href="./destinatarios.php?p={ps}" class="button pop small" {psd}><span class="icon gray small" data-icon="}"></span> Siguiente</a>

	<br/>
	<hr class="alt1 col_12" />
	<h5>Presione Salir para continuar</h5>
			<a href='#' onclick='window.opener.location.reload(false); window.close();'><button class=" pop small"><span class="icon gray small" data-icon="Q"></span> Salir  </button></a><br/><br/>	
	</div>

</div>
<script>
$(document).ready(function(){

	
	/*
	 *  Serializacion y envio de pertenencia de un email a multiples grupos
	 */



	
	/*
	 * Permite actualizar  grupo del email en cuestion
	 */
		$(".optgrp").change(function() {
			idgrp=$(this).val();
			var tr = $(this).closest('tr'),
			idemail = tr[0].id;
			//idemail=$(this).children('tr').val();
			//alert("id email:"+idemail+" idgrupo:"+idgrp);
			$.ajax({
				type: "POST",
				url: "mod_email_update.php",
				data: "idemail="+idemail+"&idgrupo="+idgrp+"&accion=insertlink",
				success: function(datos){   
					alert( "Los datos: " + datos); location.reload();  }
			 });
			
		});
	
	/*
	 *  Funcion para realizar la busqueda y filtrado de emails y nombres
	 */
	 	$('#buscar').keydown(function (e){
		if(e.keyCode == 13){
			e.preventDefault();
			txt=$("#email").val();

				$('#buscador').submit();

			//$('#insertform').trigger('submit');
			//code
			//location.reload();
		}
	});
	
	
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
		
		

		console.log(ulemail);
		console.log(iemail);
		

		localStorage.setItem('listado', ulemail);
		localStorage.setItem('individuallistemails', iemail);

	});
	
	
	/*
	 * //Show-Hide bloque de agrega grupo en la parte superior y pasa las variables a los elementos del formulario superior
	 */
	$("a.ta").click(function(e){ 
		e.preventDefault();
		id=$(this).attr('id');
		//alert('Id='+id);
		 $("div#blgrupo").css('display',"block");	
		ta_nombre=$("#"+id+" .dblclick").text();
		ta_email=$("#"+id+" .dblclicke").text();
		$("#dgnombre").val(ta_nombre);
		$("#dgemail").val(ta_email);
		$("#dgidemail").val(id);
		
		$.ajax({
				type: "POST",
				url: "consulta.php",
				data: "idemail="+id+"&accion=linkemail",
				success: function(datos){   
					//alert( "Los datos: " + datos);   
					$("#serializegrupos").html(datos);
				}
		 });
		
		
		//console.log($("#serializegrupos").html());
		//console.log(" nombre "+ta_nombre+" email "+ta_email);

	});	
	
	
	/*
	 *  Funcion para detectar ENTER en un input y agregar elemento a la DB
	 */
	$('#email').keydown(function (e){
		if(e.keyCode == 13){
			e.preventDefault();
			txt=$("#email").val();
			nme=$("#nombre").val();
			
			if (txt==""){
				alert("Por favor ingrese al menos una dirección de email para agregar.");
			}else{
			 $.ajax({
				type: "POST",
				url: "mod_email_update.php",
				data: "email="+txt+"&nombre="+nme+"&accion=insert",
				success: function(datos){   
					alert( "Los datos: " + datos); location.reload();  }
			 });				
			}
			//$('#insertform').trigger('submit');
			//code
			//location.reload();
		}
	});
	

	
	
	/*
	 * Funcion para eliminar emails
	 */
	
   $("a.elimina").click(function(){
      //alert(this);
       ids=$(this).attr("id");
      pregunta='¿Desea eliminar realmente el email '+ids+'? (Atención: Esta acción no se puede deshacer)';
      if (confirm(pregunta)){
      console.log($(this).attr("id"));
			 $.ajax({
				type: "POST",
				url: "mod_email_update.php",
				data: "id="+ids+"&accion=delete",
				success: function(datos){   
					$(this).parent("tr:first").remove();
					//alert( "Resultado de la operacion: " + datos); 
					location.reload(); }
			 });	
	  }	
   });
   
   
   /*
    * Funcion para hacer editables con un dobleclick los elementos de la tabla Nombres
    */
   $(".dblclick").editable(function(value, settings) { 
     //console.log(this);
     console.log(value);
     console.log(this.id);
     //console.log(settings);
     $.ajax({
        type: "POST",
        url: "mod_email_update.php",
        data: "observacion="+value+"&id="+this.id+"&accion=update",
        success: function(datos){
       alert( "Se guardaron los datos: " + datos);
      }
});
     return(value);
     },
   
   { 
      indicator : "<img src='../images/indicator.gif'>",
      tooltip   : "Doubleclick para editar...",
      event     : "dblclick",
      style  : "inherit"
  });
  


   /*
    * Funcion para hacer editables con un dobleclick los elementos de la tabla eMails
    */  
   $(".dblclicke").editable(function(value, settings) { 
     //console.log(this);
     console.log(value);
     console.log(this.id);
     //console.log(settings);
     $.ajax({
        type: "POST",
        url: "mod_email_update.php",
        data: "direccion="+value+"&id="+this.id+"&accion=update",
        success: function(datos){
       alert( "Se guardaron los datos: " + datos);
      }
});
     return(value);
     },
   
   { 
      indicator : "<img src='../images/indicator.gif'>",
      tooltip   : "Doubleclick para editar...",
      event     : "dblclick",
      style  : "inherit"
  });  
  
  
  
  
})
</script>

</body>
