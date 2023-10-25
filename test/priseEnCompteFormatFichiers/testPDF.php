<?php
 
// Include Composer autoloader if not already done.
require_once dirname(dirname(__DIR__))."/vendor/autoload.php";
 
// Parse pdf file and build necessary objects.
$parser = new \Smalot\PdfParser\Parser();
$pdf = $parser->parseFile(__DIR__.'/test.pdf');
 
$text = $pdf->getText();

//echo $text;
 
?>

<?php 

/*
$pdfFilePath = __DIR__.'/test.pdf';
$htmlOutputPath = __DIR__.'/test_res.html';

// Use the 'exec' function to run pdftohtml
$command = "pdftohtml -s $pdfFilePath $htmlOutputPath";
exec($command);

// Check if the conversion was successful
if (file_exists($htmlOutputPath)) {
    echo "PDF converted to HTML successfully.";
} else {
    echo "Conversion failed.";
}
*/


?>