<!--{fin}-->
<!-- ===================================== 
	FORMS 
===================================== -->

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




<ul class="tabs">
<li><a href="#afiliado">Afiliado</a></li>
<li><a href="#empleador">Empleador</a></li>
<li><a href="#familiares">Familiares</a></li>
</ul>
<form class="vertical">
<div id="afiliado" class="tab-content">
<div class="col_12">
<h4>Datos personales</h4>	
<div class="col_4"><label for="text1">Apellido y nombre</label>	<input id="text1" type="text"  class="tooltip-bottom" title="Ej. Fernandez Augusto"/></div>
<div class="col_2"><label for="text1">DNI</label>	<input id="text1" type="text"  class="tooltip-bottom" title="Sin puntos ni tipo - Ej. 23456789"/></div>
<div class="col_2"><label for="text1">Nacionalidad</label>	<input id="text1" type="text" /></div>
<div class="col_2"><label for="text1">CUIL</label>	<input id="text1" type="text"  class="tooltip-bottom" title="Ej. 27-23456789-1"/></div>
<div class="col_4"><label for="text1">Domicilio</label>	<input id="text1" type="text"  class="tooltip-bottom" title="Ej. Urquiza 1920"/></div>
<div class="col_2"><label for="text1">Localidad</label>	<input id="text1" type="text" /></div>
<div class="col_2"><label for="text1">Cod Postal</label>	<input id="text1" type="text" /></div>
<div class="col_2"><label for="text1">Tel&eacute;fono</label>	<input id="text1" type="text"  class="tooltip-bottom" title="Solo n&uacute;meros y guion Ej. 0342-4567890"/></div>
<div class="col_4"><label for="text1">Email</label>	<input id="text1" type="text" /></div>

<label for="text1">Sexo</label>	<input id="text1" type="text" />
<label for="text1">Estado Civil</label>	<input id="text1" type="text" />
<label for="text1">Fecha Nacimiento</label>	<input id="text1" type="text"  class="tooltip-bottom" title="N&uacute;meros y gui&oacute;n A&ntilde;o-mes-dia. Ej. 1978-10-23"/>
<label for="text1">Fecha de Baja</label>	<input id="text1" type="text"  class="tooltip-bottom" title="N&uacute;meros y gui&oacute;n A&ntilde;o-mes-dia. Ej. 2006-10-31"/>
<label for="text1">Motivo</label>	<input id="text1" type="text" />
</div>

<div class="col_12">
<h4>Servicios prestados</h4>
<label for="text1">Sueldo B&aacute;sico</label>	<input id="text1" type="text" />
<label for="text1">Adicional</label>	<input id="text1" type="text" />
<label for="text1">Adicional %</label>	<input id="text1" type="text" />
<label for="text1">Fecha Ing.Sindicato<span class="right">Vacio=Sin afiliaci&oacute;n</span></label>	<input id="text1" type="text" class="tooltip-bottom" title="Ej. 2001-10-23" />
<label for="text1">Cuota Sindicato %</label>	<input id="text1" type="text" />
<label for="text1">Fecha Ing.OSocial <span class="right">Vacio=Sin afiliaci&oacute;n</span></label><input id="text1" type="text"  class="tooltip-bottom" title="Ej. 2001-10-23"/>
<label for="text1">Fecha Ing.Mutual<span class="right">Vacio=Sin afiliaci&oacute;n</span></label>	<input id="text1" type="text" class="tooltip-bottom" title="Ej. 2001-10-23"/>
<label for="text1">Cuota Mutual</label>	<input id="text1" type="text" />
</div>

<div class="col_12">
<h4>Situaci&oacute;n Laboral</h4>
<label for="text1">Fecha Ingreso<span class="right">Ej.[2008-10-31]</span></label>	<input id="text1" type="text" />
<label for="text1" class="disabled">Antiguedad - calcula seg&uacute;n ingreso </label><input id="text1" type="text" disabled="disabled" />
<label for="select1">Tipo de empleo</label>
<select id="select1" class='fancy'>
<option value="0">-- Elija --</option>
<option value="1">Efectivo</option>
<option value="2">Contratado</option>
</select>

<label for="select2">Tipo de jornada</label>
<select id="select2" class='fancy'>
<option value="0">-- Elija --</option>
<option value="1">Completa</option>
<option value="2">Media</option>
</select>

<label for="text1">Desde el (fecha)</label>	<input id="text1" type="text" />
<label for="text1">Hasta el (fecha)</label>	<input id="text1" type="text" />
<label for="text1">Categor&iacute;a</label>	<input id="text1" type="text" />
</div>

</div> <!-- Fin contenedor pestaña afiliado -->
</form>

<div id="empleador" class="tab-content">
<h4>Datos del empleador</h4>
<div class="col_6">
<label for="text1">Codigo</label>	<input id="text1" type="text" />
<label for="text1">CUIT</label>	<input id="text1" type="text" />
<label for="text1">Farmacia</label>	<input id="text1" type="text" />
<label for="text1">Titular</label>	<input id="text1" type="text" />
<label for="text1">Domicilio</label>	<input id="text1" type="text" />
<label for="text1">Telefono</label>	<input id="text1" type="text" />
<label for="text1">Email</label>	<input id="text1" type="text" />
<label for="text1">Fax</label>	<input id="text1" type="text" />
<label for="text1">Localidad</label>	<input id="text1" type="text" />
<label for="text1">Cod Postal</label>	<input id="text1" type="text" />
</div>
</div>
	
<div id="familiares" class="tab-content">
Familiares a Cargo
 Beneficiarios OSocial
Nombre
Fecha Nacimiento
Edad
Parentesco
DNI
Sexo
Alta
Estado
AltaOSocial
AltaSindicato

Beneficiario Subsidio por fallecimiento
Apellido y nombre
DNI
Domicilio
Nacionalidad
Localidad
CodPostal
Fecha Nacimiento
Telefono
Parentezco
FirmoFormulario
</div>

