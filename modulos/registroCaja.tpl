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
			<option value="0">-- Ingreso Caja --</option>			
			<option value="999">Ingreso</option>	
		</select>
		<label for="fecha" >Fecha:</label>
		<input name="fecha" type="text" id="datepicker" />
		
		<br/><br/>
		<label for="comprobante">N&ordm;</label>
		<input name="comprobante" placeholder="102" type="text" size='3' class="tooltip" title="Ingrese el numero de orden asignad para el comprobante.">
		<label for="concepto">Concepto</label>
		<input name="concepto" placeholder="Detalle " type="text" class="tooltip" title="Describa brevemente el concepto.">
		<label for="importe">Importe</label>
		<input id='importe' name="importe" placeholder="123.45" type="text" class="right" size='7' class="tooltip" title="Ingrese el monto, empleando punto como separador de decimales.">
		<button type="submit" class="small green right" id='enviar'>Registar</button>
	</form>


	</div>

	<div class="col_12" id="listado">
	
	<h6>Elementos correspondientes a la fecha {rango} <span id='cant_selected'></span></h6>
	<div class='col_4'></div>
	<div class="col_8"> 
		Buscar registros (Mes/A&ntilde;o) <input type='text' size='10' value='1' id='idInicio'/>  <a class="button square small" id='m50' href="">Consultar</a>
	</div>
	<!-- Table combined Styles -->
	<table class="striped tight sortable" cellspacing="0" cellpadding="0">
	<thead>
		<tr>
			<th>Fecha</th>
			<th>N&ordm;</th>
			<th>Codigo</th>
			<th>Concepto</th>
			<th>Importe</th>
			<th>N&ordm;</th>
			<th>Codigo</th>
			<th>Concepto</th>
			<th>Importe</th>			
			<th>Saldo</th>
			
		</tr>
	</thead>
	<tbody>
	<!-- BEGIN listado -->
	
	<tr class='tafiliado {tipoafiliado}'>
	<td>
		<span class='todos'>{nro}</span><span class='solo{tipoafiliado}'>{nrotipoafiliado}</span> | <a href='#' class='{nro}' id='{id_afiliado}'>{id_afiliado}</a>
	</td>	
	<td class="tooltip-bottom" title="Tildar, si desea generar un listado en formato PDF.">
		{nombre}
	</td>
	<td>
		{razon_social}
	</td>
	<td>
		{localidad}
	</td>
	<td>
		{fechaalta}
	</td>
	<td>
		<input type="checkbox" class='chk' id="chk{id_afiliado}" name='chk{nro}' value='{id_afiliado}'/>{opciones}
	</td>
	<td>
		{fechaalta}
	</td>
	<td>
		{fechaalta}
	</td>
	<td>
		{fechaalta}
	</td>	
	</tr>
	<!-- END listado -->

	</table>
	<h6>Total de {totallista}</h6>
	</div>
	<div class="col_3">


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