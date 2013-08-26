<!--{fin}-->
<!-- ===================================== 
	FORMS 
===================================== -->


<form action="index.php?ac=afiliados" name='busca' method='post'>

		<div class="col_3">
			<!-- Select -->
		    <label for="select"></label>
			<select id="select" class='fancy' name="campo">
				<option value="0">-- Seleccionar --</option>
				<option value="id_afiliado" {s1}>Por Nro.Afiliado</option>
				<option value="nombre" {s2}>Por Apellido y Nombre</option>
				<option value="nro_documento" {s3}>Por DNI</option>
			</select>
		</div>

		<div class="col_3">
		<!-- Placeholder Text -->
			<label for="text2">Busca</label>
			<input value="{criterio}" id="text2" type="text" name="criterio" placeholder="Criterio de b&uacute;squeda" />
		</div>

		
		<div class="col_1">
			<input type="hidden" name="enviado" value="true"/>
			<button type='submit' class='small'>Consultar</button>
		</div>

		<div class="col_5">
			<!-- BEGIN aviso -->
			<div class="notice warning" style='float: right;'>
			<span style="display: inline-block;" class="icon medium" data-icon="!"></span>{aviso}
				<a style="display: inline-block;" href="#close" class="icon close" data-icon="x"></a>
			</div>
			<!-- END aviso -->
		</div>


	</form>	

<ul class="tabs">
<li><a href="#afiliado">Afiliado</a></li>
<li><a href="#empleador">Empleador</a></li>
<li><a href="#familiares">Familiares</a></li>
</ul>


<form name="afiliado" class="vertical" method='post' action='./index.php?update=mail&ac=afiliados'>
<div id="afiliado" class="tab-content">

	<!-- BEGIN osocial -->
<div class="col_12">
		
	<a href="./index.php?ac=ordenes&id_afiliado={id_afiliado}" target='_blank' class="square {blue} small button"> <span class="icon" data-icon="w"></span>{Ordenes}</a>
	<button class="square small" disabled="disabled"><span class="icon" data-icon="v"></span>Prestaciones</button>
	
</div>
<!-- END osocial -->
<div class="col_12">
	<h4>Datos personales</h4>	
	<div class="col_4 ">
		<label for="text1">Apellido y nombre</label>	
		<input id="text1" type="text"  class="tooltip-bottom" title="Ej. Fernandez Augusto" value="{col2}"/>
		<input type="hidden" name="id_afiliado" value="{id_afiliado}"/>
	</div>
	<div class="col_2 ">
		<label for="text1">DNI</label>	
		<input name="nro_documento" id="text1" type="text"  class="tooltip-bottom right" title="Sin puntos ni tipo - Ej. 23456789" value="{col5}"/>
	</div>
	<div class="col_2 ">
		<label for="text1">Nacionalidad</label>	
		<input id="text1"  type="text" value="{col8}"/>
	</div>
	<div class="col_2 ">
		<label for="text1">CUIL</label>	
		<input id="text1" type="text"  class="tooltip-bottom " title="Ej. 27-23456789-1"  value="{col10}"/>
	</div>
	<div class="col_4">
		<label for="text1">Domicilio</label>	
		<input id="text1" type="text"  class="tooltip-bottom " title="Ej. Urquiza 1920"  value="{col3}"/>
	</div>
	<div class="col_2">
		<label for="text1">Localidad</label>	
		<input id="text1" type="text"  value="{col4}"/>
	</div>
	<div class="col_2">
		<label for="text1">Cod Postal</label>	
		<input id="text1" type="text"  value="{col11}" class="right"/>
	</div>
	<div class="col_3">
		<label for="text1">Tel&eacute;fono</label>	
		<input id="text1" type="text"  class="tooltip-bottom right" title="Solo n&uacute;meros y guion Ej. 0342-4567890" value="{col12}"/>
	</div>
	<div class="col_12">
	  <div class="col_4">
		  <label for="text1">Email</label>	
		  <input name="email" id="text1" type="text"  value="{col7}" class="emailfocus"/>
		  <span class='actualiza'><a href="javascript: submitform()">Actualizar</a></span>
	  </div>
	  <div class="col_6">
	  		<label for="botonera">Estado de afiliaci&oacute;n</label>
	  		<div id='botonera'>
	  		<button class="small {sindicatoc}" {sindicatod}>Sindicato</button>
	  		<button class="small {osocialc}" {osociald}>Obra Social</button>
	  		<button class="small {mutualc}" {mutuald}>Mutual</button>
	  		</div>
	  </div>
	</div>
	
	<div class="col_2">
		<label for="text1">Sexo</label>	
		<input name="sexo" id="text1" type="text" value="{sexo}" />
	</div>
	<div class="col_2">
		<label for="text1">Estado Civil</label>	
		<input id="text1" type="text" value="{col9}"/>
	</div>
	<div class="col_2">
		<label for="text1">Fecha Nac.</label>	
		<input name="fecha_nacimiento" id="text1" type="text"  class="tooltip-bottom right" title="N&uacute;meros y gui&oacute;n A&ntilde;o-mes-dia. Ej. 1978-10-23" value="{fecha_nacimiento}"/>
	</div>
	<div class="col_2">
		<label for="text1">Fecha de Baja</label>	
		<input name="fecha_baja" id="text1" type="text"  class="tooltip-bottom right" title="N&uacute;meros y gui&oacute;n A&ntilde;o-mes-dia. Ej. 2006-10-31" value="{fecha_baja}"/>
	</div>
	<div class="col_4">
		<label for="text1">Motivo</label>	
		<input name="motivo_baja" id="text1" type="text" value="{motivo_baja}"/>
	</div>
</div>

<div class="col_12">
	<h4>Servicios prestados</h4>
	<div class="col_2">
		<label for="text1">Sueldo B&aacute;sico</label>	
		<input name="sueldo" id="text1" type="text" value="{sueldo}" class="right"/>
	</div>
	<div class="col_2">
		<label for="text1">Adicional</label>	
		<input name="adicional1" id="text1" type="text" value="{adicional1}" class="right"/>	
	</div>
	<div class="col_2">
		<label for="text1">Adicional %</label>	
		<input name="adicional2" id="text1" type="text" value="{adicional2}" class="right"/>
	</div>
	<div class="col_2">
		<label for="text1">Alta Sindicato</label>	
		<input name="sindicato_ingreso" id="text1" type="text" class="tooltip-bottom right" title="Fecha de ingreso al sindicato - Vacio=Sin afiliaci&oacute;n - Ej. 2001-10-23" value="{sindicato_ingreso}"/>
	</div>
	<div class="col_2">
		<label for="text1">Cuota Sindic.%</label>	
		<input id="text1" type="text" value="{sindicato_cuota}" class="right"/>
	</div>
	<div class="col_2">
		<label for="text1">Alta Ob.Social</label>
		<input name="os_ingreso" id="text1" type="text"  class="tooltip-bottom right" title="Fecha de ingreso a la Obra social - Vacio=Sin afiliaci&oacute;n - Ej. 2001-10-23" value="{os_ingreso}"/>
	</div>
	<div class="col_2">
		<label for="text1">Alta Mutual</label>	
		<input name="mutual_ingreso" id="text1" type="text" class="tooltip-bottom right" title="Fecha de ingreso a la mutual - Vacio=Sin afiliaci&oacute;n - Ej. 2001-10-23" value="{mutual_ingreso}"/>
	</div>
	<div class="col_2">
		<label for="text1">Cuota Mutual $</label>	
		<input id="text1" type="text" name="" value="{mutual_cuota}" class="right"/>
    </div>
</div>

<div class="col_12">
	<h4>Situaci&oacute;n Laboral</h4>
	<div class="col_2">
		<label for="text1" class="tooltip-bottom" title="Fecha de ingreso laboral - Vacio=Sin afiliaci&oacute;n - Ej. 2001-10-23">Fecha Ingreso</label>	
		<input name="fecha_ingreso" id="text1" type="text" value="{fecha_ingreso}" class="right"/>
	</div>
	<div class="col_2">
		<label for="text1" class="tooltip-bottom disabled" title="Se calcula seg&uacute;n fecha de ingreso ">Antiguedad</label>
		<input name="antiguedad" id="text1" type="text" class="tooltip-bottom right" disabled="disabled" title="Se calcula seg&uacute;n fecha de ingreso " value="{antiguedad}"/>
	</div>
	<div class="col_2">
		<label for="select1">Tipo de empleo</label>
		<select id="select1" class='fancy'>
		<option value="4">-- Elija --</option>
		<option value="1" {te1}>Efectivo</option>
		<option value="2" {te2}>Contratado</option>
		<option value="0" {te}>Sin especificar</option>
		</select>
	</div>
	<div class="col_2">
		<label for="select2">Tipo de jornada</label>
		<select id="select2" class='fancy'>
		<option value="0">-- Elija --</option>
		<option value="1" {tj1}>Completa</option>
		<option value="2" {tj2}>Media</option>
		<option value="2" {tj}>Sin especificar</option>
		</select>
	</div>
	<div class="col_2">
		<label for="text1">Desde el (fecha)</label>	
		<input name="contrato_desde" value="{contrato_hasta}" id="text1" type="text" value="" class="right"/>
	</div>
	<div class="col_2">
		<label for="text1">Hasta el (fecha)</label>	
		<input name="contrato_hasta" value="{contrato_hasta}" id="text1" type="text" class="right"/>
	</div>
	<div class="col_2">
		<label for="text1">Categor&iacute;a</label>
			<select id="select1" >
			<!-- BEGIN categorias -->
			<option value="{id_categoria}" {selected}>{descripcion}</option>
			<!-- END categorias -->
			</select>
			
		
	</div>
</div>

</div> <!-- Fin contenedor pestaña afiliado -->
</form>

<form name="empleador" class="vertical">
<div id="empleador" class="tab-content">
 <div class="col_12">
 <h4>Datos del empleador</h4>
	<div class="col_2">
		<label for="text1">Codigo</label>	
		<input id="text1" type="text" />
	</div>
	<div class="col_2">
		<label for="text1">CUIT</label>	
		<input name='fcuit' value="{fcuit}" id="text1" type="text" disabled="disabled"/>
	</div>
	<div class="col_4">
		<label for="text1">Farmacia</label>	
		<input  name='frazon_social' value="{frazon_social}" id="text1" type="text" disabled="disabled"/>
	</div>
	<div class="col_4">
		<label for="text1">Titular</label>	
		<input  name='fnombre_titular' value="{fnombre_titular}" id="text1" type="text" disabled="disabled"/>
	</div>
	<div class="col_4">
		<label for="text1">Domicilio</label>	
		<input  name='fdomicilio' value="{fdomicilio}" id="text1" type="text" disabled="disabled"/>
	</div>
	<div class="col_2">
		<label for="text1">Localidad</label>	
		<input  name='flocalidad' value="{flocalidad}" id="text1" type="text" disabled="disabled"/>
	</div>
	<div class="col_2">
		<label for="text1">Cod Postal</label>	
		<input  name='fcod_postal' value="{fcod_postal}" id="text1" type="text" disabled="disabled"/>
	</div>
	<div class="col_2">
		<label for="text1">Telefono</label>	
		<input  name='ftelefono' value="{ftelefono}" id="text1" type="text" disabled="disabled"/>
	</div>
	<div class="col_2">
		<label for="text1">Fax</label>	
		<input  name='ffax' value="{ffax}" id="text1" type="text" disabled="disabled"/>
	</div>
	<div class="col_4">
		<label for="text1">Email</label>	
		<input  name='femail' value="{femail}" id="text1" type="text" />
	</div>
</div>
<div class="col_12">
<h4 class='left clearfix'>Datos del Contador </h4><h6>(farmacia {frazon_social})</h6>
	<div class="col_4 clearfix">
		<label for="text1">Nombre</label>	
		<input  name='cpnnombre' value="{cpnnombre}" id="text1" type="text" disabled="disabled"/>
	</div>
	<div class="col_4">
		<label for="text1">Domicilio</label>	
		<input  name='cpndomicilio' value="{cpndomicilio}" id="text1" type="text" disabled="disabled"/>
	</div>
	<div class="col_2">
		<label for="text1">Telefono</label>	
		<input  name='cpntelefono' value="{cpntelefono}" id="text1" type="text" disabled="disabled"/>
	</div>
	<div class="col_2">
		<label for="text1">Fax</label>	
		<input  name='cpnfax' value="{cpnfax}" id="text1" type="text" disabled="disabled"/>
	</div>
	<div class="col_4">
		<label for="text1">Email</label>	
		<input  name='cpnemail' value="{cpnemail}" id="text1" type="text" />
	</div>

 </div>
</div>
</form>




<div id="familiares" class="tab-content">
 <h4>Familiares a Cargo - Obra Social </h4>

	<div class="col_12">

     <!-- BEGIN familiar -->
		<div class="familiar">
			 
			<form class='vertical'>
			<p><a href="#editaros.php?idfliar={idfamiliar}" id='edita'>Editar</a></p>
			<div class="col_4">
				<label for="text1">Nombre y Apellido</label>	
				<input value="{fnombre}" id="text1" type="text" disabled="disabled"/>
			</div>
			<div class="col_2">
				<label for="text1">Fecha nac.</label>	
				<input value="{ffecha_nacimiento}" id="text1" type="text" disabled="disabled"/>
			</div>
			<div class="col_2">
				<label for="text1">Edad</label>	
				<input value="{fedad}" id="text1" type="text" disabled="disabled"/>
			</div>
			<div class="col_4">
				<label for="text1">parentesco</label>	
				<input value="{fparentesco}" id="text1" type="text" disabled="disabled"/>
			</div>
			<div class="col_2">
				<label for="text1">DNI</label>	
				<input value="{fdni}" id="text1" type="text" disabled="disabled"/>
			</div>
			<div class="col_2">
				<label for="text1">sexo</label>	
				<input value="{fsexo}" id="text1" type="text" disabled="disabled"/>
			</div>
			<div class="col_2">
				<label for="text1">Alta beneficiario</label>	
				<input value="{falta}" id="text1" type="text" disabled="disabled"/>
			</div>
			<div class="col_2">
				<label for="text1">Estado</label>	
				<input value="{festado}" id="text1" type="text" disabled="disabled"/>
			</div>
			<div class="col_2">
				<label for="text1">Alta Obra Social</label>	
				<input value="{falta_osocial}" id="text1" type="text" disabled="disabled"/>
			</div>
			<div class="col_2">
				<label for="text1">Alta Sindicato</label>	
				<input value="{falta_sindicato}" id="text1" type="text" disabled="disabled"/>
			</div>
			</form>
			<hr class="alt2" />
		</div>
		<!-- END familiar -->
	</div>
 
 
 <form name="familiares" class="vertical"> 
 <h4>Beneficiario de subsidio por fallecimiento</h4>
		<div class="col_12"> 
					<div class="col_4">
						<label for="text1">Nombre y Apellido</label>	
						<input name="dsnombre" value="{dsnombre}" id="text1" type="text" disabled="disabled"/>
					</div>
					<div class="col_2">
						<label for="text1">DNI</label>	
						<input name="dsnro_documento" value="{dsnro_documento}" id="text1" type="text" disabled="disabled"/>
					</div>
					<div class="col_4">
						<label for="text1">Domicilio</label>	
						<input name="dsdomicilio" value="{dsdomicilio}" id="text1" type="text" disabled="disabled"/>
					</div>
					<div class="col_2">
						<label for="text1">Nacionalidad</label>	
						<input name="dsnacion" value="{dsnacion}" id="text1" type="text" disabled="disabled"/>
					</div>
					<div class="col_2">
						<label for="text1">Localidad</label>	
						<input name="dslocalidad" value="{dslocalidad}" id="text1" type="text" disabled="disabled"/>
					</div>
					<div class="col_2">
						<label for="text1">Cod.Postal</label>	
						<input name="dscod_postal" value="{dscod_postal}" id="text1" type="text" disabled="disabled"/>
					</div>
					<div class="col_2">
						<label for="text1">Fecha Nac.</label>	
						<input name="dsfecha_nacimiento" value="{dsfecha_nacimiento}" id="text1" type="text" disabled="disabled"/>
					</div>
					<div class="col_2">
						<label for="text1">Telefono</label>	
						<input name="dstelefono" value="{dstelefono}" id="text1" type="text" disabled="disabled"/>
					</div>
					<div class="col_2">
						<label for="text1">Parentezco</label>	
						<input id="text1" type="text" disabled="disabled"/>
					</div>
					<div class="col_2">
						<label for="text1">Firma Form.</label>	
						<input name="firma_formulario" value="{firma_formulario}" id="text1" type="text" disabled="disabled"/>
					</div>
		</div>
 </form>
</div>


