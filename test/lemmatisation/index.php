<?php 

// 1 - lire le fichier csv avec le lexique (recuperer seulement les 2 première col)
// 2 - parcourrir l'ensemble 

$fake_content = array(
    "manger","mangeais","mange","mangerai","nagé","étoiles","lunaire"
);


?>


<?php
// Nom du fichier CSV
$nom_fichier_csv = 'encoded-Lexique380 (1).csv';

// Lire le contenu du fichier CSV avec l'encodage UTF-8
//$contenuCSV = file_get_contents($nom_fichier_csv);

$contenuCSV = file_get_contents($nom_fichier_csv);

// Diviser le contenu en lignes
$lignes = explode("\n", $contenuCSV);

// 
$lemmatization_dict = array();

for ($i = 1; $i < count($lignes); $i++) {
    $colonnes = explode(';', $lignes[$i]);

    if (count($colonnes) >= 2) {
        $ortho = $colonnes[0];
        $lemme = $colonnes[1];

        $lemmatization_dict[$ortho] = $lemme;
    }
}

// Display the resulting associative array
//echo "ortho : avait, lemme : ".$lemmatization_dict['avait'];

/*
foreach($lemmatization_dict as $ortho => $lemme) {
    echo "ortho : ".$ortho.", lemme : ".$lemme."<br>";
}
*/


echo "<h1>AVANT LEMMATISATION : </h1><br>";
var_dump($fake_content);
echo "<br>";


// Appliquer la lemmatisation
foreach($fake_content as $i => $word) {
    // si le mot est en clé dans le dictionnaire de lemmatisation
    if (array_key_exists($word, $lemmatization_dict)) {
        // MAJ tableau de mots
        $fake_content[$i] = $lemmatization_dict[$word];
    }
}

echo "<h1>APRES LEMMATISATION : </h1><br>";
var_dump($fake_content);
echo "<br>";

echo "<h1>APRES NETTOYAGE DOUBLONS : </h1><br>";
var_dump(array_unique($fake_content));
echo "<br>";

echo "<h1>DICTIONNAIRE DE LEMMATISATION : </h1><br>";
foreach($lemmatization_dict as $ortho => $lemme) {
    echo "ortho : ".$ortho.", lemme : ".$lemme."<br>";
}
echo "<br>";

?>
