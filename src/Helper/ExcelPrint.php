<?php

namespace App\Helper;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class ExcelPrint
{
    private static $tableHead = [
        'font' =>
        [
            'color' => ['rgb' => 'FFFFFF'],
            'bold' => true,
            'size' => 13
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => '538ED5'
            ]
        ]
    ];
 



    public static function  print($fileName, $title, $header, $content)
    {


        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        // $sheet->setCellValue('A1', "Firoomsis Primary Hospital");
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->getFont()->setSize(20);
        $sheet->getRowDimension(1)->setRowHeight(45);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->getColumnDimension('K')->setAutoSize(true);
        $sheet->setTitle($title);
       
   
        $sheet->getRowDimension(3)->setRowHeight(25);
        $sheet->setCellValue('A3', $title);
        $sheet->getStyle('A3')->getFont()->setSize(12);
        $sheet->setCellValue('D3', " Date");
        $sheet->getStyle('D3')->getFont()->setSize(12);
        $sheet->fromArray($header, NULL, 'A5');
        $sheet->getStyle('A5:K5')->applyFromArray(self::$tableHead);
        $sheet->fromArray($content, NULL, 'A6');
        $sheet->getHeaderFooter()->setOddHeader('&C&BHeader of the Document');
        $sheet->getHeaderFooter()->setOddFooter('&LFooter of the Document&RPage &P of &N');
        $writer = new Xlsx($spreadsheet);
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($temp_file);
        return ExcelPrint::file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }
    /**
     * Returns a BinaryFileResponse object with original or customized file name and disposition header.
     *
     * @param \SplFileInfo|string $file File object or path to file to be sent as response
     */
    public static function file($file, string $fileName = null, string $disposition = ResponseHeaderBag::DISPOSITION_ATTACHMENT): BinaryFileResponse
    {
        $response = new BinaryFileResponse($file);
        $response->setContentDisposition($disposition, null === $fileName ? $response->getFile()->getFilename() : $fileName);

        return $response;
    }
}
