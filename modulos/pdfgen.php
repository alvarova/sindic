<?
require_once('../library/fpdf.php');

/*
 * Genera el listado de afiliados segun un criterio
 * 
 * $pdf=new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(40,10,'Este es un ejemplo de creación de un documento PDF con PHP');
$pdf->Output();*/

//1) Cumpleaños titlares segun el mes en curso o seleccionado brindando farmacia que corresponde con todos los datos 
//SELECT * FROM `afiliados`  LEFT OUTER JOIN `farmacias` ON `afiliados`.`id_farmacia` = `farmacias`.`id_farmacia`  WHERE `fecha_nacimiento` LIKE '%%%%-11-%%' 

$pdf = new FPDF();
// Títulos de las columnas


$header = array('Af', 'Nombre', 'Domicilio', 'Localidad', 'DNI', 'Estado Civil');
// Carga de datos
$data="";
$dsn = "Driver={Microsoft Visual FoxPro Driver};SourceType=DBF;SourceDB=C:\\xampp\\htdocs\\sindicato\\Data\\afiliados.DBF;Exclusive=NO;collate=Machine;NULL=NO;DELETED=NO;BACKGROUNDFETCH=NO;";
$rs=odbc_connect($dsn,"","");
$result = odbc_exec ($rs, "select * from C:\\xampp\\htdocs\\sindicato\\Data\\afiliados.DBF");
$texto="";
$c=0;
while($array = odbc_fetch_array($result))
		{
			$c++;
			$texto[$c][0]=$array['id_afiliado'];
			$texto[$c][1]=$array['nombre'];
			$texto[$c][2]=$array['domicilio'];
			$texto[$c][3]=$array['localidad'];
			$texto[$c][4]=$array['nro_documento'];
			$texto[$c][5]=$array['estado_civil'];			
		}	

//$data = $pdf->LoadData('paises.txt');
//SETEAMOS LOS PRIMEROS PARAMETROS PARA LA GENERACION DE LOS PDFs


$pdf->AcceptPageBreak=true;
$pdf->AddPage();
$pdf->SetAuthor("VNDesign.com.ar");
$pdf->SetCreator("VNDesign.com.ar");
$pdf->SetFont('Arial','B',15);
$pdf->Cell(80);
    // Framed title
$pdf->Cell(30,10,'Gestfar',1,0,'C');
    // Line break
$pdf->Ln(20);
$pdf->SetFont('Arial','',8);
$pdf->SetRightMargin(10);

//Header
    $pdf->SetFillColor(80,150,230);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetDrawColor(0,0,128);
    $pdf->SetLineWidth(.3);
    $pdf->SetFont('','B');
    // Cabecera
    $w = array(10, 55, 55, 35, 20, 20);
    for($i=0;$i<count($header);$i++)
        $pdf->Cell($w[$i],7,$header[$i],1,0,'C',true);
    $pdf->Ln();
    
        
//Body

    // Restauración de colores y fuentes
    $pdf->SetFillColor(224,235,255);
    $pdf->SetTextColor(0);
    //$pdf->SetFont('');
    $pdf->SetFont('','',8);
    // Datos
    $fill = false;
    $i=0;
    //var_dump($texto);
    foreach($texto as $row)
    {
		$i++;
		if( $i & 1 ) {
			$formato='0';
			$fill=false;
		}else{
			$formato='0';
			$fill=true;
		}
        $pdf->Cell(10,10,$row[0],0,0,'C',$fill);  //nro afiliado
        $pdf->Cell(55,10,$row[1],0,0,'C',$fill);  //nombre
        $pdf->Cell(55,10,$row[2],0,0,'C',$fill); //dom
        $pdf->Cell(35,10,$row[3],0,0,'L',$fill); //localidad
        $pdf->Cell(20,10,$row[4],0,0,'L',$fill); //dni
        $pdf->Cell(20,10,substr($row[5],0,11),0,0,'L',$fill); //estado civil
        $pdf->Ln();
        $fill = !$fill;
        //if ($i>140) break;
    }
    // Línea de cierre
    $pdf->Cell(array_sum($w),0,'','T');
// Footer
    $pdf->SetY(-15);
    // Select Arial italic 8
    $pdf->SetFont('Arial','I',8);
    // Print centered page number
    $pdf->Cell(array_sum($w),0,'','T');
    $pdf->Ln();
    $pdf->Cell(0,10,'Total de Paginas renderizadas:'.$pdf->PageNo(),0,0,'C');
    $pdf->Ln();
    $pdf->Cell(0,10,'Desarrollado por VNDesign.com.ar',0,0,'C');
    $pdf->Ln();
    $pdf->Cell(array_sum($w),0,'','T');


$pdf->Output();
?>
