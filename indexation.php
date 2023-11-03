<?php
    require_once "php/tokenisation.php";

?>

<?php
    require_once "php/functions_db.php";

    connect_db();

    require_once "php/utils.php";

?>


<?php

    /**
     *  
     * 
     * 
     * 
     * 
     * 
     */

    // lecture des fichiers
    function indexation($cheminFichier,$stop_path,$lemma_path) {
        
        $contenuFichier = read_file($cheminFichier);
    
        $stop_content = read_file($stop_path,"\n");
    
        // nettoyage
        console_log("Nettoyage des mots", $cheminFichier);
        $contenuFichier = clean_array($contenuFichier);
        console_log("Nettoyage des mots", $stop_path);
        $stop_content = clean_array($stop_content);
    
        // suppression des stopwords
        console_log("Suppression des stopwords",$cheminFichier);
        $contenuFichier = remove_stopwords($contenuFichier,$stop_content);
        console_log("[FIN] Suppression des stopwords",$cheminFichier);
        
        // IMPORTANT :  TODO : lemmatisation 

        $lemma_content = get_lemmatization_dict($lemma_path);

        console_log("Lemmatisation...",$cheminFichier);
        $contenuFichier = lemmatization($contenuFichier,$lemma_content);
        console_log("[FIN] Lemmatisation...",$cheminFichier);

        var_dump($contenuFichier);


    
        //var_dump(implode("<br>", $contenuFichier));
        console_log("tokenisation des mots",$cheminFichier);
        $tokenisation = tokenisation($contenuFichier);
        console_log("[FIN] tokenisation des mots",$cheminFichier);
        // nom fichier + liste mots 
        //afficher_liste_html($tokenisation,$cheminFichier);
    
    
    
        // insertion DOCUMENT
    
        // Get file name
        $nomFichier = pathinfo($cheminFichier, PATHINFO_FILENAME);
    
        // Get file path
        //$chemin = realpath($cheminFichier);
        $chemin = $cheminFichier;
    
        // Get file extension
        $extension = pathinfo($cheminFichier, PATHINFO_EXTENSION);
    
        // Get file size
        $taille = filesize($cheminFichier);
        
        // requête serveur
        insert_document($nomFichier,$chemin,$extension,$taille);
    
    
        // insertion MOT & INDEXATION
    
        $id_document = get_document_id($chemin);
    
        foreach ($tokenisation as $contenu => $frequence_mot) {
            insert_mot($contenu);
            $id_mot = get_id_mot($contenu);
            insert_indexation($id_document,$id_mot,$frequence_mot);
    
            //debug_to_console(get_id_mot($contenu));
        }    
    }


?>

<?php 
//indexation('./docs/jdg.txt');
//indexation('./docs/pomme.txt');
//indexation('./docs/test.txt');
?>




