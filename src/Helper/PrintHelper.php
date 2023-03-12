<?php

namespace App\Helper;

use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PrintHelper
{
    const PAPER_A4 = "A4";
    const PAPER_A5 = "A5";
    const ORIENTATION_PORTRAIT = "portrait";
    const ORIENTATION_LAND = "landscape";

    private $templating;
    private $parameter;
    private $size = [
        "A4" => [
            "portrait" => ["width" => 800, "height" => 100],
            "landscape" => ["width" => 800, "height" => 50],
        ], 
        "A3" => [
            "portrait" => ["width" => 3000, "height" => 100],
            "landscape" => ["width" => 3000, "height" => 50],
        ],
        "A5" => [
            "portrait" => ["width" => 450, "height" => 40],
            "landscape" => ["width" => 650, "height" => 50],
        ],
    ];

    public function __construct(\Twig\Environment $templating, ParameterBagInterface $parameterBagInterface)
    {
        $this->templating = $templating;
        $this->parameter = $parameterBagInterface;
    }

    function print($twig, $data = null, $name = "print", $orientation = null, $paper = "A4", $header=true,$footer = true)
    {
        // UserHelper::setExecutionTime();
        
        $orientation = $orientation ? $orientation : "portrait";
        $orientation = strtolower($orientation);
        $paper = strtoupper($paper);
        $data["footer"] = $footer;
        $data["header"] = $header;
        $size = $this->size[$paper][$orientation];
        $data["size"] = $size;
        $html = $this->templating->render($twig, $data);
        $pdfOptions = new Options();
        // $pdfOptions->set('defaultFont', 'Courier');
        $pdfOptions->set('isRemoteEnabled', true);
        // dd($this->parameter->get('kernel.project_dir'));
        // $pdfOptions->set('fontDir', $this->parameter->get('kernel.project_dir') . "/fonts");
        $dompdf = new Dompdf($pdfOptions);
        $dompdf->loadHtml($html);
     
        $dompdf->setPaper($paper, $orientation);
        $dompdf->render();
        $dompdf->stream($name . ".pdf", [
            "Attachment" => false,
        ]);
    }

    function printData($data , $name = "print", $orientation = null, $paper = "A4")
    {
        
        $orientation = $orientation ? $orientation : "portrait";
        $orientation = strtolower($orientation);
        $paper = strtoupper($paper);
        $size = $this->size[$paper][$orientation];
        $data["size"] = $size;
 dd($this->parameter->get('kernel.project_dir'));
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Courier');
        $pdfOptions->set('isRemoteEnabled', true);
        $pdfOptions->set('fontDir', $this->parameter->get('kernel.project_dir') . "/fonts");
        $dompdf = new Dompdf($pdfOptions);
        $dompdf->loadHtml($data);
     
        $dompdf->setPaper($paper, $orientation);
        $dompdf->render();
        $dompdf->stream($name . ".pdf", [
            "Attachment" => false,
        ]);
    }
}
