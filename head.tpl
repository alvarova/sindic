<!DOCTYPE html>
<html><head>
<title>Sindicato de Farmacia Santa Fe</title>
<meta charset="ISO-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<meta name="description" content="Sistema de gestion sindical" />
<meta http-equiv="Expires" content="0"> 
<meta http-equiv="Last-Modified" content="0"> 
<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate"> 
<meta http-equiv="Pragma" content="no-cache">
<script type="text/javascript" src="js/jquery.min.js"></script>
<!--[if lt IE 9]><script src="js/html5.js"></script><![endif]-->
<script type="text/javascript" src="js/prettify.js"></script>                                   <!-- PRETTIFY -->
<script type="text/javascript" src="js/kickstart.js"></script>                                  <!-- KICKSTART -->
<link rel="stylesheet" type="text/css" href="css/kickstart.css" media="all" />                  <!-- KICKSTART -->
<link rel="stylesheet" type="text/css" href="style.css" media="all" />                          <!-- CUSTOM STYLES -->
{setOnHeader}
<script type='text/javascript'>
$(document).ready(function(){


$(".vertical").hover(
  function () {
    $(this).find("a.actualiza").stop().animate({"opacity": "1"}, "slow"); 
  }, 
  function () {
    $(this).find("a.actualiza").stop().animate({"opacity": "0"}, "slow");
  }
);

$("#emailfocus").keypress(function(event) {

  alert("Handler for .keypress() called.");
});


$(".toggleon").click(function() {
            $("#slideDiv").slideToggle(300);                
});

$(".toggleoff").click(function() {
            $("#slideDiv").slideToggle(300);                
});

{errorJs} 
});

function submitform()
{
  document.afiliado.submit();
}

</script>

</head><body>


<a id="top-of-page">

	<div id="slideDiv">
			<div class="login_side">

				<form action='./library/{sesionlink}.php' method='post'>
					<label>Usuario</label>
						<input type="text" name="usuario" {bloqueanombre}{ocultapassword}/> {muestrausuario}
					<label {ocultapassword}>Contrase&ntilde;a</label>
						<input type="Password" name="password" {ocultapassword}  autocomplete="off" />
						<input type="submit" value="{acceder}" name="submit" class="submit" />
						<span class=''> {error}</span>
				</form>
			</div>				
			<a href="#" class="toggleoff slidebutton"><button class="inset small "><span class="icon" data-icon="-"></span>Ocultar</button></a>

	</div>
	<a href="#" class="toggleon slidebutton"><button class="inset small blue"><span class="icon" data-icon="O"></span>Sesion</button></a>


</a><div id="wrap" class="clearfix">





<!-- ===================================== END HEADER ===================================== -->
<!--{fin}-->

	<!-- 
	
		ADD YOU HTML ELEMENTS HERE
		
		Example: 2 Columns
	 -->
	<div class='logo'><h4>Sistema Gestfar <span class="icon large darkgray" data-icon="S"></span> </h4></div>
	 <!-- Menu Horizontal -->
	<ul class="menu">
	<li {m1}><a href="./index.php">Inicio</a></li>
	<li {m2}><a href="./index.php?ac=afiliados">Afiliados</a>
	
		<ul>
			<!-- <li><a href="./index.php?ac=af_busca&enviado=true"><span class="icon" data-icon="v"></span>Listar</a></li>
			<li><a href="./index.php?ac=af_busca"><span class="icon" data-icon="a"></span>B&uacute;squeda</a></li> 
			<li><a href="./modulos/pdfgen.php" target='_blank'><span class="icon" data-icon="B"></span>Volcado PDF</a></li>
		-->
			<li><a href="./index.php?ac=af_listados"><span class="icon" data-icon="v"></span>Listas armadas</a></li>
			<li><a href=""><span class="icon" data-icon="P"></span>Impresiones</a>
				<ul>
						<li><a href="./index.php?ac=af_impresiones"><span class="icon" data-icon="v"></span>Remitos</a></li>

				</ul>
			</li>
			
		</ul>
	
	</li>
	<li {m3}><a href="">Farmacias</a>
	<ul>
			<li><a href="./index.php?ac=fa_aportes"><span class="icon" data-icon="Y"></span>Aportes</a></li>

	</ul>
	</li>
	<li {m6}><a href="">Administra</a>
	<ul>
			<li><a href="./index.php?ac=registrocaja"><span class="icon" data-icon="("></span>Mov. de Caja</a></li>

	</ul>
	</li>
	<li {m4}><a href=""><span class="icon" data-icon="R"></span>Acciones</a>
		<ul>
		<li><a href="./index.php?ac=sincro" target='_blank'><span class="icon" data-icon="_"></span>Sincronizar</a></li>
		<li><a href="./index.php?ac=email"><span class="icon" data-icon="G"></span>Email</a></li>
		<li><a href=""><span class="icon" data-icon="A"></span>Par&aacute;metros</a>
			<ul>
			<li><a href=""><span class="icon" data-icon="Z"></span>Registros</a></li>
			<li><a href=""><span class="icon" data-icon="B"></span>Variables</a></li>
			<li><a href=""><span class="icon" data-icon="k"></span>Purgado</a></li>
			</ul>
		</li>
		<li class="divider"><a href=""><span class="icon" data-icon="T"></span>Revalidar</a>
		
		
		<ul>
			<li><a href="./index.php?ac=reval_incompletos"><span class="icon" data-icon="M"></span>Incompletos</a></li>
			<li><a href=""><span class="icon" data-icon="L"></span>Afil.Blancos</a></li>
			<li><a href=""><span class="icon" data-icon="J"></span>DNI Blancos</a></li>
		</ul>
		
		
		</li>
		</ul>
	</li>
	<li {m5}><a href="">Soporte</a></li>
	</ul>
	 
<div class="col_12">
	{contenido}
</div>

<!-- ===================================== START FOOTER ===================================== -->
<div class="clear"></div>
<div id="footer">
&copy; Copyright 2006-2012 All Rights Reserved. Sistema desarrollado por <a href="http://www.vndesign.com.ar">VNDesign.com.ar</a>
<a id="link-top" href="#top-of-page">Subir</a>
</div>

</div><!-- END WRAP -->
</body></html>
