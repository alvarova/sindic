<?php

require_once('./library/fpdf.php');

class ListadoPDF
{
	private $_titulo;
	private $_cabeceraRotulos;
	private $_cabeceraPosicion;
	private $_elementosTabla;
	private $_elementosAlinea;	
	private $_fill; //Relleno para las filas de la tabla
	private $_reg; //Cantidad de registros procesados
	private $_elementosFuente; //permite definir la altura de los elementos segun la columna a la que correspondan
	static private $pdf;  //Implementando instancia bajo diseño Singleton para evitar re-referenciar 2 veces el mismo objeto.

	public function __construct($titulo) //, array $rotulos, array $posicion, array $elementos)
	{
		$this->_titulo = $titulo;
		
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
			self::$pdf->SetFont('Arial','B',15);
			self::$pdf->Cell(1);
			    // Framed title
			self::$pdf->Cell(40,10,'Gestfar',1,0,'C');
			self::$pdf->SetFont('Times','',10);
			self::$pdf->Cell(110,10, $this->_titulo,1,0,'C');
			    // Line break
			self::$pdf->Ln(20);
			self::$pdf->SetFont('Arial','',8);
			self::$pdf->SetRightMargin(10);

				// Formamos el Header
			    self::$pdf->SetFillColor(80,150,230);
			    self::$pdf->SetTextColor(255,255,255);
			    self::$pdf->SetDrawColor(0,0,128);
			    self::$pdf->SetLineWidth(.3);
			    self::$pdf->SetFont('','B');
			    // Seteamos la Cabecera con los datos de las columnas

			    for($i=0;$i<count($this->_cabeceraPosicion);$i++)
			        self::$pdf->Cell($this->_cabeceraPosicion[$i],7,$this->_cabeceraRotulos[$i],1,0,'C',true);
			    self::$pdf->Ln();
			    
			        
				//Formar el Body
			    
			    // Restauración de colores y fuentes
			    self::$pdf->SetFillColor(224,235,255);
			    self::$pdf->SetTextColor(0);
			    //self::$pdf->SetFont('');
			    self::$pdf->SetFont('','',8);
			    // Datos
			    $this->fill = false;
			    $this->reg=0;
			    $i=0;

	}
	public function cargaelemento(array $elemento)
	{
					///var_dump($this->_elementosAlinea)
					for($i=0;$i<count($this->_cabeceraPosicion);$i++)
					{

							// De donde provenia esa variable fuente (abajo)  si no existe en el contexto del objeto?????

						if ($this->_elementosFuente[$i]!=$fuente) {
							self::$pdf->SetFont('','', $this->_elementosFuente[$i]);
							$fuente=$this->_elementosFuente[$i];
						}
						
						
						self::$pdf->Cell($this->_cabeceraPosicion[$i],10, $elemento[$i],0,0, $this->_elementosAlinea[$i] ,$this->_fill);  //nombre	
					}
					$this->_fill=!$this->_fill;
					$this->_reg++;
			        self::$pdf->Ln(); //Termino la fila actual
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