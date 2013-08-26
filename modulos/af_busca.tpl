<!--{fin}-->
	<div class="col_12">
	<h3>Buscador::Afiliados</h3>
	<p>

	<form action="index.php?ac=af_busca" name='busca' method='post'>

		<div class="col_5">
			<!-- Select -->
		<label for="select1">Tipo</label>
		<select id="select1" class='fancy' name="campo">
		<option value="0">-- Seleccionar --</option>
		<option value="1">Por Nro.Afiliado</option>
		<option value="2">Por Apellido y Nombre</option>
		<option value="3" selected='selected'>Por DNI</option>
		<option value="4">Por Farmacia</option>
		</select>
		</div>

		<div class="col_5">
		<!-- Placeholder Text -->
		<label for="text2">Consulta</label>
		<input id="text2" type="text" name="criterio" placeholder="Criterio de b&uacute;squeda" /></div>

		<input type="hidden" name="enviado" value="true"/>
		<div class="col_2"><button type='submit'>Consultar</button></div>

	</form>	
	
	<hr></hr>
	<h4>Resultado de b&uacute;squeda</h4><h6>{items}</h6>
	<table class="striped tight sortable" cellpadding="0" cellspacing="0">
			<thead>
				<tr class="alt first last">
					<th value="Afiliado" rel="0">Afiliado</th>
					<th value="Nombre" rel="1">Nombre</th>
					<th value="Domicilio" rel="2">Domicilio</th>
					<th value="Localidad" rel="3">Localidad</th>
					<th value="DNI" rel="4">DNI</th>
					<th value="EstadoCivil" rel="5">Estado Civil</th>
					<th value="Accion" rel="5">Accion</th>
				</tr>
			</thead>
			<tbody>
			<!-- BEGIN bloque -->
				<tr>
					<td>{col1}</td>
					<td>{col2}</td>
					<td>{col3}</td>
					<td>{col4}</td>
					<td>{col5}</td>
					<td>{col6}</td>	
					<td>
						<a href='./index.php?ac=af_modifica&afil={col7}' class='tooltip' title='Modificar'><span style="display: inline-block;" class="icon gray" data-icon="7"></span></a>
					    <a href='./index.php?ac=ver_ficha&afil={col7}' class='tooltip' title='Ver ficha'><span style="display: inline-block;" class="icon gray" data-icon="a"></span></a>
					</td>			
				</tr>
			 <!-- END bloque --> 
			</tbody>
	</table>
	
	</p>
	</div> <!--Cierro col_9 inicial-->
	
	<div class="col_6 center">
	{error}
	<h6>Tareas</h6>
	<ul class="alt">
	<li><a href=''>Buscar Afiliado</a></li>
	<li><a href=''>Modificar Afiliado</a></li>
	<li><a href=''>Mostrar rango por edad</a></li>
	<li><a href=''>Otra consulta</a></li>
	</ul>
	</div>
	
	<hr />
	
