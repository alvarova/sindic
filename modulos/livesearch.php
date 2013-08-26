<?

/* livesearch
 * Modulo de consulta de afiliados 
 * No modificar Exclusivamente para el uso de la funcion incrustada.
 * Notes:
query - original query value
suggestions - comma separated array of suggested values
data (optional) - data array, that contains values for callback function when data is selected.
 */

$data = array();
$str="";

if (isset($_POST['s'])) {

        $str = $_POST['s'];
        $pre="../";  //Nivel del directorio para agregar DB y Pear
        include_once($pre."localconf.php");
        error_reporting(E_ALL);
        $sql="";
        $db2 = ADONewConnection();
        $db2->debug = false;
        $result2 = $db2->Connect("$dbhost", "$dbuname", "", "$dbname");

        //Enlistamos todos los que tengan un patron determinado STR
        
        $sql="SELECT * FROM  `afiliados` WHERE  `nombre` LIKE  '%".$str."%' LIMIT 0 , 5";
        $rs2=$db2->Execute($sql);

        if ($rs2 === false) die("Fallo consultando lista de grupos...".$sql);
        $val=$db2->Affected_Rows();
        while (!$rs2->EOF) {
                        echo "<li>".$rs2->fields['nombre']."</li>";                        
                        $rs2->MoveNext();
        }
}
//header("Content-type: application/json");
//echo json_encode($data);
