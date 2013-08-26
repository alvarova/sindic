<!--{fin}-->
	<div class="col_9">
	<form action="enviar.php" method="post">	
		<h3>Email</h3> 
		
			<div class="col_12">

					<label class="col_3  tooltip" for="select1"  title="Se envia como copia oculta a todos los integrantes del listado">Enviar a grupos seg&uacute;n listados</label>
					<select id="select1" class='col_5 fancy tooltip' title="Se envia como copia oculta a todos los integrantes del listado">
						<option value="0" >-- Ninguno --</option>
						<!-- BEGIN grps -->
						<option value="{grupoval}">{grupodb}</option>
						<!-- END grps -->
					</select><br/>
			</div>			
			<div class="col_12">
                <div class="col_11"><label for="subject">Asunto del Mensaje</label> <input type='text' id="subject" class="col_8" /></div>
    			<label for="rte1">Cuerpo del Mensaje</label>
    			<textarea id="rte1" class="rte" ></textarea>
			</div>

				<header  class="col_9">
			       <h5 id="vermas">  EMails individuales <span class="icon small gray"  data-icon="p"></span></h5>
			       <span id="mensaje"></span>
    			</header>
		       
		        <section  id="individuales" class="col_9">
		            <ul id="lista" class="alt" contenteditable="true">
		                <li></li>
		            </ul>
		        <a href='./modulos/destinatarios.php' id="destinatarios" target='_blank'><button class="blue pop small ">Agregar</button></a>
				
				<button class="red small pop" id="limpiar" value="Limpiar"> <span class="icon small" data-icon="F"></span> Limpiar Lista </button> 
		        </section>
 			
			<div class="col_12">
				<a href='./sendmail/upload.php' id="adjunta" target='_blank'><button class="black small"><span class="icon blue small" data-icon="R"></span> Adjuntar Archivo </button></a> <span id='adjunto'></span>
                <button class="gray small pop " id="limpiaradjunto" value="Eliminar"> <span class="icon small red" data-icon="m"></span> Limpiar Adjunto </button> 
                
			</div>			
			<div class="col_6">
				<a href="./sendmail/send.php" id="send"><button class="red small"> <span class="icon small" data-icon="%"></span> Enviar Mailing </button></a>
			</div>
		
	</form>
	<br/><br/>
	<hr class="alt1 col_12" />
	<h5>Otras acciones</h5>
			<a href='./mailing/importador.php' target='_blank'><button class=" pop small"><span class="icon pink small" data-icon="("></span> Importar Correo CSV</button></a>
			<!-- <a href='./mailing/exportador.html' target='_blank'><button class=" pop small"><span class="icon green small" data-icon=")"></span> Exportar listado</button></a> -->
			<a href='./mailing/admingroup.php' target='_blank'><button class=" pop small"><span class="icon blue small" data-icon="U"></span> Administrar Grupos</button></a>
			<a href='./mailing/adminemail.php' target='_blank'><button class=" pop small"><span class="icon darkgray small" data-icon="@"></span> Administrar Emails</button></a>
			<br/><br/>	
	</div>
	<div class="col_3">
	<h6>Ultimos Cambios</h6>
	<ul class="checks">
	<li>Se habilitaron los cambios en correo</li>
	<li>Libreta de direcciones actualizada</li>

	</ul>
	
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
	
	<hr />
	<div class="col_5">
		<!-- -->
	</div>
	
		
	</div>
<script type="text/javascript">
var gruposelected="";
$(document).ready(function() {

        $("#limpiar").click(function(e){
        		e.preventDefault();
            	localStorage.removeItem('listado');
                //localStorage.removeItem('asunto');
            	localStorage.removeItem('individuallistemails');
                $("#mensaje").text("Sin direcciones en  el LocalStorage");
                $("#individuales").toggle();
                $('#lista > li').remove();  //limpiamos todos los elementwos
                $('#lista').append('<li></li>');  //restauramos el li limpio (para poder agregar)
                $('input#asunto').val('');

         });


        $("#vermas").click(function(){
        	$("#individuales").toggle();
        });
        
        /* Habilita los cambios en el editor */
		$('#rte1, .rte-editor').blur(function() {
			
		   var txt = $('.rte-editor').html();
		   if (txt=="") {
		   		txt = $('textarea#rte1').val();
		   }
		  localStorage.removeItem('mensajeemail');
		  localStorage.setItem("mensajeemail", txt); 
		});



		function update_all(){ 
                 return 0;
		}

       /* $("#select1").blur(function(){
            gruposelected=$(this).attr('selected');
            alert(gruposelected);
        });

        $("#select1").change(function() {
            gruposelected = $(this).val();//$(this).val();
            alert(gruposelected);
        });*/


        $('#select1').change(function() {
            var selectVal = $('#select1 option:selected').val();
            alert(selectVal);
        });




         $("#lista").blur(function(){
         		 //alert($("#lista").html());
         		 var elementos = $("#lista").html();

         		 elementos = elementos.replace('<li class="first last"><br></li>',"");


         		 var items=elementos.split('<li class="first last">');
         		 var ErrorFlag=false;
         		 var emails=""; //Aqui se acumularan separados con coma los emails individuales para el envio masivo
         		 var c=0;
         		 localStorage.removeItem("individuallistemails"); //Almacenamiento de la lista de emails individuales persistente en el navegador
         		 for (x=1; x<items.length; x++)
         		 {
         		 	 
         		 	console.log("A:"+items[x]);
         		 	var pos=items[x].indexOf("</li>");
         		 	if (pos) {
         		 		 var sale=items[x].slice(0,pos);
         		 		 console.log("P:"+sale);
         		 		 if ((sale.length==0) ) { delete items[x]; }
         		 		 else {
         		 		 	if ((sale.indexOf("@")<0) || (sale.indexOf(".")<0)) { 
         		 		 			ErrorFlag=true; //Direccion de email sin arroba ni puntos? Ok! Validado.
         		 		 	}
         		 		 	items[x]=sale;
         		 		 	emails=emails + sale+', ';
         		 		 }
         		 	} 
         		 	
         		 	
         		 	
         		 }

         		 localStorage.setItem("individuallistemails", emails.slice(0,(emails.length-2))); 
         		 console.log("Final:"+localStorage.getItem('individuallistemails'));
         		 if (ErrorFlag) alert("Atencion: Entre los items posee una direccion de email erronea. Verificar:"+items);
         		 //Tengo un arreglo con los elementos impares trash y los pares con el email otro split alli.

                 localStorage.setItem('listado',elementos);
                 listaf=$("#lista").html();
         		 listaf=listaf.replace("null","");
                 localStorage.setItem('listado', listaf);
                 if ($("#lista:contains('null')").length) {
                 	$("#lista:contains('null')").removeItem();			
                 }
          });


		/* Abre ventana de destinatarios individuales */
        $("#destinatarios").click(function(e){
        	e.preventDefault();
			var href=$(this).attr('href');
			window.open(href,'','menubar=1,location=1,toolbar=1,width=1024,height=768'); 
        });    

        /* Abre ventana de destinatarios individuales */
        $("#adjunta").click(function(e){
            e.preventDefault();
            var href=$(this).attr('href');
            window.open(href,'','menubar=1,location=1,toolbar=1,width=960,height=500'); 
        });  

        $("#limpiaradjunto").click(function(e){
            e.preventDefault();
                $('#limpiaradjunto').css('display','none');
                $('#adjunto').empty().append('--Sin Adjuntos--');
                localStorage.setItem("adjunto","");
        }); 


        /* Procede a enviar el mailing */
        $("#send").click(function(e){
        	e.preventDefault();
            //var asuntotxt='';
            //asuntotxt=$('#asunto').val();
            //localStorage.setItem("asunto", asuntotxt);
        	if (localStorage.getItem("mensajeemail")==""){
        			alert("Error: No definio el mensaje a enviar a los destinatarios.");
        	}else{
	        	var vgrp=$('#select1 option:selected').val();
	        	console.log(vgrp);
	        	console.log(localStorage.getItem("individuallistemails"));
	        	if ((vgrp=="0") && (localStorage.getItem("individuallistemails")=="")) 
		        	{
		        	alert("Error: No tiene asignado destinatarios de grupo ni individuales.");
		        	}else{
					var href=$(this).attr('href');
					//alert(href);
                    grupon=$('#select1:selected').attr('value');
                    alert(grupon);
					window.open(href+'?grupo='+grupon,'','menubar=1,location=1,toolbar=1,width=1024,height=768'); 
					}
			}
        });
        
        $('#subject').blur(function(){
            var asuntillo= $('#subject').val();
            //alert(asuntillo);
            localStorage.setItem("asunto", asuntillo);

        });

        //Lo que se inicia sin eventos...

		//Restauramos el mensaje viejo por si se cerro la ventana por error
		tmpMsg=localStorage.getItem("mensajeemail");
		if (tmpMsg!=""){
             $('.rte-editor').append(tmpMsg);
		}

		if (localStorage.getItem('listado')){
                $("#mensaje").text("Lista de direcciones individuales");
                $("#lista").html(localStorage.getItem('listado'));
            }else{
                $("#mensaje").text("Sin direcciones en  el LocalStorage");
                $("#individuales").toggle();
        }
        if (localStorage.getItem('asunto')) { 
            asunto=localStorage.getItem('asunto');
            $('#subject').val(asunto); 
        }
        if (localStorage.getItem('adjunto')){
                $('#adjunto').empty().append(localStorage.getItem('adjunto'));
        } else {
                $('#limpiaradjunto').css('display','none');
                $('#adjunto').empty().append('--Sin Adjuntos--');
        }
        //m=update_all();


    });  

</script>