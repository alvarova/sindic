<!--{fin}-->
	<div class="col_12">
	<h4>Impresiones</h4>
	<form id="listas" name="listados" method="post" action="./index.php?ac=af_impresiones" target='_self'>

		<div class="col_3">
		<label for="select1">Tipo de filtrado</label>
		<select id="filtro" name='filtro'>
			<option value="" selected="selected">--Seleccione Filtro--</option>
			<option value="0" >Afiliados por Cumplea&ntilde;os</option>
			<option value="1" >Padron (todos)</option>
			<option value="2" >Padron Afiliados Sindicales</option>
			<option value="3" >Padron No Afiliados</option>
		</select>

		</div>

		<div class="col_3 bloques" id="pormes">
			<label for="select1">Mes de filtrado</label>
			<select id="mes" name='mes'>

				<!-- BEGIN meslista -->
					<option value="{mesval}" {ss}>{mesnombre}</option>
				<!-- END meslista -->		
			</select>
		</div>

		<div class="col_3 bloques" id="solointerior">
			<fieldset class="tooltip-bottom" title="Tildar, para filtrar solo interior - caso contrario muestra todo.">
				<legend>Interior</legend>
				<input type="checkbox" id="interior" name="interior"/> <label for="check1" class="inline">Solo Interior</label>
			</fieldset>
		</div>

		<div class="col_3 bloques" id="textotitulo">
			<fieldset class="tooltip-bottom" title="Titulo para asignar al remito.">
				<legend>Texto para Remito</legend>
				<input type="text" id="titulo" name="titulo"/> <label for="check1" class="inline">Titulo</label><br/>
				<input type="checkbox" id="remito" name="remito" checked=checked/> <label for="check1" class="inline">Generar Remitos</label>		
			</fieldset>
		</div>

		<div class="col_3 bloques" id="generarotulo">
			<fieldset class="tooltip-bottom" title="Tildar, si desea generar adem&aacute;s del remito, r&oacute;tulos de envio.">
				<legend>Rotular</legend>
				<input type="checkbox" id="rotulo" name="rotulo"/> <label for="check1" class="inline">Generar R&oacute;tulos PDF</label>
			</fieldset>
		</div>

		<div class="col_2 right">
			<br/>
			<button type="submit" class="small" >Generar</button>
		</div>
		<input type='hidden' name='lista' id='lista' value=''/>
	</form>

	</div>


	<div class="col_12" id="listado">
	<h5>Listados personalizados {titulo}</h5>
	<h6>Elementos seleccionados <span id='cant_selected'></span></h6>
	<div class="col_8"> 
		A partir del Id <input type='text' size='3' value='1' id='idInicio'/> 
		<a class="button square small" id='m10' href="">Marcar 10</a>
		<a class="button square small" id='m50' href="">Marcar 50</a>
	</div>
	<!-- Table combined Styles -->
	<table class="striped tight sortable" cellspacing="0" cellpadding="0">
	<thead>
		<tr>
			<th>ID</th>
			<th>Afiliado</th>
			<th>Farmacia</th>
			<th>Localidad</th>
			<th>Fecha Alta</th>
			<th>Seleccion</th>
			
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
var selected = new Array(50);
var countselect=0;
var vartemp="";
	$('.bloques').hide();
	$('#listado').hide();

	$('#pdf').change(function(){
		if ($('#pdf').is(':checked'))  {
		  	$('#listas').attr('target', '_blank');
		  } else {
		  	$('#listas').attr('target', '_self');
		  }
	}) 

    $("#filtro").change(function() {
        var n= ($(this).val());
        switch(n)
		{
		case '0':
		  $('.bloques').hide();
		  $('#pormes').show();
		  $('#listas').attr('target', '_blank');
		  $('#solointerior').show();
		  $('#generarotulo').show();
		  $('#listado').hide();

		  break;
		
		case '1':
		  $('.bloques').hide();
		  
		  $('#listas').attr('target', '_blank');
		  $('#solointerior').hide();
		  $('.0').show();
		  $('.1').show();	
		  $('.todos').show();
		  $('.solo0').hide();
		  $('.solo1').hide();	  
		  $('#generarotulo').show();
		  $('#textotitulo').show();
		  $('#listado').show();

		  break;

		case '2':
		  $('.bloques').hide();
		  $('.0').hide();
		  $('.1').show();
  		  $('.todos').hide();
		  $('.solo0').hide();
		  $('.solo1').show();		  
		  $('#listas').attr('target', '_blank');
		  $('#solointerior').hide();
		  $('#generarotulo').show();
		  $('#textotitulo').show();
		  $('#listado').show();

		  break;

		case '3':
		  $('.bloques').hide();
		  $('.0').show();
		  $('.1').hide();
  		  $('.todos').hide();
		  $('.solo0').show();
		  $('.solo1').hide();
		  $('#listas').attr('target', '_blank');
		  $('#solointerior').hide();
		  $('#generarotulo').show();
		  $('#textotitulo').show();
		  $('#listado').show();

		  break;

		default:
		  $('.bloques').hide();
		  
		  $('#listas').attr('target', '_blank');
		  $('#solointerior').hide();
		  $('#generarotulo').show();
		  $('#textotitulo').show();
		  $('#listado').show();
		  //$('#listafiliados').load('./modulos/ajax_listafiliados.php');
		}
    });
    $("#interior").click(function() {
        var checkBoxes = $("input[name=rotulo]");
        checkBoxes.attr("checked", !checkBoxes.attr("checked"));
    }); 

    $("#m10").click(function() {
        var posInicio = $("#idInicio").attr('value');
        //checkBoxes.attr("checked", !checkBoxes.attr("checked"));
        event.preventDefault();
        
        var i=0;
        for (i=1; i<11; i++){
        	valId=Number(posInicio-1)+Number(i);
        	$('input[name=chk'+valId+']').attr('checked',true);
        	
        }
       avisaChecked(); 

    }); 

    $("#m50").click(function() {
        var posInicio = $("#idInicio").attr('value');
        //checkBoxes.attr("checked", !checkBoxes.attr("checked"));
        event.preventDefault();
        
        var i=0;
        for (i=1; i<51; i++){
        	valId=Number(posInicio-1)+Number(i);
        	$('input[name=chk'+valId+']').attr('checked',true);
        	
        }
        
        avisaChecked();
    }); 

    function avisaChecked(){
    	var names = [];
    	$('#listado input:checked').each(function() {
            names.push($(this).val());
        });
		var cnt = 0;
		for (var i = 0; i < names.length; i++) {
    		if (names[i] !== undefined) {
        		++cnt;
    		}
		}
		$('.fixed-div').html('<span>Cantidad de elementos: '+cnt+'</span>');
		$('.fixed-div').show(); // <-- time in milliseconds  
		setTimeout(function() { $('.fixed-div').fadeOut('fast');   }, 2000); // <-- time in milliseconds  
		vartemp=names.join();
		console.log(vartemp);
		$('#lista').attr('value', vartemp);
    }


    $(".chk").click(function(){
    	// $valores[] = $(this).val());
    	
    	var names = [];
        $('#listado input:checked').each(function() {
            names.push($(this).val());
        });

    	countselect++;
		console.log($(this).val());
		console.log(names);
		
		var cnt = 0;
		for (var i = 0; i < names.length; i++) {
    		if (names[i] !== undefined) {
        		++cnt;
    		}
		}
		$('.fixed-div').html('<span>Cantidad de elementos: '+cnt+'</span>');
		$('.fixed-div').show(); // <-- time in milliseconds  
		setTimeout(function() { $('.fixed-div').fadeOut('fast');   }, 2000); // <-- time in milliseconds  

		vartemp=names.join();
		console.log(vartemp);
		$('#lista').attr('value', vartemp);

    });

    $("#listas").submit(function() {
      if (validado()) {
        return true;
      }
      else {
        return false;
      }
    });

    function validado(){
    	var sale=true;

    	if ($('#filtro').attr('value')==1){
	    	if (($('#lista').attr('value')!=""))
	     	{
	    		sale=true;
	    	}else{
	    		sale=false;
	    	} 
	    }
    	return sale;
  	};
    	
});
$('#portipo').show();
</script>