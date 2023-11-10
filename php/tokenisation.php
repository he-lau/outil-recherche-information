

<?php

//header('Content-Type: text/html; charset=utf-8');
//mb_internal_encoding('UTF-8');
require_once 'utils.php';


/**
 * Deboguage en console
 * @param string $msg : descriptif du message
 * @param string $content : contenu du message
 */
function console_log($msg, $content='') {
    echo "<script>console.log('[INFO] $msg : $content')</script>";
}


/**
 * Lecture de fichier & convertir sous la forme d'un tableau
 * @param string $path : chemin vers le fichier
 * @param string $separator : le séparateur pour parser les mots
 * @return array : contenu du fichier sous forme d'un tableau
 */
function read_file($path, $separator=" ") {


    // TODO : condition 
    //
    $info_fichier = pathinfo($path);

    //
    $format = $info_fichier['extension'];

    switch ($format) {
        case 'txt':
        case 'html':
        case 'htm':
            console_log("Lecture du fichier",$path);
            $contenuFichier = file_get_contents($path);               
            break;
        case 'pdf':  
            console_log("Lecture du fichier",$path);
            $contenuFichier = get_pdf_text($path);
            /*
            echo '<pre>';
            var_dump(explode($separator,$contenuFichier));
            echo '</pre>';
            */
            break;
        case 'docx':
            console_log("Lecture du fichier",$path);
            $contenuFichier = get_docx_text($path);
            break;
    }    



    console_log("Longueur du fichier",strlen($contenuFichier));
    //echo strlen($contenuFichier);
    return explode($separator,$contenuFichier);
}


/**
 * Nettoyage du contenu du fichier
 * @param array $array : tableau representant le contenu du ficheir
 * @return array : tableau nettoyé
 */

 /*
function clean_array($array,$path) {
    console_log("Nettoyage des mots",$path);
    // minuscule
    $array = array_map('mb_strtolower', $array);

    // suppression des espaces
    $array = array_map('trim', $array);

    // uniformiser les apostrophes pour le fr
    $array = str_replace('’', "'", $array);

    // garder que les mots avec minimum 2 char
    $array = preg_grep('/^[a-zA-Z]{2,}$/', $array);

    // Supprimer les caractères avant une apostrophe (',’) exemple : "l'éléphant" --> "éléphant"
    $array = preg_replace('/\w*\'(\w+)/', '$1', $array);        

    $array = preg_replace('/[\p{P}]/u', '', $array);
    
    // garder les mots et les chiffres
    //$array = preg_grep('/^[a-z0-9]{3,}$/', $array);    
    $array = preg_grep('/^[\p{Ll}\p{Nd}]{3,}$/u', $array);


    return $array;
}
*/



function clean_array($array) {

    $cleaned_array = [];

    foreach ($array as $word) {
        // Minuscules
        $word = mb_strtolower($word);

        // Suppression des espaces
        $word = trim($word);

        // Uniformiser les apostrophes pour le français
        $word = str_replace('’', "'", $word);

        // Garder que les mots avec minimum 3 caractères
        //if (preg_match('/^[a-zA-Z]{3,}$/', $word)) {
        if (preg_match('/^[\p{L}]{3,}$/u', $word)) {

            // Supprimer les caractères avant une apostrophe (',’) exemple : "l'éléphant" --> "éléphant"
            $word = preg_replace('/[a-zA-Z]*\'([a-zA-Z]+)/', '$1', $word);

            // Supprimer les caractères de ponctuation
            $word = preg_replace('/[\p{P}]/u', '', $word);
            
            $cleaned_array[] = $word;

        }
    }

    return $cleaned_array;
}




/**
 * Suppression des "stopwords"
 * @param array $file : ensemble des mots du fichier 
 * @param array $stopwords : ensemble des stopwords
 * @return $file : contenu nettoyé
 * 
 */
function remove_stopwords($file, $stopwords) {
    $count = 0;

    // parcours de l'ensemble des mots
    foreach ($file as $key => $word) {
        // si le mot est présent dans les stopwords, le supprimer de la liste
        if (in_array($word, $stopwords)) {
            unset($file[$key]);
            $count++;
        }
    }

    console_log("$count stopwords supprimés");
    return $file;
}


/**
 * Tokenisation en incrementant les mots qui se répètes
 * @param array $content : contenu du fichier
 * @param string $path : chemin du fichier
 * @return array $occurences : contenus avec occurence de chaque mot
 * 
 */

function tokenisation($content) {
    $occurrences = array();

    foreach($content as $word) {
        // si l'occurence est deja dans le tableau on l'incremente
        if(isset($occurrences[$word])) {
            $occurrences[$word]++;
        } else {
            // init à 1
            $occurrences[$word] = 1;
        }
    }

    return $occurrences;
}



/**
 * Affichage contenus sous forme de liste
 * @param array $occurences 
 * @param string $path
 */

function afficher_liste_html($occurrences,$path) {
    // Tri du tableau par ordre décroissant de la valeur (nombre d'apparitions)
    arsort($occurrences);

    // Affichage de la liste à puces HTML
    echo "<h2>$path</h2>";
    echo "<ul>";
    foreach ($occurrences as $word => $count) {
        echo "<li>$word : $count</li>";
    }
    echo "</ul>";
}



?>