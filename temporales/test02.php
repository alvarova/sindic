<?php
include('.\library\adodb5\adodb.inc.php');
include_once('.\library\adodb5\adodb-pager.inc.php');


$db = ADONewConnection('vfp');
$dsn = "Driver={Microsoft Visual FoxPro Driver};SourceType=DBF;SourceDB=C:\\xampp\\htdocs\\sindicato\\Data\\afiliados.dbf;Exclusive=NO;collate=Machine;NULL=NO;DELETED=NO;BACKGROUNDFETCH=NO;";
//$dsn="Driver={Microsoft Visual FoxPro Driver};SourceType=DBF;SourceDB=C:\\xampp\\htdocs\\sindicato\\Data\\afiliados.dbf;Exclusive=No;";
$db->Connect($dsn) or die('nope');
$db->SetFetchMode(ADODB_FETCH_ASSOC);
$query = "Select * from afiliados";
$rs = $db->Execute($query);
while (!$rs->EOF) {
    print_r($rs->fields);
    $rs->MoveNext();
}
?>
