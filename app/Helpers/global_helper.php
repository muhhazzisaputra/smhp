<?php

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Reader\Html;

/**
 * Write PhpSpreadsheet output to browser and return string content
 *
 * @param Xlsx $writer
 * @return string
 */

function export_excel_response(Spreadsheet $spreadsheet, string $filename = 'Export.xlsx'): \CodeIgniter\HTTP\ResponseInterface {
    $writer = new Xlsx($spreadsheet);

    // Optional: Save to disk for debugging
    // $writer->save(WRITEPATH . 'exports/test.xlsx');

    // Clean any existing output buffer
    if (ob_get_length()) {
        ob_end_clean();
    }

    // Capture Excel output to string
    ob_start();
    $writer->save('php://output');
    $excelOutput = ob_get_clean();

    // Build and return response
    return service('response')
        ->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
        ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
        ->setHeader('Cache-Control', 'max-age=0')
        ->setBody($excelOutput);
}

function write_excel_to_output(Xlsx $writer): string {
    ob_start();
    $writer->save('php://output');
    return ob_get_clean();
}
