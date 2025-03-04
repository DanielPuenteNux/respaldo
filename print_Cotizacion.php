<?php
session_name('_erp');
session_start();

//require('fpdf.php');
define('FPDF_FONTPATH', 'font/');
require('cellpdf.php');
include("../config/Database.php");
include("../config/Generales.php");

// variable global
global $globalRegion;


class PDF extends CellPDF
{

	function RoundedRect($x, $y, $w, $h, $r, $corners = '1234', $style = '')
    {
        $k = $this->k;
        $hp = $this->h;
        if($style=='F')
            $op='f';
        elseif($style=='FD' || $style=='DF')
            $op='B';
        else
            $op='S';
        $MyArc = 4/3 * (sqrt(2) - 1);
        $this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));

        $xc = $x+$w-$r;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k ));
        if (strpos($corners, '2')===false)
            $this->_out(sprintf('%.2F %.2F l', ($x+$w)*$k,($hp-$y)*$k ));
        else
            $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);

        $xc = $x+$w-$r;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));
        if (strpos($corners, '3')===false)
            $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-($y+$h))*$k));
        else
            $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);

        $xc = $x+$r;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-($y+$h))*$k));
        if (strpos($corners, '4')===false)
            $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-($y+$h))*$k));
        else
            $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);

        $xc = $x+$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$yc)*$k ));
        if (strpos($corners, '1')===false)
        {
            $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$y)*$k ));
            $this->_out(sprintf('%.2F %.2F l',($x+$r)*$k,($hp-$y)*$k ));
        }
        else
            $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
        $this->_out($op);
    }

    function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
    {
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1*$this->k, ($h-$y1)*$this->k,
            $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
    }

	//Page header
	function Header()
	{
	     // max width: 195
	 	$border = 0;
	 	// Logo
	 	$y = $this->GetY();
	 	//$this->Image('../assets/images/background-pdf.png',-0.5, -0.5, 217, 281);
		$this->Image('../assets/images/logo-transparente.png', 15, 20, 60);
		$this->Image('../assets/images/QR_formas_fpdf.png', 168, -8, 32);
		$this->Image('../assets/images/banner2_formas_fpdf.png', -11, -5, 17);

		$this->SetFont('Poppins','',12);
		$this->SetTextColor(0, 0, 0);

		$this->SetY(43);
		$this->SetX(16);
		$this->Cell(40, 5, utf8_decode('COTIZACIÓN:'), $border, 0, 'L');

		$this->SetY(50);
		$this->SetX(16);
		$this->Cell(50, 5, utf8_decode('FECHA DE COTIZACIÓN:'), $border, 0, 'L');

		$this->SetY(57);
		$this->SetX(16);
		$this->Cell(40, 5, 'EJECUTIVO:', $border, 0, 'L');

		$this->SetY(65);
		$this->SetX(16);
		$this->Cell(40, 5, 'CLIENTE:', $border, 0, 'L');

		
	}
	
	function GetMultiCellHeight($w, $h, $txt, $border=null, $align='J') {
		// Calculate MultiCell with automatic or explicit line breaks height
		// $border is un-used, but I kept it in the parameters to keep the call
		//   to this function consistent with MultiCell()
		$cw = &$this->CurrentFont['cw'];
		if($w==0)
			$w = $this->w-$this->rMargin-$this->x;
		$wmax = ($w-2*$this->cMargin)*1000/$this->FontSize;
		$s = str_replace("\r",'',$txt);
		$nb = strlen($s);
		if($nb>0 && $s[$nb-1]=="\n")
			$nb--;
		$sep = -1;
		$i = 0;
		$j = 0;
		$l = 0;
		$ns = 0;
		$height = 0;
		while($i<$nb)
		{
			// Get next character
			$c = $s[$i];
			if($c=="\n")
			{
				// Explicit line break
				if($this->ws>0)
				{
					$this->ws = 0;
					$this->_out('0 Tw');
				}
				//Increase Height
				$height += $h;
				$i++;
				$sep = -1;
				$j = $i;
				$l = 0;
				$ns = 0;
				continue;
			}
			if($c==' ')
			{
				$sep = $i;
				$ls = $l;
				$ns++;
			}
			$l += $cw[$c];
			if($l>$wmax)
			{
				// Automatic line break
				if($sep==-1)
				{
					if($i==$j)
						$i++;
					if($this->ws>0)
					{
						$this->ws = 0;
						$this->_out('0 Tw');
					}
					//Increase Height
					$height += $h;
				}
				else
				{
					if($align=='J')
					{
						$this->ws = ($ns>1) ? ($wmax-$ls)/1000*$this->FontSize/($ns-1) : 0;
						$this->_out(sprintf('%.3F Tw',$this->ws*$this->k));
					}
					//Increase Height
					$height += $h;
					$i = $sep+1;
				}
				$sep = -1;
				$j = $i;
				$l = 0;
				$ns = 0;
			}
			else
				$i++;
		}
		// Last chunk
		if($this->ws>0)
		{
			$this->ws = 0;
			$this->_out('0 Tw');
		}
		//Increase Height
		$height += $h;
	
		return $height;
	}
	
	// //Page footer
	function Footer()
	{

		$this->SetFont('Poppins','B',12);
		$this->SetTextColor(15, 87, 99);
		$this->SetFillColor(188, 190, 192);
		$this->SetY(255);
		$this->Cell(5,5,'', 0, 0,'C');
		$this->Cell(65,8,'FORMAS INTELIGENTES S.A DE C.V.', 0, 0,'L');
		$this->SetTextColor(0, 0, 0);
		$this->SetY(260);
		$this->SetFont('Poppins','',11);
		$this->Cell(5,5,'', 0, 0,'C');
		$this->Cell(60,8,'Bolivia 213 Desarollo Las Torres 91', 0, 0,'L');
		$this->SetY(264.3);
		$this->Cell(5,5,'', 0, 0,'C');
		$this->Cell(55,8,utf8_decode('Monterrey, Nuevo León, C.P.64760'), 0, 0,'L');
		$this->SetTextColor(124, 124, 124);
		$this->SetY(269);
		$this->Cell(5,5,'', 0, 0,'C');
		$this->Cell(50,8,'IV-RCO015 REV-04 NC:YeS', 0, 0,'L');
		$this->SetTextColor(0, 0, 0);
		$this->SetY(260);
		$this->Cell(80,5,'', 0, 0,'C');
		$this->Cell(60,8, utf8_decode('Teléfono: 811 932 5200'), 0, 0,'L');
		$this->SetY(264);
		$this->Cell(80,5,'', 0, 0,'C');
		$this->Cell(60,8,'www.formasinteligentes.com', 0, 0,'L');
		$this->SetY(268);
		$this->Cell(80,5,'', 0, 0,'C');
		$this->Cell(70,8,'ventas@formasinteligentes.com.mx', 0, 0,'L');
		$this->SetY(272);
		$this->Cell(179,5,'', 0, 0,'C');
		$this->Cell(30,5, utf8_decode('Pág. '.$this->PageNo().'/{nb}'), 0, 0, 'C');

		//Footer content
		$this->Image('../assets/images/footer-logo-pdf.png', 16, 245, 30);
	}
	
}

// checamos si recibimos datos
if (isset($_GET['p1']))  {

	$db = new Database;

	// abrimos conexion
	$db->connectDB();  // "mysql_real_escape_string" necesita una conexion abierta
	
	// obtenemos folio
	$intCotizacion = $db->sanitize($_GET['p1']);
	$bytRevision = $db->sanitize($_GET['p2']);
	
	// inicializamos
	$myFilters = "";

	// construimos estatuto en base a filtros propios
	if (strlen($_GET['p1']) > 0) {
		$myFilters .= "intCotizacion = $intCotizacion AND bytRevision = $bytRevision ";
	}else{

		// cerramos conexión
		$db->closeDB();		
		
		echo "Parámetros insuficientes.";
		exit();
	}

	$searchSql = "WHERE " . $myFilters . " ";


	// preparamos estatuto de Consulta
	$sql = "SELECT * FROM view_consulta_cotizaciones $searchSql";
	$results = $db->get_Rows($sql) or die($sql); 

	// cerramos coneccion
	$db->closeDB();


	// bandera de control de bordes
	$border = 0;
	
	// inicializamos hoja de PDF
	$pdf=new PDF('P','mm','Letter');
	$pdf->AddFont('Poppins', '', 'Poppins-Regular.php');
	$pdf->AddFont('Poppins', 'B', 'Poppins-Bold.php');
	$pdf->AliasNbPages();
	$pdf->SetAutoPageBreak(true, 1.0); // bottom margin 1cm.

		
	// agregamos página
	$pdf->AddPage('P');
		
	while ($row = mysqli_fetch_assoc($results)) {
		
		// obtenemos datos del Folio
		$cotizacion = $row['intCotizacion'] . '-' . $row['bytRevision'];

		// abrimos conexion
		$db->connectDB();

		// obtenemos partidas
		$query = "SELECT * FROM view_cotizaciones_partidas WHERE intCotizacion = $intCotizacion AND bytRevision = $bytRevision";
		$results2 = $db->get_Rows($query) or die($query); 

		// cerramos coneccion
		$db->closeDB();
		
		// obtenemos total de partidas
		$itemCount = mysqli_num_rows($results2);
		$firstIteration = true;
				
		while ($item = mysqli_fetch_assoc($results2)) {

			$height = 5;

			//Construimos header de descripcion solo la primera iteracion
			if($firstIteration){

				$pdf->Ln(10);
				$y = $pdf->GetY();
				$pdf->SetX(9);
				$pdf->SetFont('Poppins','B',10);
				$pdf->SetDrawColor(0, 78, 89);
				$pdf->SetFillColor(0, 78, 89);
				$pdf->RoundedRect(15.5, $y-0.4, 185, 9, 4.5, '1234', 'DF');

				// labels
				$pdf->SetTextColor(255, 255, 255);
				$pdf->Cell(0, 9, utf8_decode('DESCRIPCIÓN'), 0, 0, 'C');
				$pdf->SetTextColor(0, 0, 0);
				$firstIteration = false;

				$pdf->Ln(12);
			}


			$currY = $pdf->GetY();
			$currX = $pdf->GetX();

			// Datos generales
			$pdf->SetFont('Poppins','B',12);
			$pdf->SetTextColor(0, 0, 0);

			$pdf->SetY(43);
			$pdf->SetX(48);
			$pdf->Cell(20, 5, $cotizacion, $border, 1, 'L');

			$pdf->SetY($currY);
			
			$pdf->SetY(50);
			$pdf->SetX(68);
			$pdf->Cell(135, 5, $row['strFecha'], $border, 0, 'L');
			$pdf->SetY($currY);
			$pdf->SetX($currX);

			$pdf->SetY(57);
			$pdf->SetX(44);
			$pdf->Cell(125, 5, utf8_decode($row['strUsuarioAlta']), $border, 1, 'L');
			$pdf->SetY($currY);
			$pdf->SetX($currX);

			$pdf->SetY(65);
			$pdf->SetX(39);
			$pdf->Cell(125, 5, utf8_decode($row['strCliente']), $border, 1, 'L');
			$pdf->SetY($currY);
			$pdf->SetX($currX);

			$pdf->SetY($currY+3);

			// Descripcion
			$pdf->SetX(16);
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Poppins','',10);
			$pdf->MultiCell(185, $height, chr(149)." ".utf8_decode($item['strDescripcion']), 0, 'L', false);

			//Posicionamos cantidad
			$currY = $pdf->GetY();

			$pdf->SetY($currY+5);
			$pdf->SetX(120);
			$pdf->SetDrawColor(47, 88, 102);
			$pdf->SetFillColor(47, 88, 102);
			$pdf->RoundedRect(118, $currY+3, 29, 9, 4.5, '1234', 'DF');
			$pdf->SetTextColor(255, 255, 255);
			$pdf->SetFont('Poppins','B',10);
			$pdf->Cell(13, $height, "Cant. : ", 0, 0, 'L');
			$pdf->Cell(12, $height, ($item['dblCantidad'] == 0? '-' : $item['dblCantidad']), 0, 0, 'R');

			//Posicionamos precio unitario
			$pdf->SetX(155);
			$pdf->RoundedRect(153, $currY+3, 45, 9, 4.5, '1234', 'DF');
			$pdf->Cell(20, $height, "P. Unitario: ", 0, 0, 'L');
			$pdf->Cell(21, $height, "$".number_format($item['dblPrecioUnitario'], 2), 0, 1, 'R');

			$currY = $pdf->GetY();

			//Damos un espacio entre partidas
			$pdf->SetY($currY+7);

			if($currY > 200){
				
				$pdf->AddPage('P');
				$currY = $pdf->GetY();

				$pdf->Ln(10);
				$y = $pdf->GetY();
				$pdf->SetX(9);
				$pdf->SetFont('Poppins','B',10);
				$pdf->SetDrawColor(0, 78, 89);
				$pdf->SetFillColor(0, 78, 89);
				$pdf->RoundedRect(15.5, $y-0.4, 185, 9, 4.5, '1234', 'DF');

				// labels
				$pdf->SetTextColor(255, 255, 255);
				$pdf->Cell(0, 9, utf8_decode('DESCRIPCIÓN'), 0, 0, 'C');
				$pdf->SetTextColor(0, 0, 0);

				$pdf->Ln(12);
			
			}
			
		}
						
		
		// SubTotal cuadro decorativo
		$currY = $pdf->GetY();
		$pdf->SetY($currY+2);
		$pdf->SetDrawColor(0, 78, 89);
		$pdf->SetFillColor(0, 78, 89);
		$pdf->RoundedRect(118, $currY-1, 81, 25, 5, '1234', 'DF');

		//Subtotal
		$pdf->SetX(80);
		$pdf->SetFont('Poppins','B',12);
		$pdf->SetTextColor(255, 255, 255);
		$pdf->Cell(75, 6, utf8_decode('SUBTOTAL:'), 0, 0, 'R');
		$pdf->SetFont('Poppins','B',10);
		$pdf->Cell(4, 6, '', 0, 0, 'L');
		$pdf->Cell(21, 6, '$' . number_format($row['dblSubTotal'], 2), 0, 0, 'R');
		$pdf->Cell(11, 6, $row['strSiglasMoneda'], 0, 1, 'R');
		
		// IVA
		$pdf->SetX(80);
		$pdf->SetFont('Poppins','B',12);
		$pdf->SetTextColor(255, 255, 255);
		$pdf->Cell(75, 6, utf8_decode('IVA:'), 0, 0, 'R');
		$pdf->SetFont('Poppins','B',10);
		$pdf->Cell(4, 6, '', 0, 0, 'L');
		$pdf->Cell(21, 6, '$' . number_format($row['dblIVA'], 2), 0, 0, 'R');
		$pdf->Cell(11, 6, $row['strSiglasMoneda'], 0, 1, 'R');

		// TOTAL
		$pdf->SetX(80);
		$pdf->SetFont('Poppins','B',12);
		$pdf->SetTextColor(255, 130, 0);
		$pdf->Cell(75, 6, utf8_decode('TOTAL + IVA:'), 0, 0, 'R');
		$pdf->SetFont('Poppins','B',10);
		$pdf->SetTextColor(255, 255, 255);
		$pdf->Cell(4, 6, '', 0, 0, 'L');
		$pdf->Cell(21, 6, '$' . number_format($row['dblTotal'], 2), 0, 0, 'R');
		$pdf->Cell(11, 6, $row['strSiglasMoneda'], 0, 1, 'R');

		
		// incrementamos contador
		$countQuotes ++;
		
	}
	
	// checamos si es mas de un vale
	if ($countQuotes > 1) {
		$fileName = "Cotizaciones.pdf";
	}else{
		$fileName = "Cotizacion_$cotizacion.pdf";
	}
	
	$pdf->SetTitle($fileName); // nombre del Tab
	$pdf->Output($fileName,'I');
	exit();
}
else {

	echo utf8_encode('Parámetros insuficientes!');
	exit;
}
?>
