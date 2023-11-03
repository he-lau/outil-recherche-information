

<?php

require_once("debug_console.php");

/*
  Connection à la bd
*/
function connect_db() {
  global $db;

$server = "localhost;port=3306;dbname=m1_outil_recherche";
$username = "root";
$password = "";

try {
  $db = new PDO("mysql:host=$server", $username, $password);
  // set the PDO error mode to exception
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //debug_to_console("Connection a la db réussi.");

} catch(PDOException $e) {
  debug_to_console("Connection failed:"  . $e->getMessage());
}
}


/*
  Ajout d'un document à la base
*/
function insert_document($nom, $chemin, $extension, $taille) {
  global $db;

  // Check if document already exists based on chemin
  $stmt = $db->prepare("SELECT id FROM DOCUMENT WHERE chemin = :chemin");
  $stmt->execute(array("chemin" => $chemin));
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$row) {
    // Document does not exist, insert a new row
    $insert_stmt = $db->prepare("INSERT INTO DOCUMENT (nom, chemin, extension, taille) VALUES (:nom, :chemin, :extension, :taille)");
    try {
      $insert_stmt->execute(array(
        "nom" => $nom,
        "chemin" => $chemin,
        "extension" => $extension,
        "taille" => $taille
      ));
      debug_to_console("Injection à la table DOCUMENT réussi.");
    } catch (PDOException $e) {
      debug_to_console("Insert failed: " . $e->getMessage());
    }
  } else {
    //debug_to_console("[INFO] DOCUMENT existant, insertion annulée.");
  }
}


// Ajout d'un mot à la db
function insert_mot($contenu) {
  global $db;

  // Check if mot already exists based on contenu
  $stmt = $db->prepare("SELECT id FROM MOT WHERE contenu = :contenu");
  $stmt->execute(array("contenu" => $contenu));
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$row) {
    // Mot does not exist, insert a new row
    $insert_stmt = $db->prepare("INSERT INTO MOT (contenu) VALUES (:contenu)");
    try {
      $insert_stmt->execute(array(
        "contenu" => $contenu
      ));
      debug_to_console("Injection à la table MOT réussi.");
    } catch (PDOException $e) {
      debug_to_console("Insert failed: " . $e->getMessage());
    }
  } else {
    //debug_to_console("[INFO] MOT existant, insertion annulée.");
  }
}



function insert_indexation($document_id, $mot_id, $frequence_mot) {
  global $db;

  // Check if the indexation already exists for the given document and word
  $stmt = $db->prepare("SELECT id FROM INDEXATION WHERE document = :document_id AND mot = :mot_id");
  $stmt->execute(array("document_id" => $document_id, "mot_id" => $mot_id));
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$row) {
    // Indexation does not exist, insert a new row
    $insert_stmt = $db->prepare("INSERT INTO INDEXATION (document, mot, frequence_mot) VALUES (:document_id, :mot_id, :frequence_mot)");
    try {
      $insert_stmt->execute(array(
        "document_id" => $document_id,
        "mot_id" => $mot_id,
        "frequence_mot" => $frequence_mot
      ));
      debug_to_console("Injection à la table INDEXATION réussie.");
    } catch (PDOException $e) {
      debug_to_console("Insert failed: " . $e->getMessage());
    }
  } else {
    //debug_to_console("[INFO] INDEXATION existant, insertion annulée.");
  }
}

function get_document_id($chemin) {
  global $db;

  // préparer la requête de recherche
  $stmt = $db->prepare("SELECT id FROM DOCUMENT WHERE chemin = :chemin");

  // exécuter la requête avec le paramètre chemin
  $stmt->execute(array("chemin" => $chemin));

  // récupérer la première ligne du résultat
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  // si le document existe, retourner son ID
  if ($row) {
    return $row["id"];
  }

  // sinon, retourner null
  return null;
}

function get_id_mot($contenu) {
  global $db;
  
  // Recherche le mot dans la table MOT
  //$stmt = $db->prepare("SELECT id FROM MOT WHERE contenu LIKE :contenu");
  $stmt = $db->prepare("SELECT id FROM MOT WHERE contenu = :contenu");
  //$stmt->execute(array("contenu" => "%".$contenu."%"));
  $stmt->execute(array("contenu" => $contenu));
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($row) {
    // Mot trouvé, renvoie son ID
    return $row['id'];
  } else {
    // Mot non trouvé
    return null;
  }
}

function get_id_like_mot($contenu) {
  global $db;
  
  // Recherche le mot dans la table MOT
  $stmt = $db->prepare("SELECT id FROM MOT WHERE contenu LIKE :contenu");
  //$stmt = $db->prepare("SELECT id FROM MOT WHERE contenu = :contenu");
  $stmt->execute(array("contenu" => "%".$contenu."%"));
  //$stmt->execute(array("contenu" => $contenu));
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($row) {
    // Mot trouvé, renvoie son ID
    return $row['id'];
  } else {
    // Mot non trouvé
    return null;
  }
}


// PREMIERE VERSION avec select all

/*
function get_mot_infos($mot_id) {
  global $db;

  $stmt = $db->prepare("SELECT * FROM INDEXATION WHERE mot = :mot_id");

  $stmt->execute(array("mot_id" => $mot_id));

  // Récupérer tous les résultats de la requête
  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

  return $results;
}
*/


// retourne les infos pour un mot
function get_mot_infos($mot_id) {
  global $db;

  // on recupere le chemin du document , le contenu du mot et la frequence ($mot_id)
  // JOINTURE pour recuperer les données des documents et des mots avec leurs id
  $stmt = $db->prepare("SELECT DOCUMENT.id, DOCUMENT.chemin, MOT.contenu, INDEXATION.frequence_mot  FROM INDEXATION 
                        JOIN MOT ON INDEXATION.mot = MOT.id 
                        JOIN DOCUMENT ON INDEXATION.document = DOCUMENT.id 
                        WHERE INDEXATION.mot = :mot_id");

  $stmt->execute(array("mot_id" => $mot_id));

  // retourne sous forme de tableau 2D
  /*

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
  );
  */
  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

  return $results;
}




?>
