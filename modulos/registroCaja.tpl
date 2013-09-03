<!--{fin}-->
	<div class="col_12" id="listado">
	<h5>Movimiento de caja</h5>
	</div>
	<div class="col_12" id="registrar">
	<h6>Registrar movimiento<span id='cant_selected'></span></h6>
		
	<form name='movimiento' action="./index.php?ac=registrocaja" method="post">
		<label for="codigo" >C&oacute;digo</label>
		<select id="codigo" name='codigo' class="tooltip" title="Tipee el codigo para el movimiento a registrar">
			<!-- BEGIN listacodigos -->
			<option value="{codigo}">{concepto}</option>
			<!-- END listacodigos -->
			<option value="0" >&nbsp;&nbsp;&nbsp;&nbsp;-- De Ingresos --</option>			
			<option value="999">999 - Ingreso</option>	
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
		<h6>Elementos correspondientes a la fecha {rango} <span id='cant_selected'></span></h6>
		<div class='col_4'></div>
		<div class="col_8"> 
			Buscar registros (A&ntilde;o-Mes - ej. 2013-08)
			<label for="fecha" >Fecha:</label>	<input type="text" value='{fechaconsulta}' name='fechaconsulta' id='fechaconsulta' />

			<button type="submit" class="small green right" id='envia'>Consultar</button>
			 
		</div>
	</form>
	<!-- Table combined Styles -->
	<table class=" tight sortable" cellspacing="0" cellpadding="0">
	<thead>
		<tr>
			<th>Fecha</th>
			<th>N&ordm;</th>
			<th>Codigo</th>
			<th>Gastos Admin.</th>
			<th>Importe</th>
			<th>N&ordm;</th>
			<th>Codigo</th>
			<th>Gastos Sector</th>
			<th>Importe</th>			
			<th>Saldo</th>
			
		</tr>
	</thead>
	<tbody>
	
	<!-- BEGIN listado -->
	
	<tr class='tafiliado {tipoafiliado} {fondo_celda}'>
	<td>
		{fecha}
	</td>
	<td>
		{nroi}
	</td>		
	<td >
		{codigoi}
	</td>
	<td>
		{conceptoi}
	</td>
	<td class='right'>
		{importei}
	</td>
	<td>
		{nrod}
	</td>
	<td>
		{codigod}
	</td>
	<td>
		{conceptod}
	</td>
	<td class='right'>
		{imported}
	</td>
	<td class='right'>
		{saldo}
	</td>	
	</tr>
	<!-- END listado -->

	</table>
	<h6>Total de {totallista}</h6>
	</div>
	<div class="col_3">



	</div>
		<form id='verafiliado' action='./index.php?ac=afiliados' target='_blank' method='post' >
			<input type='hidden' id='campo' name='campo' value='nro_documento' /><input id='criteriodni' type='hidden' name='criterio' value='' />
		</form>
	<hr />
	<div class='fixed-div'></div>

<script>

$(document).ready(function() {


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
});

</script>