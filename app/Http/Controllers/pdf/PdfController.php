<?php

namespace App\Http\Controllers\pdf;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

class PdfController extends Controller
{
    use PdfDetailsTrait;

    protected $headers = [
        "Content-type" => "application/pdf",
        "Pragma" => "no-cache",
        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
        "Expires" => "0"
    ];

    // Agenda PDF
    public function print_agenda (Request $request) 
    {
        $data = $this->pdf_details($request);
        $resource = $data;
        $company = $resource;

        $html = view('pdfs.print_agenda', compact('resource', 'company'));
        return $html;
        
        $mpdf = new Mpdf(config('pdf'));            
        $mpdf->WriteHTML($html);

        return $mpdf->Output('agenda.pdf', Destination::DOWNLOAD);
    }
}
