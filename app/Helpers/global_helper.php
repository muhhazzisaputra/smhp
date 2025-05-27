<?php

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Write PhpSpreadsheet output to browser and return string content
 *
 * @param Xlsx $writer
 * @return string
 */
function write_excel_to_output(Xlsx $writer): string {
    ob_start();
    $writer->save('php://output');
    return ob_get_clean();
}
