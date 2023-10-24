<?php
// Path to the PDF file you want to extract text from
$pdfFilePath = '../docsinput.pdf';

// Execute the pdftotext command to extract text from the PDF
$output = array();
exec("pdftotext $pdfFilePath -", $output, $returnCode);

if ($returnCode === 0) {
    $pdfText = implode("\n", $output);
    echo $pdfText; // Output the extracted text
} else {
    echo 'PDF to text extraction failed.';
}
?>
