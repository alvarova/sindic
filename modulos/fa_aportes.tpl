<!--{fin}-->
	<div class="col_12" id="listado">
	<h5>Registro de Aportes</h5>
	</div>
	<div class="col_12" id="registrar">
		
	<form name='movimiento' action="./index.php?ac=fa_aportes" method="post">
		<label for="idfarmacia" >Farmacia</label>
		<select id="idfarmacia" name='idfarmacia' class="tooltip" title="Tipee el nombre de la farmacia" onchange="this.form.submit()">
			<option value="" {noselected}> -- Seleccione Farmacia -- </option>
			<!-- BEGIN listacodigos -->
			<option value="{codigo}" {selected}>{concepto}</option>
			<!-- END listacodigos -->
		</select>
		<label for="fecha" >Fecha:</label>
		<input name="fecha" type="text" id="datepicker" />
		
		<br/><br/>
		<label for="comprobante">N&ordm;</label>
		<input name="comprobante" placeholder="{comprobantesiguiente}" type="text" size='3' class="tooltip" title="Ingrese el numero de orden asignad para el comprobante.">
		<label for="concepto">Concepto</label>
		<input name="concepto" placeholder="Detalle " type="text" class="tooltip" title="Describa brevemente el concepto.">
		<label for="importe">Importe</label>
		<input id='importe' name="importe" placeholder="123.45" type="text" class="right" size='7' class="tooltip" title="Ingrese el monto, empleando punto como separador de decimales.">
		<button type="submit" class="small green right" id='enviar'>Registar</button>
	</form>


	</div>

	<div class="col_12" id="listado">
	<form name='busca' action="./index.php?ac=registrocaja" method="post">
		<h6>Afiliados a la fecha {rango} <span id='cant_selected'></span></h6>
		<div class='col_4'></div>
		<div class="col_8" id='buscaregistros'> 
			Buscar registros (A&ntilde;o-Mes - ej. 2013-08)
			<label for="fecha" >Fecha:</label>	<input type="text" value='{fechaconsulta}' name='fechaconsulta' id='fechaconsulta' />

			<button type="submit" class="small green right" id='envia'>Consultar</button>
			 
		</div>
	</form>
	<!-- Table combined Styles -->
	<table class=" tight sortable" cellspacing="0" cellpadding="0">
	<thead>
		<tr>
			<th>DNI</th>
			<th>Nombre</th>
			<th>Ingreso</th>
			<th>Categoria</th>
			<th>Antig.+Adicional</th>
			<th>Sindical</th>
			<th>Art 47 C.C.T.</th>
		</tr>
	</thead>
	<tbody>
	
	<!-- BEGIN listado -->
	
	<tr class='tafiliado {tipoafiliado} {fondo_celda}'>
	<td>
		{dni}
	</td>
	<td>
		{nombre}
	</td>		
	<td >
		{fecha_ingreso}
	</td>
	<td class='center'>
		{categoria}
	</td>
	<td class='right'>
		{antiguedad}
	</td>
	<td class='right'>
		{sindical}
	</td>
	<td class='right'>
		{art47}
	</td>	
	</tr>
	<!-- END listado -->

	</table>

	</div>
	<div class="col_3">

	<div id='imprime'>
		<a class="button pop" id='imprimir' href=""><span class="icon" data-icon="P"></span>Imprime</a>
	</div>

	</div>
		<form id='verafiliado' action='./index.php?ac=afiliados' target='_blank' method='post' >
			<input type='hidden' id='campo' name='campo' value='nro_documento' /><input id='criteriodni' type='hidden' name='criterio' value='' />
		</form>

<script>

$(document).ready(function() {

$("#verafiliado").hide();
// ***********************ELEMENTOS VALIDOS PARA EL FORMULARIO, PURGAR LO ANTERIOR *********************
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!

var yyyy = today.getFullYear();
if(dd<10){dd='0'+dd} if(mm<10){mm='0'+mm} today = yyyy+'-'+mm+'-'+dd;
hoy=today;

    $("#movimiento").submit(function() {
      if (validado()) {
        return true;
      }
      else {
        return false;
      }
    });



	$( "#datepicker" ).datepicker({ 			inline: true 		});
	$( "#datepicker" ).datepicker("option", "dateFormat","yy-mm-dd");
	
	$( "#fechaconsulta" ).datepicker({ 			inline: true 		});
	$( "#fechaconsulta" ).datepicker("option", "dateFormat","yy-mm");	

	$("#enviar").click(function() {
		fecha=$("#datepicker").val();
		importe=$("#importe").val();
		codigo=$("#codigo").val();
		//alert(fecha);
		if ((!fecha) || (fecha==0) || (fecha==null)) { 
			fecha = hoy; 
			$("#datepicker").val(hoy);
		}

		if ((!importe) || (importe==0) || (importe==null))  { 
			alert('Debe ingresar un monto'); 
			event.preventDefault();
		} else if  ((!codigo) || (codigo==0) || (codigo==null)) {
			alert('Debe seleccionar un codigo de imputacion'); 
			event.preventDefault();
		} else { 
			if (!window.confirm("Desea registrar este movimiento en la fecha "+fecha+"?")) {		
				event.preventDefault();
			}
		}
	});

    $("#imprimir").click(function() {
    	event.preventDefault();
    	$("#registrar").hide();$("#imprime").hide();$("#top-of-page").hide();
    	$("#buscaregistros").hide();
    	$(".menu").hide();
    	window.print();
    	//alert("Se envio copia para impresion");
    	$("#registrar").show();$("#imprime").show();$("#buscaregistros").show();$("#top-of-page").show();
    	$(".menu").show();
    });

});

</script>