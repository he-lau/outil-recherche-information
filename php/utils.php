<?php


// composer include
require_once dirname(__DIR__)."/vendor/autoload.php";


function get_pdf_text($path) {
    $parser = new \Smalot\PdfParser\Parser();
    $pdf = $parser->parseFile($path);

    return $pdf->getText();
}
?>
