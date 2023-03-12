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

function clean_array($array) {
    // minuscule
    $array = array_map('strtolower', $array);
    // espace
    $array = array_map('trim', $array);

    // virgule, point & point-virgule
    $array = array_map(function ($value) {
        return preg_replace('/[,;.]+\s*$/', '', $value);
    }, $array);

    // parentheses debut/fin
    $array = array_map(function ($value) {
        return preg_replace('/^[\(\)]+|[\(\)]+$/', '', $value);
    }, $array);

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
    console_log("$count stopwords supprimés",$path);    
    return $file;
}

function indexation($content,$path) {
    console_log("Indexation des mots",$path);

    $occurrences = array();

    foreach($content as $word) {
        if(isset($occurrences[$word])) {
            $occurrences[$word]++;
        } else {
            $occurrences[$word] = 1;
        }
    }
    
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



<?php
    // lecture des fichiers
    // TODO : parcours recursive 
    $cheminFichier = './docs/jdg.txt';
    $contenuFichier = read_file($cheminFichier);

    $stop_path = './stopwordsv2.txt';
    $stop_content = read_file($stop_path,"\n");

    // nettoyage
    $contenuFichier = clean_array($contenuFichier);
    $stop_content = clean_array($stop_content);
    
    // suppression des stopwords
    $contenuFichier = remove_stopwords($contenuFichier,$stop_content,$cheminFichier);

    //var_dump(implode("<br>", $contenuFichier));

    $indexation = indexation($contenuFichier,$cheminFichier);
    afficher_liste_html($indexation,$cheminFichier);
    



?>