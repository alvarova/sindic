<?

/*
 * Modulo de consulta para emails volcado datos en formato json
 * No modificar Exclusivamente para el uso de la funcion incrustada.
 * Web page that provides data for Ajax Autocomplete, in our case autocomplete.ashx will receive GET request with querystring ?query=Li, and it must return JSON data in the following format:
        {
        query:'Li',
        suggestions:['Liberia','Libyan Arab Jamahiriya','Liechtenstein','Lithuania'],
        data:['LR','LY','LI','LT']
        }
Notes:
query - original query value
suggestions - comma separated array of suggested values
data (optional) - data array, that contains values for callback function when data is selected.
 */

$data = array();
$str="";

if (isset($_GET['q'])) {

        $str = $_GET['q'];
        //if (strlen($str)>2) {
                $pre="../";  //Nivel del directorio para agregar DB y Pear
                include_once($pre."localconf.php");
                error_reporting(E_ALL);
                $sql="";


                $db2 = ADONewConnection();
                $db2->debug = false;
                $result2 = $db2->Connect("$dbhost", "$dbuname", "", "$dbname");

                $sql2="SELECT DISTINCT (nro_documento), nombre, id_afiliado FROM afiliados GROUP BY nro_documento ORDER BY id_afiliado DESC"; //Elimina duplicados ordenados de mayor a menor por id

                //Enlistamos todos los
                $sql="SELECT * FROM  `afiliados` WHERE  `nombre` LIKE  '%".$str."%' LIMIT 0 , 5";
                $rs2=$db2->Execute($sql);

                if ($rs2 === false) die("Fallo consultando lista de grupos...".$sql);
                $val=$db2->Affected_Rows();

                while (!$rs2->EOF) {
                        $id=$rs2->fields['nro_documento'];
                        $value=$rs2->fields['nombre'];
                        $info=$rs2->fields['domicilio'];

                        $json = array();
                        $json['value'] = $value;
                        $json['id'] = $id;
                        $json['info'] = $info;
                        $data[] = $json;
                        $rs2->MoveNext();
                }
        //}
}
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0
header ("Content-type: application/json");
echo '{"results":'.json_encode($data)."}";
