<?php

function console_log($msg, $content='') {
    echo "<script>console.log('[INFO] $msg : $content')</script>";
}

function read_file($path, $separator=" ") {
    console_log("Lecture du fichier",$path);
    $contenuFichier = file_get_contents($path);
    console_log("Longueur du fichier",strlen($contenuFichier));
    //echo strlen($contenuFichier);
    return explode($separator,$contenuFichier);
}

function clean_array($array,$path) {
    console_log("Nettoyage des mots",$path);
    // minuscule
    $array = array_map('strtolower', $array);
    // espace
    $array = array_map('trim', $array);
    // uniformiser les apostrophes pour le fr
    $array = str_replace('’', "'", $array);

    // garder que les mots
    //$array = preg_grep('/^[a-zA-Z]{2,}$/', $array);

    // Supprimer les caractères avant une apostrophe (',’)
    $array = preg_replace('/\w*\'(\w+)/', '$1', $array);        

    $array = preg_replace('/[\p{P}]/u', '', $array);

    // garder les mots et les chiffres
    $array = preg_grep('/^[a-z0-9]{3,}$/', $array);    

    return $array;
}


function remove_stopwords($file, $stopwords, $path) {
    console_log("Suppression des stopwords",$path);

    $count = 0;

    // parcours de l'ensemble des mots
    foreach ($file as $key => $word) {
        // si le mot est présent dans les stopwords, le supprimer de la liste
        if (in_array($word, $stopwords)) {
            unset($file[$key]);
            $count++;
        }
    }
    console_log("[FIN] Suppression des stopwords",$path);
    console_log("$count stopwords supprimés",$path);
    return $file;
}

function tokenisation($content,$path) {
    console_log("tokenisation des mots",$path);

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

    console_log("[FIN] tokenisation des mots",$path);

    return $occurrences;
}

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