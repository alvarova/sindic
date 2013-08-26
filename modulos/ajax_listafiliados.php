<?PHP

$pre="../";  //Nivel del directorio para agregar DB y Pear
include_once($pre."localconf.php");
include_once($pre."library/logger.php");
require_once($pre."library/PEAR.php");
require_once($pre."library/IT.php");

/*  Modulo de listado de afiliados sindicales activos.
**  Empleado para impresiones.php
**
**/

	
	$consulta = "SELECT `afiliados`.id_afiliado, `afiliados`.nombre, `afiliados`.id_farmacia, `afiliados`.sindicato_ingreso, `afiliados`.localidad, `farmacias`.razon_social 
					FROM  farmacias    LEFT JOIN afiliados	ON  `afiliados`.id_farmacia =`farmacias`.id_farmacia
 					WHERE  (`afiliados`.sindicato = 1)  AND (`afiliados`.fecha_baja =  '0000-00-00')  ";

	


	echo '<table class="sortable" >
			<thead><tr>
				<th>ID</th>
				<th>Afiliado</th>
				<th>Farmacia</th>
				<th>Localidad</th>
				<th>Opciones</th>
			</tr></thead>
			<tbody><tr>';
	$db = ADONewConnection();
	$result = $db->Connect("$dbhost", "$dbuname", "", "$dbname");
	$rs=$db->Execute($consulta);	
	$cantidadafiliados=0;
	while (!$rs->EOF) {
					echo "<tr><td>".$rs->fields['0']."</td>";
					echo "<td>".$rs->fields['1']."</td>";
					echo "<td>".$rs->fields['5']."</td>";
					echo "<td>".$rs->fields['4']."</td>";
					echo "<td id='opciones'><a href=''><span class='icon gray' data-icon='7'>TEST</span></a></td></tr>";
					$cantidadafiliados++;
					$rs->MoveNext();
				}
	echo '</tbody></table>';
	echo "Total de afiliados:".$cantidadafiliados;
?>	
	