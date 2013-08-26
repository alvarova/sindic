<!--{fin}-->
	<div class="col_9">
	<h3>Sindicato de Farmacia de Santa Fe</h3>
	<br/>
	<h5>
		Ultima actualizaci&oacute;n de registros {update}
	</h5>
		<div id='mensaje' class="notice {tipo}">{mensaje}</div>
		<span id='procesados'></span>
		<div id='step' style='margin: 50px;' class='center'> 	</div>
		<div id='ready' class="notice success center" style='display:none'>Actualizaci&oacute;n conclu&iacute;da. Puede continuar.</div>
	</div>
	
	<div class="col_3">
	<!--
	<h6>Ultimos Cambios</h6>
	<ul class="checks">
	<li>tation ullamcorper suscipit lobortis</li>
	<li>Nam liber tempor cum soluta nobis</li>
	<li>imperdiet doming id quod mazim</li>
	<li>suscipit lobortis nisl ut aliquip ex</li>
	</ul>
	-->
	<h6>Nuestro Soporte</h6>
	
	<span class="icon social x-large darkgray" data-icon="G"></span>
	<span class="icon social x-large" style="color:orange;" data-icon="5"></span>
	<span class="icon social x-large green" data-icon="3"></span>	
	<span class="icon social x-large blue" data-icon="2"></span>
	<span class="icon social x-large gray" data-icon="S"></span>
	<span class="icon social x-large blue" data-icon="E"></span>
	
	
	<h6>Acceda a nuestro RSS</h6>
	<a class="button orange small" href="#"><span class="icon social" data-icon="r"></span> RSS</a>
	</div>
	
	<hr />
	<div class="col_5">
		<!-- -->
	</div>
	<div class="col_7">
		<h6>Accesos r&aacute;pidos</h6>
	</div>

	<div class="col_3">
	<h6>Afiliados</h6>

	</div>
	
	<div class="col_3">
	<h6>Farmacias</h6>

	</div>
	
	<div class="col_3">
	<h6>Consultas</h6>

	</div>
	
	<div class="col_3">
	<h6>Sistema</h6>

	</div>
<script>
$(document).ready(function(){
	var proceso=0;
	// Ir agregando cantidad de procesos totales para el control en pantalla
	var totalprocesos=3;

	function actualiza_procesados(){
					proceso++;

					$('#step').append($('<div style="margin-top: 20px; float:left; display:block;"><h1 style="border: dotted 2px; padding: 0 10px 0 10px; margin-right: 10px;">'+proceso+'</h1></div>').hide().fadeIn(2000));
					$("#procesados").html('<strong>Total de subprocesos completos '+(proceso)+' de '+(totalprocesos)+'</strong>');
					//alert( "Actualizado registro " + proceso+ " de "+totalprocesos); 
					if (proceso==totalprocesos) {
						$("#mensaje").fadeOut(500);
						$("#step").fadeOut(500);
						$("#ready").fadeIn(3000);
					}
					return true;
	}


		proceso=0;
		//alert('El proceso llevara un instate, por favor aguarde antes de continuar con su trabajo.');
	function inicia_proceso(){
		$("#procesados").html('<strong>Iniciando '+(totalprocesos)+' subprocesos de actualizaci&oacute;n</strong>');		
		$.ajax({
				type: "GET",
				url: "./sincro/sinc_afiliados.php",
				data: "&accion=linkemail",
				success: function(datos){   
					//alert( "Los datos: " + datos);   
					//$("#afiliados").html(datos);
					actualiza_procesados();
				}
		 });
		$.ajax({
				type: "GET",
				url: "./sincro/sinc_familiares.php",
				data: "&accion=linkemail",
				success: function(datos){   
					//alert( "Los datos: " + datos);   
					//$("#familiares").html(datos);
					actualiza_procesados();
				}
		 });
		$.ajax({
				type: "GET",
				url: "./sincro/sinc_farmacias.php",
				data: "&accion=linkemail",
				success: function(datos){   
					//alert( "Los datos: " + datos);   
					//$("#farmacias").html(datos);
					actualiza_procesados();
				}
		 });
	}
  {iniciar}
});
</script>