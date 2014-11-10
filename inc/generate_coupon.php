<?php
require('fpdf/fpdf.php');

class PDF_Dash extends FPDF
{
    function SetDash($black=null, $white=null)
    {
        if($black!==null)
            $s=sprintf('[%.3F %.3F] 0 d',$black*$this->k,$white*$this->k);
        else
            $s='[] 0 d';
        $this->_out($s);
    }
	const DPI = 96;
    const MM_IN_INCH = 25.4;
    const A4_HEIGHT = 297;
    const A4_WIDTH = 210;
    // tweak these values (in pixels)
    const MAX_WIDTH = 800;
    const MAX_HEIGHT = 500;
 
    function pixelsToMM($val) {
        return $val * self::MM_IN_INCH / self::DPI;
    }
 
    function resizeToFit($imgFilename) {
        list($width, $height) = getimagesize($imgFilename);
 
        $widthScale = self::MAX_WIDTH / $width;
        $heightScale = self::MAX_HEIGHT / $height;
 
        $scale = min($widthScale, $heightScale);
 
        return array(
            round($this->pixelsToMM($scale * $width)),
            round($this->pixelsToMM($scale * $height))
        );
    }
 
    function centreImage($img) {
        list($width, $height) = $this->resizeToFit($img);
 
        // you will probably want to swap the width/height
        // around depending on the page's orientation
        $this->Image(
            $img, 10,
            10,
            $width,
            $height
        );
    }
}


//Generate the coupon PDF which will expire in 5 days. Returns the new filename.
function generate_coupon($img, $product, $upc, $hash, $retailers){
	$web = $_SERVER['HTTP_HOST'];
	// Create Filename
	$retailers = '/home/busaweb/public_html/coupon/img/retailers/' . $retailers;
	$input_path = '/home/busaweb/public_html/coupon/img/' . $img;
	$output_file = $product . '-' . $upc . '-' . $hash . '.pdf';
	$output_path = '/home/busaweb/public_html/coupon/pdf/' . $output_file;
	// Calculate current month and set expiration date
	$currentDate = date("F j, Y"); // current date
	$futuredate = strtotime(date("Y-m-d", strtotime($currentDate)) . " +5 days");
	$expdate = "Source: $web  | Downloaded on: $currentDate | Expiration date: " . date("F j, Y", $futuredate);
	$expired = "Expires: " . date("F j, Y", $futuredate);
	
	// Instanciation of inherited class
	//$pdf = new PDF();
	$pdf=new PDF_Dash();
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Helvetica','B',10);
	$pdf->Ln(20);
	$pdf->Image($input_path,10,10,0);
	$pdf->Ln(35);
	$pdf->Cell(65);
	//$pdf->SetTextColor(58,126,182);
	$pdf->SetTextColor(0,0,0);
	$pdf->Cell(0,0,$expired,0,0,'C');
	$pdf->Ln(58);
	$pdf->SetTextColor(153,153,153);
	$pdf->Cell(0,0,$expdate,0,0,'C');
	$pdf->Ln(20);
	$pdf->SetLineWidth(0.6);
	$pdf->SetDash(2,2); //restores no dash
	$pdf->Line(0,130,500,130);
	
	$pdf->Image($retailers);
	$pdf->Output($output_path, F);
	
	return 'http://www.boironusa.com/coupon/pdf/' . $output_file;
}
?>