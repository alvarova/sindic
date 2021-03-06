<!DOCTYPE html>
<html><head>
<title>Importador de contactos - CSV</title>
<meta charset="UTF-8">
<meta name="description" content="Importador de contactos - CSV" />
<script type="text/javascript" src="../js/jquery.min.js"></script>
<!--[if lt IE 9]><script src="../js/html5.js"></script><![endif]-->
<script type="text/javascript" src="../js/prettify.js"></script>                                   <!-- PRETTIFY -->
<script type="text/javascript" src="../js/kickstart.js"></script> 
<link rel="stylesheet" type="text/css" href="../css/kickstart.css" media="all" /> 
<link rel="stylesheet" type="text/css" href="../style.css" media="all" />  

</head>
<body>
<a id="top-of-page"></a><div id="wrap" class="clearfix">
	<div class="col_9">
	
	
	<form method='POST' enctype='multipart/form-data' action='importacsv.php'>	
		<h4>Importar Direcciones de Email</h4> 
		<p>El importador le <strong>permite ingresar una lista de contactos</strong> en formato CSV (exportada de Outlook express o programa de correo similar)
		para luego vincular estos contactos a los afiliados en funcion a su identificaci&oacute;n dentro del sistema.</p>
		<p>Si alguno de los emails que se encuentra en la lista del CSV <strong>se encontrara en la base de datos se omitir&aacute;</strong> por razones obvias.(ya se encuentra ingresado)</p>
		<p>Los contactos que se agregan por este medio <strong>no se vincular&aacute;n inicialmente a ningun afiliado</strong> y pertenecer&aacute;n al <strong>grupo 'Sin vinculaci&oacute;n'</strong> ya que se carece de esta informaci&oacute;n. Luego desde el men&uacute;
		se le podr&aacute; asignar el afiliado que correspondiera.  
		</p>
		<br/><br/>
		<div class='col_1'></div><p>Asignar listado a un grupo existente 
		<? include_once('consultagrp.php'); ?></p><br/>
		<div class='col_1'></div><label for="file1">Archivos CSV</label> <input id="file1" type="file" name='file'/> 
		<button class=" small" type="submit"><span class="icon small" data-icon=")"></span> Agregar</button>
		<br/><br/>

	</form>
	
	
	<br/><br/>
	<hr class="alt1 col_12" />
	<h5>Otras acciones</h5>
			<button class=" pop small"><span class="icon gray small" data-icon=")"></span> Exportar listado</button>
			<a href='#' onclick='window.close();'><button class=" pop small"><span class="icon gray small" data-icon="Q"></span> Salir  </button></a><br/><br/>	
	</div>
<div class="col_3">
	<h6>Detalle del importador</h6>
	<ul class="checks">
	<li>Permite importar contactos de archivos CSV</li>
	<li>Los campos requeridos seran solo nombre e email</li>
	<li>Luego Ud. podra vincular los afiliados a las direcciones</li>

	</ul>
</div>
</div>
</body>
</html>
