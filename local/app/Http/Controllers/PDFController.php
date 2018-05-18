<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use App\func as f;

class PDFController extends Controller
{
    //
    //
    protected $date;
    protected $headerTxt='';
    
        public function index(){
          return view('pdf.index');
        }

        public function setHeaderContent($text){
            $this->headerTxt = $text;
        }
        public function testPdf1(){
          // set document information
    
          $this->date = date("l jS \of F Y h:i:s A");
    
          $hmtl ="<h1>You generated Pdf success!</h1>
          <h1>You generated Pdf success!</h1>
          <h1>You generated Pdf success!</h1>
          <h1>You generated Pdf success!</h1>
          <h1>You generated Pdf success!</h1>
          <h1>You generated Pdf success!</h1>
          <h1>You generated Pdf success!</h1>
          <h1>You generated Pdf success!</h1>
          <h1>You generated Pdf success!</h1>
          <h1>You generated Pdf success!</h1>
          <h1>You generated Pdf success!</h1>
          <h1>You generated Pdf success!</h1>
          <h1>You generated Pdf success!</h1>
          <h1>You generated Pdf success!</h1>
          <h1>You generated Pdf success!</h1>
          <h1>You generated Pdf success!</h1>
          <h1>You generated Pdf success!</h1>
          <h1>You generated Pdf success!</h1>
          <h1>You generated Pdf success!</h1>
          <h1>You generated Pdf success!</h1>
          <h1>You generated Pdf success!</h1>
          <h1>You generated Pdf success!</h1>
          <h1>You generated Pdf success!</h1>
          <h1>You generated Pdf success!</h1>
          <h1>You generated Pdf success!</h1>
          <p>fsdfsdfsddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddgfdgsgcagdfgadfgasdhfgdgfajdfgjadgfdajjjjjjjjjjjjjjjjjjjjjjjjjjjjjjewwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww</p>
          ใช้ภาษไทยได้ไหม";
          PDF::setHeaderCallback(function ($pdf) {
            $pdf->SetMargins(10, 22, 6, true);
            $pdf->SetY(2);
            $pdf->SetFont('helvetica', 'I', 8);
            $pdf->Cell(0, 16, '<< TCPDF Example 003 >>', 0, false, 'L', 0, '', 0, false, 'M', 'M');
            $pdf->Cell(0, 16, '<< TCPDF Example 003 >>', 0, false, 'R', 0, '', 0, false, 'M', 'M');
    
          });
          PDF::setFooterCallback(function($pdf){
            // Position at 15 mm from bottom
           $pdf->SetY(-15);
           // Set font
           $pdf->SetFont('helvetica', 'I', 8);
           // Page number
           $pdf->Cell(0, 10, 'Page '.$pdf->getAliasNumPage().'/'.$pdf->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
          });
          PDF::SetTitle("Example 1");
          PDF::SetMargins(20, 22, 20, true);
          PDF::setFontSubsetting(true);
          PDF::AddFont('thsarabun', '', 'thsarabun.php');
          PDF::SetFont('thsarabun', '', 14, '', true);
          PDF::AddPage();
          PDF::writeHTML($hmtl,true,false,true,false,'');
    
          PDF::Output("Example-1.pdf");
    
        }

        
        public function createPDF($html){
          // set document information
    
          $this->date = f::dateThaiFull(date("Y-m-d"));
    
          PDF::setHeaderCallback(function ($pdf) {
            $pdf->SetMargins(10, 22, 6, true);
            $pdf->SetY(2);
            $pdf->AddFont('thsarabun', '', 'thsarabun.php');
            $pdf->SetFont('thsarabun', 'I', 10);
            $pdf->Cell(0, 16,$this->headerTxt." วันที่ออกรายงาน : ".$this->date , 0, false, 'R', 0, '', 0, false, 'M', 'M');
    
          });
          
          PDF::SetTitle($this->headerTxt);
          
          PDF::AddFont('thsarabun', '', 'thsarabun.php');
          PDF::SetFont('thsarabun', '', 16, '', true);
          PDF::AddPage('P','A4',false,false);
          PDF::SetMargins(20, 22, 20,false);
          PDF::setFontSubsetting(true);
          PDF::writeHTML($html,true,false,true,false,'');


          PDF::setFooterCallback(function($pdf){
            // Position at 15 mm from bottom
           $pdf->SetY(-15);
           // Set font
           $pdf->AddFont('thsarabun', '', 'thsarabun.php');
           $pdf->SetFont('thsarabun', 'I', 10);
           // Page number
           $pdf->Cell(0, 10, 'หน้า '.$pdf->getAliasNumPage().'/'.$pdf->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
          });
    
          PDF::Output("Example-1.pdf");
    
        }
}
