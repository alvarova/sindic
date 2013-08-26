<? 

$basedbf="afiliados.DBF"; 
$mifile = explode (".",$basedbf); 

$based = $mifile[0]; 

printf($based); 
printf("<br>"; 

$link = mysql_connect("localhost", "root",""); 
mysql_select_db("sindicatofarm", $link); 


if (($descriptor=dbase_open ($basedbf, 0))==0) 
{ 
printf ("<br>Error al abrir la base de datos"; 
} 
else 
{ 
$num_registros = dbase_numrecords($descriptor); 
$num_campos = dbase_numfields($descriptor); 
$regcampos = dbase_get_header_info($descriptor); 

$cadenasql = "create table " . $based . " ( "; 
$titcampos = ""; 

for ($j=0;$j<$num_campos -1 ;$j++) 
{ 
$cadenasql = $cadenasql . strtolower($regcampos[$j][name]); 
$titcampos = $titcampos . trim(strtolower($regcampos[$j][name])) . ", "; 
if ( strtolower($regcampos[$j][type])=="character" ) 
{ 
if ( Trim($regcampos[$j][length]) > "20" ) 
{ $cadenasql = $cadenasql . " varchar(" . $regcampos[$j][length] . ","; } 
else { $cadenasql = $cadenasql . " char(" . $regcampos[$j][length] . ",";} 
} 
if ( strtolower(trim($regcampos[$j][type]))=="date" ) 
{ 
$cadenasql = $cadenasql . " datetime,"; 
} 
if ( strtolower(trim($regcampos[$j][type]))=="number" ) 
{ 
if ( Trim($regcampos[$j][precision])=="0" 
{ $cadenasql = $cadenasql . " Integer(" . $regcampos[$j][length] . ",";} 
else {$cadenasql = $cadenasql . " decimal (" . $regcampos[$j][length] . ",".$regcampos[$j][precision].",";} 
} 
} 
$cadenasql = $cadenasql . strtolower($regcampos[$j][name]); 
$titcampos = $titcampos . trim(strtolower($regcampos[$j][name])); 

if ( strtolower($regcampos[$j][type])=="character" ) 
{ 
if ( Trim($regcampos[$j][length]) > "20" ) 
{ $cadenasql = $cadenasql . " varchar(" . $regcampos[$j][length] . " )"; } 
else { $cadenasql = $cadenasql . " char(" . $regcampos[$j][length] . " )";} 
} 
if ( strtolower(trim($regcampos[$j][type]))=="date" ) 
{ 
$cadenasql = $cadenasql . " datetime ) )"; 
} 
if ( strtolower(trim($regcampos[$j][type]))=="number" ) 
{ 
if ( Trim($regcampos[$j][precision])=="0" 
{ $cadenasql = $cadenasql . " Integer(" . $regcampos[$j][length] . " )";} 
else {$cadenasql = $cadenasql . " decimal (" . $regcampos[$j][length] . ",".$regcampos[$j][precision]." )";} 
} 

/* Creando la Tabla */ 

/* $result = mysql_query($cadenasql, $link); */ 

/* Insertando los Registros */ 

/* for ($i=1;$i<=$num_registros;$i++) */ 
for ($i=1;$i<=50 ;$i++) 
{ 
$cadenadatos = "" ; 
$registro= dbase_get_record ($descriptor, $i); 
for ($j=0;$j<$num_campos - 1;$j++) 
{ 
if ( strtolower(trim($regcampos[$j][type]))=="date" ) 
{ 
$cadenadatos = $cadenadatos . "'" . (trim($registro[$j])=="" ? "\n" : trim($registro[$j])) . "',"; 
} 
if ( strtolower(trim($regcampos[$j][type]))=="character" ) 
{ 
$cadenadatos = $cadenadatos . "'" . (trim($registro[$j])=="" ? "\n" : trim($registro[$j])) . "',"; 
} 
if ( strtolower(trim($regcampos[$j][type]))=="number" ) 
{ 
$cadenadatos = $cadenadatos . (trim($registro[$j])=="" ? "\n" : trim($registro[$j])) . ","; 
} 

} 
if ( strtolower(trim($regcampos[$j][type]))=="date" ) 
{ 
$cadenadatos = $cadenadatos . "'" . (trim($registro[$j])=="" ? "\n" : trim($registro[$j])) . "'"; 
} 
if ( strtolower(trim($regcampos[$j][type]))=="character" ) 
{ 
$cadenadatos = $cadenadatos . "'" . (trim($registro[$j])=="" ? "\n" : trim($registro[$j])) . "'"; 
} 
if ( strtolower(trim($regcampos[$j][type]))=="number" ) 
{ 
$cadenadatos = $cadenadatos . (trim($registro[$j])=="" ? "\n" : trim($registro[$j])) . ""; 
} 

$cadenasql = "Insert into " . $based . " (" . $titcampos . " Values (" . $cadenadatos . ""; 
$cadenasql = strip_tags($cadenasql); 
printf($cadenasql); 
$result = mysql_query($cadenasql, $link) or die ("Invalid query"; 
printf("<br>"; 
} 

dbase_close($descriptor); 
printf ("<br>Base de datos cerrada"; 
} 
mysql_close( $link); 

?> 
