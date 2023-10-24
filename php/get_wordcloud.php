<?php 

require_once "functions_db.php";

connect_db();

//global $db;

$response = array();


if(
    isset($_POST['file_path']) && 
    !empty($_POST['file_path'])
) {

    // path format
    //$path = str_replace("\\", "/", $chemin)



    // TODO : liste des mots avec occurence
    $select_words_in_doc = $db->prepare(

        "SELECT word.contenu, ind.frequence_mot
        FROM DOCUMENT AS doc 
        LEFT JOIN INDEXATION AS ind
        ON doc.id=ind.document 
        LEFT JOIN MOT AS word
        ON word.id=ind.mot
        
        WHERE doc.chemin = :chemin
        ORDER BY ind.frequence_mot DESC
        LIMIT 50"
    );



    $select_words_in_doc->execute(array(
        ":chemin"=>htmlspecialchars($_POST['file_path'])
    )
    ); 

    $results = $select_words_in_doc->fetchAll(PDO::FETCH_ASSOC);


    $response['success'] = true;
    $response['res'] = $results;


    echo json_encode($response);



}

?>