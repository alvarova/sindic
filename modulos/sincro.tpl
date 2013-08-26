<!--{fin}-->
	<div class="col_12">
	<h4>Sincronizado de sistemas</h4>
		<p>
			Procesando tablas DBF sobre MySql<br/>
			<a class="button small" id='ta' href="">Iniciar Update</a><span id='procesados'></span>
		</p>
		<div  class='col_11'>
		<p>Filtrando afiliados</p>
		<textarea id='afiliados' class='col_10' id="textarea1" placeholder="Aguarde Un Instante...">
		</textarea>
		</div>
		
		<div  class='col_11'>	
		<p>Filtrando familiares</p>
		<textarea id='familiares' class='col_10' id="textarea1" placeholder="Aguarde Un Instante...">
		</textarea>	
		</div>

		<div  class='col_11'>	
		<p>Filtrando farmacias</p>
		<textarea id='farmacias' class='col_10' id="textarea1" placeholder="Aguarde Un Instante...">
		</textarea>	
		</div>
		
	</div>
	
	
	
	<hr />
	<div class="col_5">
		<!-- -->
	</div>


<script>
$(document).ready(function(){
	var proceso=0;
	// Ir agregando cantidad de procesos totales para el control en pantalla
	var totalprocesos=3;

	function actualiza_procesados(){
					proceso++;
					$("#procesados").html('<strong>Total de procesos completos '+(proceso)+' de '+(totalprocesos)+'</strong>');
					return true;
	}

	$("a#ta").click(function(e){ 
		e.preventDefault();
		$("#procesados").html('<strong>Iniciando '+(totalprocesos)+' procesos de actualizacion</strong>');
		proceso=0;
		alert('El proceso llevara un instate, por favor aguarde antes de continuar con su trabajo.');
		$.ajax({
				type: "GET",
				url: "./sincro/sinc_afiliados.php",
				data: "&accion=linkemail",
				success: function(datos){   
					//alert( "Los datos: " + datos);   
					$("#afiliados").html(datos);
					actualiza_procesados();
				}
		 });
		$.ajax({
				type: "GET",
				url: "./sincro/sinc_familiares.php",
				data: "&accion=linkemail",
				success: function(datos){   
					//alert( "Los datos: " + datos);   
					$("#familiares").html(datos);
					actualiza_procesados();
				}
		 });
		$.ajax({
				type: "GET",
				url: "./sincro/sinc_farmacias.php",
				data: "&accion=linkemail",
				success: function(datos){   
					//alert( "Los datos: " + datos);   
					$("#farmacias").html(datos);
					actualiza_procesados();
				}
		 });
	});
  
});
</script>