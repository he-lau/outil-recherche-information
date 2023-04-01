<?php
    require_once "php/tokenisation.php";

?>

<?php
    require_once "php/functions_db.php";

    connect_db();
?>


<?php
    // lecture des fichiers
    function indexation($cheminFichier,$stop_path='./stopwords-fr.txt') {
        
        $contenuFichier = read_file($cheminFichier);
    
        $stop_content = read_file($stop_path,"\n");
    
        // nettoyage
        $contenuFichier = clean_array($contenuFichier,$cheminFichier);
        $stop_content = clean_array($stop_content,$stop_path);
    
        // suppression des stopwords
        $contenuFichier = remove_stopwords($contenuFichier,$stop_content,$cheminFichier);
    
        //var_dump(implode("<br>", $contenuFichier));
    
        $tokenisation = tokenisation($contenuFichier,$cheminFichier);
    
        // nom fichier + liste mots 
        //afficher_liste_html($tokenisation,$cheminFichier);
    
    
    
        // insertion DOCUMENT
    
        // Get file name
        $nomFichier = pathinfo($cheminFichier, PATHINFO_FILENAME);
    
        // Get file path
        $chemin = realpath($cheminFichier);
    
        // Get file extension
        $extension = pathinfo($cheminFichier, PATHINFO_EXTENSION);
    
        // Get file size
        $taille = filesize($cheminFichier);
    
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




