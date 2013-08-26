<!--{fin}-->


<div class="col_12">
	<form action="index.php?ac=ordenes&id_afiliado={id_afiliado}" id='addorden' name='addorden' method='post' class="vertical">


	 
		<h4>Ordenes de Consulta</h4>

		
		<div class="col_2 right">
			<label for="text1" >Afiliado</label>	<input id="text1" type="text" disabled='disabled' class='right' value='{dni}'/>
		</div>

		<div class="col_4">
			<label for="text1" >Nombre</label>	<input id="text1" type="text" disabled='disabled' value='{nombre}'/>
		</div>

		<div class="col_4">
			<label for="select1">Familiares</label>
			<select id="select1" multiple="multiple" >
			<!-- BEGIN familiares -->				
				<option value="" class='tooltip' title="{familiartt}">{familiar}</option>
			<!-- END familiares -->
			</select>

		</div>	

		<div class="clear"></div>

		<div class="col_2 right">
			<label for="nro_orden">Nro.Orden</label>	
			<input id="nro_orden" type="text" autocomplete="on" autofocus="autofocus" name="nro_orden" class="tooltip-bottom" title="Ingrese el primer numero de orden de la chequera"/>
		</div> <!-- Fin contenedor  -->

		<div class="col_2">
			<label for="importe">Monto</label>	
			<input id="importe" type="text"  value='{importe}' autocomplete="on" name="importe" class="tooltip-bottom" title="Ingrese el valor - Omitir para PMI, solo tildar Orden Sin Cargo."/>
		</div>
		<div class="col_4">
			<label for="observacion">Observaci&oacute;n / Fecha Venc.</label>	
			<input id="observacion" type="text"  value='{observacion}' autocomplete="on" name="observacion" class="tooltip-bottom" title="Ingrese el valor/observacion si correspondiese - Para PMI solo tildar Orden Sin Cargo."/>
		</div>		
		
		<div class="col_4">
			<fieldset class="tooltip-bottom" title="Si corresponde ordenes por PMI tilde para actualizar entrega mensual.">
				<legend>Orden sin cargo</legend>
				<input type="checkbox" id="check0" name="embarazo"/> <label for="check1" class="inline">Embarazo</label><br/>
				<!-- BEGIN ordenespmi -->
				 <input type="checkbox" id="check1" name="{pmi}"/> <label for="check1" class="inline">Plan Materno Infantil</label>
				<!-- END ordenespmi -->
			</fieldset>
		</div>	
		
		<div class="clear"></div>
	 	<div class="col_11 ">
	 		<div class="col_7 right"><input type='hidden' name='timestamp' value='{timestamp}'/></div>
	 		<div class="col_2 right"><input type="submit" value="Ingresar" /></div>
	 	</div>
	</form>
	<hr class="alt2" />

	    
		<div >
			<h5>Ordenes en los ultimos 30 d&iacute;as</h5><h6>y Ultimas PMI - 90 d&iacute;as</h6>
			<form class="vertical">
			 <!-- BEGIN ordenes -->
			<div class="col_2">
				<label for="text1">Fecha</label>	
				<input value="{ofecha}" id="text1" type="text" disabled="disabled"/>
			</div>
			<div class="col_2">
				<label for="text1">Nro. de Orden</label>	
				<input value="{onro_orden}" id="text1" type="text" disabled="disabled"/>
			</div>
			<div class="col_4">
				<label for="text1">Monto/Observaci&oacute;n</label>	
				<input value="{omonto-observacion}" id="text1" type="text" disabled="disabled"/>
			</div>
			<hr class="alt2" />
			<!-- END ordenes -->
		</form>
		</div>

</div>
<script LANGUAGE="JavaScript">
<!--
var existe_pmi={varpmi};
$('#addorden').submit(function() {
	var importe=$("#importe").val();
	var observa=$("#observacion").val();
	var ordenes=$("#nro_orden").val();
	
	var submit=false;
	
	if($("#check1 #check0").is(':checked')) {  
            var submit=confirm("El Afiliado posee en vigencia una chequera de PMI correspondiente a este periodo (ver listado). Desea continuar de todas maneras?");
    }
	if (ordenes=="") { 
		alert('Falta el numero de chequera (numero de la primera orden en la misma)');  
	}else if (importe=="" && observa=="") { 
		alert('Sin un valor para el costo de la chequera,  al menos debe ingresar una observacion.'); 
		submit=false;
	} else {
		var agree=confirm("Se registrara la entrega de la chequera al Afiliado. Desea continuar?");
		if (agree) {
			submit=true;
			location.replace('./index.php?ac=ordenes');
		} else {
			submit=false;
		}
	}
 
	return submit;
});
 $("#check1").click(function() {
	if($("#check1").is(':checked')) {  
	        if (existe_pmi)  alert("Observacion: Existe registrado y aun en vigencia una chequera de PMI dentro del periodo (ver listado).");
	    }
 });


// -->
</script>