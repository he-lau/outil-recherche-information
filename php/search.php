<?php
require_once "functions_db.php";

connect_db();

?>

<?php

// 1 - recupere la chaine de caractere
// 2 - transformer en liste de mots clé
// 3 - questionner la bdd pour recuperer pour chaque mot la liste des documents
  // 3.1 - recuperer l'id des mots SI EXISTANT
  // 3.2 - recuperer l'id des document et la frequence des mots associés de la table INDEXATION
  // 3.3 - recuperer les informations du document via son id de la table DOCUMENT
// 4 - tableau 2D retourné par la bdd
// 5 - retourner une liste des docs dans l'ordre decroissant (frequence_mot) de l'ENSEMBLE des mots

?>

<?php

// fonction pour passer de l'étape 4- à l'étape 5-
// OUTPUT : liste avec ("chemin"=>"frequence_mot")
function sum_word_frequencies($infos_word) {
  $result = array();

  // pour chaque association
  foreach ($infos_word as $word_array) {
    // pour chaque attribut ("contenu","chemin","frequence_mot")
      foreach ($word_array as $info_array) {

          $chemin = $info_array['chemin'];
          $frequence = intval($info_array['frequence_mot']);

          // si le chemin du doc est déjà dans la liste
          if (array_key_exists($chemin, $result)) {
              // incremente la frequence
              $result[$chemin] += $frequence;
          } else {
            // init la frequence
              $result[$chemin] = $frequence;
          }

          //$result["documents_ids"][$info_array['id']] = ""; 

      }
  }
  // tri ordre decroissant
  arsort($result);
  return $result;
}



if (isset($_GET['query']) && !empty($_GET['query'])) {
  // XSS
  $query = htmlspecialchars($_GET['query']);
  
  $words =  explode(' ',$query);

  //var_dump($words);

  //echo "<br>";

  $words_id = array();

  // requête à la bdd pour avoir l'id des mots (NULL si pas dans la base)
  $words_id = array_map("get_id_mot",$words);

  foreach ($words as $word) {
    array_push($words_id,get_id_mot($word));
  }
  
  //var_dump($words_id);
  //echo "<br>";  

  // Récupère toute les associations de la bdd avec l'id du mot
  // cf "get_mot_infos" (functions_db.php)
  $infos_word = array();

  foreach ($words_id as $id) {
    //echo "<br>";  
    // si le mot existe sur la bdd
    if (!$id == null ) {
      //var_dump(get_mot_infos((int)$id))."<br>";
      //var_dump((int)$id);
      //echo "<br>";  
      array_push($infos_word,get_mot_infos((int)$id));
    }
  }  

  //var_dump($infos_word);

  //echo "<br>" ."<br>";



// TEST
/*
$infos_word = array(
  array(
      array(
          "contenu" => "mot1",
          "chemin" => "./doc1.txt",
          "frequence_mot" => "9"
      ),
      array(
          "contenu" => "mot1",
          "chemin" => "./doc2.txt",
          "frequence_mot" => "2"
      )
  ),
  array(
      array(
          "contenu" => "mot2",
          "chemin" => "./doc1.txt",
          "frequence_mot" => "3"
      ),
      array(
          "contenu" => "mot2",
          "chemin" => "./doc3.txt",
          "frequence_mot" => "5"
      ),
      array(
          "contenu" => "mot2",
          "chemin" => "./doc4.txt",
          "frequence_mot" => "1"
      )
  ),
  array(
      array(
          "contenu" => "mot3",
          "chemin" => "./doc2.txt",
          "frequence_mot" => "7"
      ),
      array(
          "contenu" => "mot3",
          "chemin" => "./doc5.txt",
          "frequence_mot" => "4"
      )
  )
);
*/



// on recupere la somme des frequences de tous les mots pour chaque doc
$res = sum_word_frequencies($infos_word);
// taille de la liste
$num_results = count($res);
// html
$results_html = '';

$_SESSION['res'] = $res;


// pour chaque doc, on affiche son nom.extension à l'aide de basename() (somme_frequence)
foreach ($res as $doc => $freq_total) {

  /**TODO : switch... case pour l'extension du fichier */

  //
  $info_fichier = pathinfo($doc);

  //
  $format = $info_fichier['extension'];

  var_dump($info_fichier);


  switch ($format) {
    // .txt
    case 'txt':
      //recuperer une description du doc
      $description = substr(file_get_contents(dirname(__FILE__, 2)."/".$doc),0,200);
      break;      
      
      
    case 'html':
    case 'htm':
      $htmlContent = file_get_contents(dirname(__FILE__, 2) . "/" . $doc);
    
      // Utilisez strip_tags pour supprimer toutes les balises HTML du contenu
      $plainText = strip_tags($htmlContent);
    
      // Supprimez les espaces et les sauts de ligne inutiles
      $plainText = preg_replace('/\s+/', ' ', $plainText);
    
      // Tronquez le texte à 200 caractères
      $description = substr(htmlspecialchars($plainText), 0, 200);
      break;    
  } 

  // concatenation du resultat courant  
  $results_html .= "<li><a data-document-id='"."42"."' href='./php/document_content.php?chemin=$doc'><h3>".basename($doc)."($freq_total)</h3><p class='doc-description'>$description...</p></a></li>";


}

echo "<pre>";

var_dump($infos_word);
var_dump($words_id);

echo "</pre>";

// informe l'utilisateur de la requete + nombre de resultat
echo "<h2 class='search-result-count'>Nombre de réponse(s) pour \"$query\" : $num_results</h2>";
// affiche la liste
echo "<ul class='search-result'>$results_html</ul>";

} // isset




?>
