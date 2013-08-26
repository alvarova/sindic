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
        $pre="../";  //Nivel del directorio para agregar DB y Pear
        include_once($pre."localconf.php");
        error_reporting(E_ALL);
        $sql="";


                $db2 = ADONewConnection();
                $db2->debug = false;
                $result2 = $db2->Connect("$dbhost", "$dbuname", "", "$dbname");



                //Enlistamos todos los
                $sql="SELECT *  FROM  `email` WHERE `observacion` LIKE  '%".$str."%'";
                $rs2=$db2->Execute($sql);

                        if ($rs2 === false) die("Fallo consultando lista de grupos...".$sql);
                        $val=$db2->Affected_Rows();

                        while (!$rs2->EOF) {
                                        $id=$rs2->fields['id_email'];
                                        $dir=trim($rs2->fields['direccion']);
                                        $obs=$rs2->fields['observacion'];
                                        $txt=$obs."(".$dir.")";
                                        $json = array();
                                        $json['value'] = $id;
                                        $json['name'] = $txt;
                                        $data[] = $json;
                                        $rs2->MoveNext();
                        }
}
header("Content-type: application/json");
echo json_encode($data);
