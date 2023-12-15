<?php


// composer include
require_once dirname(__DIR__) . "/vendor/autoload.php";


function get_pdf_text($path)
{
    $parser = new \Smalot\PdfParser\Parser();
    $pdf = $parser->parseFile($path);

    $pdf_text = $pdf->getText();

    $chaine_sans_espaces_en_trop = preg_replace('/\s+/', ' ', $pdf_text);


    return $chaine_sans_espaces_en_trop;
}

function get_lemmatization_dict($path)
{

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

function lemmatization($datas, $lemma_dict)
{

    // 


    // Appliquer la lemmatisation
    foreach ($datas as $i => $word) {
        // si le mot est en clé dans le dictionnaire de lemmatisation
        if (array_key_exists($word, $lemma_dict)) {
            // MAJ tableau de mots
            $datas[$i] = $lemma_dict[$word];
        }
    }

    return $datas;
}

// read .docx
function get_docx_text($path)
{

    // contenu du fichier .docx
    $fullText = '';

    // instance phpWord
    $phpWord = \PhpOffice\PhpWord\IOFactory::load($path);

    // Parcourir l'ensemble des Sections
    foreach ($phpWord->getSections() as $section) {
        // Parcourir l'ensemble des elements de la section courante
        foreach ($section->getElements() as $element) {
            // Si l'element est de type TextRun (text+style)
            if ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
                // On récupère uniquement le texte 
                foreach ($element->getElements() as $textElement) {
                    if ($textElement instanceof \PhpOffice\PhpWord\Element\Text) {
                        // concaténation
                        $fullText .= $textElement->getText();
                    }
                }

                // Si un Text n'est pas inclus dans une Section     
            } elseif ($element instanceof \PhpOffice\PhpWord\Element\Text) {
                $fullText .= $element->getText();
            }
            // Si l'element est un Title
            elseif ($element instanceof \PhpOffice\PhpWord\Element\Title) {

                if (!$element->getText() instanceof \PhpOffice\PhpWord\Element\TextRun) {
                    $fullText .= $element->getText();
                }
            }
        }
    }
    return $fullText;
}

/**
 *  Suggestion de mots 
 *  // liste de mots : https://www.freelang.com/dictionnaire/dic-francais.php

 */

// recuperer le dictionnaire de mots 
function get_suggestion_dict($path)
{

    $content = file_get_contents($path);

    // Charger le dictionnaire en un tableau de mots
    $dict = explode("\n", $content);

    return $dict;
}
// obtenir la liste des suggestions pour un mot donné
function get_suggestions($dictionary, $input, $max_distance)
{
    $suggestions = array();

    // Parcourir le dictionnaire
    foreach ($dictionary as $word) {
        // Supprimer les espaces blancs au début et à la fin du mot
        $word = trim($word);

        // Calculer la distance d'édition entre le mot d'entrée et chaque mot du dictionnaire
        $distance = levenshtein($input, $word);

        // Vérifier si la distance est inférieure ou égale à la distance maximale acceptable
        // Et si le mot n'est pas identique à l'entrée, alors ajouter le mot aux suggestions
        if ($distance <= $max_distance && strtolower($input) !== strtolower($word)) {
            $suggestions[] = array('word' => $word, 'distance' => $distance);
        }
    }

    // Trier les suggestions par distance d'édition (ordre croissant)
    usort($suggestions, function ($a, $b) {
        return $a['distance'] - $b['distance'];
    });

    return $suggestions;
}
