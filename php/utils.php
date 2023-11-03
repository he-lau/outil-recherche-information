<?php


// composer include
require_once dirname(__DIR__)."/vendor/autoload.php";


function get_pdf_text($path) {
    $parser = new \Smalot\PdfParser\Parser();
    $pdf = $parser->parseFile($path);

    return $pdf->getText();
}

function get_lemmatization_dict($path) {

    $contenu_csv = file_get_contents($path);

    $lignes = explode("\n", $contenu_csv);

    $lemmatization_dict = array();

    // 1ere ligne correspond aux labels
    for ($i = 1; $i < count($lignes); $i++) {
        $colonnes = explode(';', $lignes[$i]);
    
        if (count($colonnes) >= 2) {
            $ortho = $colonnes[0];
            $lemme = $colonnes[1];
    
            $lemmatization_dict[$ortho] = $lemme;
        }
    }
    
    return $lemmatization_dict;
}

function lemmatization($datas, $lemma_dict) {

    // 


    // Appliquer la lemmatisation
    foreach($datas as $i => $word) {
        // si le mot est en clé dans le dictionnaire de lemmatisation
        if (array_key_exists($word, $lemma_dict)) {
            // MAJ tableau de mots
            $datas[$i] = $lemma_dict[$word];
        }
    }

return $datas;

}
?>
