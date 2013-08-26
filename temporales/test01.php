<?
//Prueba conexion a DB en Foxpro
//$dsn = "Driver={Microsoft Visual FoxPro Driver};SourceType=DBF;SourceDB=c:\\shortcut;Exclusive=NO;collate=Machine;NULL=NO;DELETED=NO;BACKGROUNDFETCH=NO;";
//$conn=odbc_connect($dsn,"","");

echo "Conectando:";

$c=0;

$dsn = "Driver={Microsoft Visual FoxPro Driver};SourceType=DBF;SourceDB=C:\\xampp\\htdocs\\sindicato\\Data\\afiliados.DBF;Exclusive=NO;collate=Machine;NULL=NO;DELETED=NO;BACKGROUNDFETCH=NO;";
$cnx=odbc_connect($dsn,"","");
//$cnx2 = odbc_connect("..\\Data\\afiliados", "", ""); 
var_dump($cnx);
//var_dump($cnx2);

if($cnx) {echo "<br/>Conecto ODBC<br>";}


$strsql= 'SELECT * FROM C:\\xampp\\htdocs\\sindicato\\Data\\afiliados.DBF';
$query = odbc_exec($cnx, $strsql) or die (odbc_errormsg());
while($row = odbc_fetch_array($query))
{
 /*   echo 'Afiliado: '.$row['NOMBRE'].'';
    echo 'DNI: '.$row['NRO_DOCUME'].'';
    echo '<hr />';*/
    $fecha=explode('-',$row['fecha_nacimiento']);
    if ($fecha[0]<1972) {
		echo "<br/>----------------------------------------<br/>Registro [".$c."] - ";
		echo "Tiene mas de 40 a&ntilde;os:<br/>";
		var_dump($row);
		echo "<br/>Fin registro [".$c."]<br/>----------------------------------------<br/>";
	}
    
    $c++;
    
}
odbc_close($odbc);

var_dump($c);

odbc_close($cnx);
?>
