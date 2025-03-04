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

	function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link=''){
		$k=$this->k;

		if($this->y+$h>$this->PageBreakTrigger && !$this->InHeader && !$this->InFooter && $this->AcceptPageBreak())
		{
			$x=$this->x;
			$ws=$this->ws;
			if($ws>0)
			{
				$this->ws=0;
				$this->_out('0 Tw');
			}
			$this->AddPage($this->CurOrientation);
			$this->x=$x;
			if($ws>0)
			{
				$this->ws=$ws;
				$this->_out(sprintf('%.3F Tw',$ws*$k));
			}
		}

		if($w==0)
			$w=$this->w-$this->rMargin-$this->x;
		$s='';
		if($fill || $border==1)
		{
			if($fill)
				$op=($border==1) ? 'B' : 'f';
			else
				$op='S';
			$s=sprintf('%.2F %.2F %.2F %.2F re %s ',$this->x*$k,($this->h-$this->y)*$k,$w*$k,-$h*$k,$op);
		}
		if(is_string($border))
		{
			$x=$this->x;
			$y=$this->y;
			if(is_int(strpos($border,'L')))
				$s.=sprintf('%.2F %.2F m %.2F %.2F l S ',$x*$k,($this->h-$y)*$k,$x*$k,($this->h-($y+$h))*$k);
			if(is_int(strpos($border,'T')))
				$s.=sprintf('%.2F %.2F m %.2F %.2F l S ',$x*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-$y)*$k);
			if(is_int(strpos($border,'R')))
				$s.=sprintf('%.2F %.2F m %.2F %.2F l S ',($x+$w)*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
			if(is_int(strpos($border,'B')))
				$s.=sprintf('%.2F %.2F m %.2F %.2F l S ',$x*$k,($this->h-($y+$h))*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
		}
		if($txt!='')
		{
			if($align=='R')
				$dx=$w-$this->cMargin-$this->GetStringWidth($txt);
			elseif($align=='C')
				$dx=($w-$this->GetStringWidth($txt))/2;
			elseif($align=='FJ')
			{
				//Set word spacing
				$wmax=($w-2*$this->cMargin);
				$nb=substr_count($txt,' ');
				if($nb>0)
					$this->ws=($wmax-$this->GetStringWidth($txt))/$nb;
				else
					$this->ws=0;
				$this->_out(sprintf('%.3F Tw',$this->ws*$this->k));
				$dx=$this->cMargin;
			}
			else
				$dx=$this->cMargin;
			$txt=str_replace(')','\\)',str_replace('(','\\(',str_replace('\\','\\\\',$txt)));
			if($this->ColorFlag)
				$s.='q '.$this->TextColor.' ';
			$s.=sprintf('BT %.2F %.2F Td (%s) Tj ET',($this->x+$dx)*$k,($this->h-($this->y+.5*$h+.3*$this->FontSize))*$k,$txt);
			if($this->underline)
				$s.=' '.$this->_dounderline($this->x+$dx,$this->y+.5*$h+.3*$this->FontSize,$txt);
			if($this->ColorFlag)
				$s.=' Q';
			if($link)
			{
				if($align=='FJ')
					$wlink=$wmax;
				else
					$wlink=$this->GetStringWidth($txt);
				$this->Link($this->x+$dx,$this->y+.5*$h-.5*$this->FontSize,$wlink,$this->FontSize,$link);
			}
		}
		if($s)
			$this->_out($s);
		if($align=='FJ')
		{
			//Remove word spacing
			$this->_out('0 Tw');
			$this->ws=0;
		}
		$this->lasth=$h;
		if($ln>0)
		{
			$this->y+=$h;
			if($ln==1)
				$this->x=$this->lMargin;
		}
		else
			$this->x+=$w;
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

	//Page header
	function Header()
	{
		if ( $this->PageNo() !== 1) {
			
             // max width: 195
			$border = 0;

			// Logo
			$y = $this->GetY();
			$this->Image('../assets/images/background_cotizacion_nux_fpdf.png',-0.5, -0.5, 217, 281);
			$this->Image('../assets/images/logo_color_fpdf.png', 22, 20, 40);

			$this->SetFont('Poppins','',12);
			$this->SetTextColor(0, 0, 0);

			$this->SetY(28);
			$this->SetX(145);
			$this->Cell(40, 5, utf8_decode('COTIZACIÓN:'), $border, 0, 'L');

			$this->SetY(50);
			$this->SetX(25);
			$this->Cell(50, 5, utf8_decode('FECHA:'), $border, 0, 'L');

			$this->SetY(57.5);
			$this->SetX(25);
			$this->Cell(40, 5, 'EJECUTIVO:', $border, 0, 'L');

			$this->SetY(65);
			$this->SetX(25);
			$this->Cell(40, 5, 'CLIENTE:', $border, 0, 'L');
			
        }

	}
	
	// //Page footer
	function Footer()
	{
		if ( $this->PageNo() !== 1) {
			
			$this->SetFont('Poppins','B',12);
			$this->SetTextColor(15, 87, 99);
			$this->SetY(270);
			$this->SetX(185);
			$this->Cell(30,5, utf8_decode('Pág. '.$this->PageNo().'/{nb}'), 0, 0, 'C');
		}
	}
}

// checamos si recibimos datos
if (isset($_GET['p1']))  {

	$db = new Database;

	// validamos el token
	// if (!check_ValidToken('get')){		
	// 	echo 'Token inválido!';
	// 	exit;
	// }


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

	$rowPortada = mysqli_fetch_assoc($results);

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
	$totalPages = 0;

	// agregamos página para la portada
	$pdf->AddPage('P');

	//Inicializamos portada
	$pdf->Image('../assets/images/background_portada_nux_fpdf.jpg',-0.5, -0.5, 217, 281);
	$pdf->Image('../assets/images/logo_fpdf_nux.png', 75, 65, 70);

	$pdf->SetDrawColor(255, 255, 255);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->Rect(68, 100, 80, 2, 'DF');

	$pdf->SetFont('Poppins','B',20);
	$pdf->SetTextColor(255, 255, 255);
	$pdf->SetY(110);
	$pdf->Cell(0, 5, utf8_decode('COTIZACIÓN DE PROYECTO'), $border, 0, 'C');

	$pdf->SetY(120);
	$pdf->Cell(0, 5, utf8_decode($rowPortada['strCliente']), $border, 1, 'C');

	// agregamos página para el contenido
	$pdf->AddPage('P');

	//Obtenemos height de la hoja
	$pageHeight = $pdf->GetPageHeight();

	// abrimos conexion
	$db->connectDB();

	// preparamos estatuto de Consulta
	$sql = "SELECT * FROM view_consulta_cotizaciones $searchSql";
	$results = $db->get_Rows($sql) or die($sql);

	// cerramos coneccion
	$db->closeDB();
		
	while ($row = mysqli_fetch_assoc($results)) {
		
		// obtenemos datos del Folio
		$cotizacion = $row['intCotizacion'] . '-' . $row['bytRevision'];
						
		$border = 0;

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

			//Construimos header de descripcion solo la primera iteracion
			if($firstIteration){

				$pdf->Ln(10);
				$y = $pdf->GetY();
				$pdf->SetX(9);
				$pdf->SetFont('Poppins','B',10);
				$pdf->SetDrawColor(199, 200, 202);
				$pdf->SetFillColor(0, 70, 118);
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

			$pdf->SetY(28);
			$pdf->SetX(173);
			$pdf->SetTextColor(0, 0, 0);
			$pdf->Cell(20, 5, $cotizacion, $border, 1, 'L');

			$pdf->SetY($currY);

			$pdf->SetY(50);
			$pdf->SetX(43);
			$pdf->Cell(135, 5, $row['strFecha'], $border, 0, 'L');
			$pdf->SetY($currY);
			$pdf->SetX($currX);

			$pdf->SetY(57.5);
			$pdf->SetX(50);
			$pdf->Cell(125, 5, utf8_decode($row['strUsuarioAlta']), $border, 1, 'L');
			$pdf->SetY($currY);
			$pdf->SetX($currX);

			$pdf->SetY(65);
			$pdf->SetX(44);
			$pdf->Cell(125, 5, utf8_decode($row['strCliente']), $border, 1, 'L');
			$pdf->SetY($currY);
			$pdf->SetX($currX);


			//Obtenemos el espacio que va a tomar la siguiente partida
			$nextHeight = $pdf->GetMultiCellHeight(160, 5, $item['strDescripcion']);

			//Obtenemos el espacio actual, incrementamos 20 lineas de los cuadros de sumatoria y firma e incrementamos las lineas que va a ocupar el texto de la partida
			$currY = ($pdf->GetY() + 20) + $nextHeight;

			//Obtenemos espacio restante en la hoja
			$availableSpace = $pageHeight - $currY;

			//Revisamos si aun tenemos espacio contemplando la nueva partida si no para inicializar otra hoja (40 es el margen limite de la hoja)
			if($availableSpace < 40){
				
				$pdf->AddPage('P');
				$currY = $pdf->GetY();

				// Datos generales
				$pdf->SetFont('Poppins','B',12);

				$pdf->SetY(28);
				$pdf->SetX(173);
				$pdf->SetTextColor(0, 0, 0);
				$pdf->Cell(20, 5, $cotizacion, $border, 1, 'L');

				$pdf->SetY($currY);

				$pdf->SetY(50);
				$pdf->SetX(43);
				$pdf->Cell(135, 5, $row['strFecha'], $border, 0, 'L');
				$pdf->SetY($currY);
				$pdf->SetX($currX);

				$pdf->SetY(57.5);
				$pdf->SetX(50);
				$pdf->Cell(125, 5, utf8_decode($row['strUsuarioAlta']), $border, 1, 'L');
				$pdf->SetY($currY);
				$pdf->SetX($currX);

				$pdf->SetY(65);
				$pdf->SetX(44);
				$pdf->Cell(125, 5, utf8_decode($row['strCliente']), $border, 1, 'L');
				$pdf->SetY($currY);
				$pdf->SetX($currX);

				$pdf->Ln(10);
				$y = $pdf->GetY();
				$pdf->SetX(9);
				$pdf->SetFont('Poppins','B',10);
				$pdf->SetDrawColor(199, 200, 202);
				$pdf->SetFillColor(0, 70, 118);
				$pdf->RoundedRect(15.5, $y-0.4, 185, 9, 4.5, '1234', 'DF');

				// labels
				$pdf->SetTextColor(255, 255, 255);
				$pdf->Cell(0, 9, utf8_decode('DESCRIPCIÓN'), 0, 0, 'C');
				$pdf->SetTextColor(0, 0, 0);

				$pdf->Ln(12);
			}
			
			// abrimos conexion
			$db->connectDB();

			$intPartida = $item['intPartida'];
			
			// preparamos estatuto de Consulta
			$sqlImages = "SELECT * FROM tblcotizaciones_p_docs WHERE intCotizacion = $intCotizacion AND bytRevision = $bytRevision AND intPartida = $intPartida AND intTipoDocumentoCot = 3";
			$resultsImages = $db->get_Rows($sqlImages) or die($sqlImages); 
			$rowImage = mysqli_fetch_assoc($resultsImages);

			// cerramos coneccion
			$db->closeDB();

			$height = 5;
			$currY = $pdf->GetY();

			//Revisamos si tiene imagen
			if($rowImage['strFileName'] != NULL && $rowImage['intTipoArchivo'] == 2 || $rowImage['intTipoArchivo'] == 4){ 
				$pdf->SetX(15);
				$pdf->Image('../files/'.$rowImage['strFileName'], 17, $currY+1, 22);
			}else{
				$pdf->SetX(15);
				$pdf->Image('../assets/images/imagen_no_disponible.jpg', 18, $currY-3, 22);
			}
			
			// Descripcion
			$pdf->SetX(44);
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Poppins','',10);
			$pdf->MultiCell(160, $height, chr(149)." ".utf8_decode($item['strDescripcion']), 0, 'L', false);

			//Posicionamos cantidad
			$currY = $pdf->GetY();

			
			$pdf->SetY($currY+9);
			$pdf->SetX(120);
			$pdf->SetFillColor(1, 46, 73);
			$pdf->RoundedRect(118, $currY+7, 29, 9, 4.5, '1234', 'DF');
			$pdf->SetTextColor(255, 255, 255);
			$pdf->SetFont('Poppins','B',10);
			$pdf->Cell(10, $height, "Cant. : ", 0, 0, 'L');
			$pdf->Cell(15, $height, ($item['dblCantidad'] == 0? '-' : $item['dblCantidad']), 0, 0, 'R');

			//Posicionamos precio unitario
			$pdf->SetX(155);
			$pdf->SetFillColor(1, 46, 73);
			$pdf->RoundedRect(153, $currY+7, 45, 9, 4.5, '1234', 'DF');
			$pdf->Cell(10, $height, "P. Unitario: ", 0, 0, 'L');
			$pdf->Cell(31, $height, "$".number_format($item['dblPrecioUnitario'], 2), 0, 1, 'R');

			$currY = $pdf->GetY();

			//Damos un espacio entre partidas
			$pdf->SetY($currY+15);

			$currY = $pdf->GetY();
			$pageHeight = $pdf->GetPageHeight();
			$availableSpace = $pageHeight - $currY+20;

			//var_dump($availableSpace);
			// if($availableSpace < 50){
				
			// 	$pdf->AddPage('P');
			// 	$currY = $pdf->GetY();

			// 	$pdf->Ln(10);
			// 	$y = $pdf->GetY();
			// 	$pdf->SetX(9);
			// 	$pdf->SetFont('Poppins','B',10);
			// 	$pdf->SetDrawColor(199, 200, 202);
			// 	$pdf->SetFillColor(0, 70, 118);
			// 	$pdf->RoundedRect(15.5, $y-0.4, 185, 9, 4.5, '1234', 'DF');

			// 	// labels
			// 	$pdf->SetTextColor(255, 255, 255);
			// 	$pdf->Cell(0, 9, utf8_decode('DESCRIPCIÓN'), 0, 0, 'C');
			// 	$pdf->SetTextColor(0, 0, 0);

			// 	$pdf->Ln(12);
			// }
		}
		
		//Damos espacio
		$pdf->Ln(10);

		// SubTotal cuadro decorativo
		$currY = $pdf->GetY();
		$pdf->SetY($currY+1);
		$pdf->SetDrawColor(199, 200, 202);
		$pdf->SetFillColor(0, 70, 118);
		$pdf->RoundedRect(118, $currY-2, 81, 25, 5, '1234', 'DF');

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
		
		//Estilos para la firma del cliente
		$pdf->SetFont('Poppins','',8);
		$pdf->SetTextColor(0, 0, 0);

		//Cuadro decorativo de firma
		$pdf->SetDrawColor(232, 243, 247);
		$pdf->SetFillColor(232, 243, 247);
		$pdf->RoundedRect(23, $currY-2, 81, 25, 5, '1234', 'DF');

		//Linea separadora para firmas
		$pdf->SetDrawColor(255, 255, 255);
		$pdf->SetFillColor(255, 255, 255);
		$pdf->Rect(61, $currY-1, 1, 28, 'DF');

		// firma del cliente
		$pdf->SetX(29);
		$pdf->Cell(217, 2, utf8_decode('Firma del cliente'), 0, 0, 'L');

		$pdf->SetX(67);
		$pdf->Cell(217, 2, utf8_decode('Firma del ejecutivo'), 0, 0, 'L');
		//var_dump($pdf->GetY());die;
		
		//Insertamos nueva hoja de terminos y condiciones
		$pdf->AddPage('P');
		$currY = $pdf->GetY();

		$pdf->SetY(28);
		$pdf->SetX(173);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Poppins','B',12);
		$pdf->Cell(20, 5, $cotizacion, $border, 1, 'L');

		$pdf->SetY($currY);

		$pdf->SetY(50);
		$pdf->SetX(43);
		$pdf->Cell(135, 5, $row['strFecha'], $border, 0, 'L');
		$pdf->SetY($currY);
		$pdf->SetX($currX);

		$pdf->SetY(57);
		$pdf->SetX(50);
		$pdf->Cell(125, 5, utf8_decode($row['strUsuarioAlta']), $border, 1, 'L');
		$pdf->SetY($currY);
		$pdf->SetX($currX);

		$pdf->SetY(65);
		$pdf->SetX(44);
		$pdf->Cell(125, 5, utf8_decode($row['strCliente']), $border, 1, 'L');
		$pdf->SetY($currY);
		$pdf->SetX($currX);

		// Barra decorativa de terminos y condicione
		$pdf->Ln(10);
		$y = $pdf->GetY();
		$pdf->SetX(9);
		$pdf->SetFont('Poppins','B',10);
		$pdf->SetDrawColor(199, 200, 202);
		$pdf->SetFillColor(0, 70, 118);
		$pdf->RoundedRect(55.5, $y+6, 105, 9, 4.5, '1234', 'DF');

		// label Terminos y condiciones
		$y = $pdf->GetY();
		$pdf->SetTextColor(255, 255, 255);
		$pdf->SetY($y+6);
		$pdf->Cell(192, 9, utf8_decode('TÉRMINOS Y CONDICIONES'), 0, 0, 'C');

		$pdf->SetFont('Poppins','',11);
		$pdf->SetTextColor(0, 0, 0);

		$pdf->Ln(14);

		$pdf->Cell(16, 6, chr(149), 0, 0, 'R');
		$pdf->MultiCell(168, 6, utf8_decode('Se requiere una orden de trabajo formal por parte del cliente para la programación del trabajo.'), 0, 'L', 0);
		
		$pdf->Ln(3);

		$pdf->Cell(16, 6, chr(149), 0, 0, 'R');
		$pdf->MultiCell(168, 6, utf8_decode('El Cliente tendrá derecho a solicitar máximo de 3 cambios a la propuesta de diseño inicial. Las cuales deben ser solicitadas por escrito y la agencia las analizará para determinar su viabilidad y su impacto en el cronograma y presupuesto del proyecto.'), 0, 'L', false);

		$pdf->Ln(3);

		$pdf->Cell(16, 6, chr(149), 0, 0, 'R');
		$pdf->MultiCell(168, 6, utf8_decode("Para la entrega o programación se establece un 50% de anticipo y 50% contra entrega."), 0, 'L', 0);

		$pdf->Ln(3);

		$pdf->Cell(16, 6, chr(149), 0, 0, 'R');
		$pdf->MultiCell(168,6, utf8_decode("Se evaluará el crédito por proyecto y cliente."),0,'L',0);

		$pdf->Ln(3);

		$pdf->Cell(16, 6, chr(149), 0, 0, 'R');
		$pdf->MultiCell(168,6, utf8_decode("Una vez aprobada la orden de trabajo y/o de compra se inicia el plazo de entrega según el proyecto."),0,'L',0);
		
		$pdf->Ln(3);

		$pdf->Cell(16, 6, chr(149), 0, 0, 'R');
		$pdf->MultiCell(168,6, utf8_decode("En caso de rescisión por parte del cliente, la empresa no realiza ningún reembolso del pago realizado."),0,'L',0);
		
		$pdf->Ln(3);

		$pdf->Cell(16, 6, chr(149), 0, 0, 'R');
		$pdf->MultiCell(168,6, utf8_decode("Esta cotización tiene una validez que se especifica de acuerdo a la fecha en la que se emitió, se cotizó y se envió."),0,'L',0);

		$pdf->Ln(3);

		$pdf->Cell(16, 6, chr(149), 0, 0, 'R');
		$pdf->MultiCell(168,6, utf8_decode("Precio en MXN."),0,'L',0);

		$pdf->Ln(3);

		$pdf->Cell(16, 6, chr(149), 0, 0, 'R');
		$pdf->MultiCell(168,6, utf8_decode("Cotización sólo incluye suministro de materiales e impresión."),0,'L',0);

		$pdf->Ln(3);

		$pdf->Cell(16, 6, chr(149), 0, 0, 'R');
		$pdf->MultiCell(168,6, utf8_decode("Montaje en sitio."),0,'L',0);

		$pdf->Ln(3);

		$pdf->Cell(16, 6, chr(149), 0, 0, 'R');
		$pdf->MultiCell(168,6, utf8_decode("No incluye estructuras."),0,'L',0);

		//Cuadro decorativo de firma
		$pdf->SetDrawColor(232, 243, 247);
		$pdf->SetFillColor(232, 243, 247);
		$pdf->RoundedRect(67.5, 240, 81, 25, 5, '1234', 'DF');

		//Linea separadora para firmas
		$pdf->SetDrawColor(255, 255, 255);
		$pdf->SetFillColor(255, 255, 255);
		$pdf->Rect(108, 240, 1, 28, 'DF');

		// firma del cliente
		$pdf->SetY(260);
		$pdf->SetX(76);
		$pdf->SetFont('Poppins','',8);
		$pdf->Cell(217, 2, utf8_decode('Firma del cliente'), 0, 0, 'L');

		$pdf->SetX(114);
		$pdf->Cell(217, 2, utf8_decode('Firma del ejecutivo'), 0, 0, 'L');

		//Terminos y condiciones izquierda
		// $pdf->SetFont('Poppins','',7);

		// $pdf->SetY(265);
		// $pdf->SetX(175);
		// $pdf->Cell(100, 6, utf8_decode('Clave: IV-NUXC01 Rev: 01'), 0, 1, 'L');
		
		// incrementamos contador
		$countQuotes ++;
		
	}
	
	// checamos si es mas de una
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
