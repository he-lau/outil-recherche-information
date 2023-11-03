<?php
require_once dirname(dirname(__DIR__))."/vendor/autoload.php";

$docFilePath = './file-sample_100kB.docx'; // Replace with the path to your .docx file
//$docFilePath = './COURRIER A DESTINATION DES ETUDIANTS PARIS 8.docx'; // Replace with the path to your .docx file

$phpWord = \PhpOffice\PhpWord\IOFactory::load($docFilePath);


// Extract text from text elements and text runs
$fullText = '';
foreach ($phpWord->getSections() as $section) {
    foreach ($section->getElements() as $element) {
        if ($element instanceof \PhpOffice\PhpWord\Element\Text) {
            $fullText .= $element->getText();
        } elseif ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
            foreach ($element->getElements() as $textElement) {
                if ($textElement instanceof \PhpOffice\PhpWord\Element\Text) {
                    $fullText .= $textElement->getText();
                }
            }
        }
    }
}

// Output the extracted text
//echo $fullText;



// Extraction du texte et des titres à partir des éléments du document
$fullText = '';

$titles = "";

foreach ($phpWord->getSections() as $section) {
    foreach ($section->getElements() as $element) {
       if ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
            $textRunText = '';
            foreach ($element->getElements() as $textElement) {
                if ($textElement instanceof \PhpOffice\PhpWord\Element\Text) {
                    $textRunText .= $textElement->getText();
                }
            }
            $fullText .= $textRunText;
        } elseif ($element instanceof \PhpOffice\PhpWord\Element\Text) {
            $fullText .= $element->getText();
        }
    }
}


foreach ($phpWord->getSections() as $section) {
    foreach ($section->getElements() as $element) {
        if ($element instanceof \PhpOffice\PhpWord\Element\Title) {

            if (! $element->getText() instanceof \PhpOffice\PhpWord\Element\TextRun) {
                echo "42";
                $fullText .= $element->getText();
                $titles .= $element->getText();
            }

        }
    }
}




// Affichage des titres
echo "<h1>Titres :</h1><br>$titles";



// Affichage du texte extrait
echo "<h1>Texte :</h1><br>$fullText";

