<?php

/**
 * 
 * TODOS: 
 * client --> requete recherche (liste mots clés)
 * serveur --> retourne liste des document ou le/les mot/mots apparaît-ssent
 *  todo : s'il y a une erreur (orthographe) dans la requete --> proposer une suggestion
 *  - lemmatisation sur la recherche 
 * 
 */

// liste de mots : https://www.freelang.com/dictionnaire/dic-francais.php

$dict_path = "liste_francais.txt";
//$input = "matématique";
$input = "solfeg";
$dict = file_get_contents($dict_path);

// Charger le dictionnaire en un tableau de mots
$dictionary = explode("\n", $dict);

// Initialiser un tableau pour les suggestions
$suggestions = array();

// Définir la distance maximale acceptable
$max_distance = 2;

// Parcourir le dictionnaire
foreach ($dictionary as $word) {
    // Calculer la distance d'édition entre le mot d'entrée et chaque mot du dictionnaire
    $distance = levenshtein($input, $word);

    // Si la distance est inférieure ou égale à la distance maximale acceptable, ajouter le mot aux suggestions
    if ($distance <= $max_distance) {
        $suggestions[] = array('word' => $word, 'distance' => $distance);
    }
}

// Trier les suggestions par distance d'édition (ordre croissant)
usort($suggestions, function ($a, $b) {
    return $a['distance'] - $b['distance'];
});

// Limiter les suggestions aux trois premières
$suggestions = array_slice($suggestions, 0, 3);

// Afficher les trois suggestions les plus ressemblantes
echo "Les trois mots les plus ressemblants pour '$input':\n";
foreach ($suggestions as $suggestion) {
    echo $suggestion['word'] . " (Distance: " . $suggestion['distance'] . ")\n";
}
