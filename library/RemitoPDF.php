<?php

require_once('./library/fpdf.php');

class RemitoPDF
{
	private $_titulo;
	private $_cabeceraRotulos;
	private $_cabeceraPosicion;
	private $_elementosTabla;
	private $_elementosAlinea;	
	private $_fill; //Relleno para las filas de la tabla
	private $_crearotulos; //Relleno para las filas de la tabla
	private $_reg; //Cantidad de registros procesados
	private $_elementosFuente; //permite definir la altura de los elementos segun la columna a la que correspondan
	static private $pdf;  //Implementando instancia bajo diseño Singleton para evitar re-referenciar 2 veces el mismo objeto.

	public function __construct($titulo, $crearotulos = false) //, array $rotulos, array $posicion, array $elementos)
	{
		$this->_titulo = $titulo;
		$this->_crearotulos = $crearotulos;
		
		if ( !self::$pdf instanceof self){  //Asegurarnos que sea la unica instancia SI o SI - patron singleton
			self::$pdf = new FPDF();
		}
		$this->_elementosAlinea[] = array('C','C','C','C','C','C','C','C');  //Hasta 8 columnas 
		$this->_elementosFuente[] = array('8','8','8','8','8','8','8','8');
	}

   public function __clone()
   {
      trigger_error("Operación Invalida: No es posible clonar una instancia de ". get_class($this) ." class. Metodo Singleton Implementado.", E_USER_ERROR );
   }

	public function agregar_rotulo ($rotulos)
	{
		$this->_cabeceraRotulos[] = $rotulos;
	}
	public function agregar_posicion ($posicion)
	{	
		$this->_cabeceraPosicion[] = $posicion;
	}
	public function agregar_listado ($elementos)
	{
		$this->_elementosTabla = $elementos;
	}
	public function alinea_columnas (array $alineacion)
	{
		$this->_elementosAlinea = $alineacion;
	}	

	public function altura_fuente_columnas (array $fuente_columna)
	{
		$this->_elementosFuente = $fuente_columna;
	}	

	public function inicializar()
	{		

    		$header = $this->_cabeceraRotulos;

			self::$pdf->AcceptPageBreak=true;
			self::$pdf->AddPage();
			self::$pdf->SetAuthor("VNDesign.com.ar");
			self::$pdf->SetCreator("VNDesign.com.ar");

	}
	public function cargaelemento(array $elemento)
	{
					
			if (($this->_reg % 2)) {$y=144;} else { $y=11;}
			
			self::$pdf->Image('./images/farmacia.jpg', 15, $y, 12);
			self::$pdf->SetFont('Arial','B',15);
			self::$pdf->Cell(20);
			    // Framed title
			self::$pdf->Cell(80,7,utf8_decode('Asociación Trabajadores'),0,0,'L');
			self::$pdf->Ln(6);
			self::$pdf->Cell(20);
			self::$pdf->Cell(80,7,' de Farmacia Santa Fe',0,0,'L');
			self::$pdf->SetFont('Times','',10);
			self::$pdf->Ln(6);
			self::$pdf->Cell(20);
			self::$pdf->Cell(50,7,'Fundada el 10 de Marzo de 1945',0,0,'L');
			
			self::$pdf->Ln(3);
			self::$pdf->Cell(20);
			self::$pdf->Cell(50,7,utf8_decode('Personería Gremial Nº 1068'),0,0,'L');			
			
			self::$pdf->Ln(3);
			self::$pdf->Cell(20);
			self::$pdf->Cell(50,7,utf8_decode('Personería Jurídica Nº 03004'),0,0,'L');			
			self::$pdf->SetFont('Times','',10);
			//self::$pdf->Cell(110,10, $this->_titulo,1,0,'C');
			    // Line break
			self::$pdf->Ln(2);
			//self::$pdf->SetFont('Arial','',8);
			//self::$pdf->SetRightMargin(10);		

			self::$pdf->SetFont('Arial','U',12);
			self::$pdf->Cell(15);
			    // Framed title
			self::$pdf->Cell(160,20,strtoupper(utf8_decode('REMITO ENVIO '.$this->_titulo)),0,0,'C');
			self::$pdf->Ln(10);
			
			self::$pdf->SetFont('Arial','',10);
			self::$pdf->Cell(195,20,strtoupper(utf8_decode('FECHA ENVIO ____/_____/____ ')),0,0,'R'); 			self::$pdf->Ln(7);
			self::$pdf->SetFont('Arial','B',10);
			self::$pdf->Cell(195,20,strtoupper(utf8_decode('FARMACIA: '.$elemento[2].' / tel.'.$elemento[5])),0,0,'C'); 			self::$pdf->Ln(7);
			self::$pdf->SetFont('Arial','',10);
			self::$pdf->Cell(20); self::$pdf->Cell(125,20,strtoupper(utf8_decode('DESTINATARIO: '.$elemento[0])),0,0,'L'); 			self::$pdf->Ln(7);
			self::$pdf->Cell(20); self::$pdf->Cell(125,20,strtoupper(utf8_decode('DOMICILIO: '.$elemento[3])),0,0,'L'); 			self::$pdf->Ln(7);
			self::$pdf->Cell(20); self::$pdf->Cell(125,20,strtoupper(utf8_decode('LOCALIDAD: '.$elemento[4])),0,0,'L'); 			self::$pdf->Ln(1);
			 self::$pdf->Cell(190,20,strtoupper(utf8_decode('FECHA RECEPCION ____/_____/____')),0,0,'R'); 			self::$pdf->Ln(10);
			 self::$pdf->Cell(190,20,strtoupper(utf8_decode('FIRMA    _____________________________')),0,0,'R'); 			self::$pdf->Ln(7);
			 self::$pdf->Cell(190,20,strtoupper(utf8_decode('Aclaración    _____________________________')),0,0,'R'); 			self::$pdf->Ln(7);
			self::$pdf->SetFont('Arial','I',10);
			self::$pdf->Cell(20); self::$pdf->Cell(169,24,(utf8_decode(' Devolver este remito conformado al remitente:')),0,0,'L'); 	self::$pdf->Ln(7);
			self::$pdf->SetFont('Arial','B',10);
			self::$pdf->Cell(10); self::$pdf->Cell(85,30,(utf8_decode('Chacabuco 1818.')),0,0,'R'); 			self::$pdf->Ln(6);
			self::$pdf->Cell(10); self::$pdf->Cell(85,30,(utf8_decode('Tel./Fax: 0342-4527803')),0,0,'R'); 			self::$pdf->Ln(6);
			self::$pdf->Cell(10); self::$pdf->Cell(85,30,(utf8_decode('3000 - Santa Fe.')),0,0,'R'); 			self::$pdf->Ln(6);
			self::$pdf->Ln(25);
			$this->_reg++;
//		    self::$pdf->Ln(); //Termino la fila actual
		    
			if ($this->_crearotulos) {
				self::$pdf->AddPage();
				self::$pdf->SetFont('Arial','B',21);
				self::$pdf->Ln(10);
				self::$pdf->Cell(195,20,strtoupper(utf8_decode('FARMACIA: '.$elemento[2])),0,0,'C'); 			self::$pdf->Ln(18);
				self::$pdf->Ln(10);
				self::$pdf->SetFont('Arial','',17);
				self::$pdf->Cell(20); self::$pdf->Cell(185,20,strtoupper(utf8_decode('DESTINATARIO: '.$elemento[0])),0,0,'L'); 			self::$pdf->Ln(18);
				self::$pdf->Cell(20); self::$pdf->Cell(185,20,strtoupper(utf8_decode('DOMICILIO: '.$elemento[3])),0,0,'L'); 			self::$pdf->Ln(18);
				self::$pdf->Cell(20); self::$pdf->Cell(185,20,strtoupper(utf8_decode('LOCALIDAD: '.$elemento[4])),0,0,'L'); 			self::$pdf->Ln(18);
				self::$pdf->SetFont('Arial','B',18);
				self::$pdf->Ln(20);

				self::$pdf->Cell(10); self::$pdf->Cell(180,30,(utf8_decode('REMITENTE:')),0,0,'C'); 			self::$pdf->Ln(15);
				self::$pdf->Cell(10); self::$pdf->Cell(180,30,(utf8_decode('Asociación Trabajadores')),0,0,'R'); 			self::$pdf->Ln(15);
				self::$pdf->Cell(10); self::$pdf->Cell(180,30,(utf8_decode('de Farmacia de Santa Fe')),0,0,'R'); 			self::$pdf->Ln(15);
				self::$pdf->Cell(10); self::$pdf->Cell(180,30,(utf8_decode('Chacabuco 1818')),0,0,'R'); 			self::$pdf->Ln(15);
				self::$pdf->Cell(10); self::$pdf->Cell(180,30,(utf8_decode('Tel./Fax: 0342-4527803')),0,0,'R'); 			self::$pdf->Ln(15);
				self::$pdf->Cell(10); self::$pdf->Cell(180,30,(utf8_decode('CP: 3000 - Santa Fe')),0,0,'R'); 			self::$pdf->Ln(15);
				self::$pdf->Ln(10);
				self::$pdf->AddPage();
				$this->_reg++;
			}else{
			if (($this->_reg % 2)) {
				self::$pdf->Image('./images/lineapuntos2.jpg', 15, 130, 180);	
			}else{
				self::$pdf->AddPage();					
			}
			


			}
			    // Línea de cierre
	}

	public function finalizar(){

		    self::$pdf->SetFont('Arial','I',9);
		    // Print centered page number
		    self::$pdf->Ln();
		    $fecha=date("m-d-Y");
		    self::$pdf->Cell(0,10, 'Se volcaron '.self::$pdf->PageNo().' paginas, con un total de '.$this->_reg.' registros. Fecha de realizacion: '.$fecha,0,0,'R');
		    self::$pdf->Ln();
		    self::$pdf->SetFont('Arial','I',6);
		    self::$pdf->Cell(0,7,'Desarrollado por Design-sitio-web.com.ar  - VNDesign Santa Fe',0,0,'R');
		    self::$pdf->Ln();
			self::$pdf->Output();
	}
} //cierre de clase