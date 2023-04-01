<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>MyEngine</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>

    </style>
  </head>
  <body>
    <div class="wrapper">
      <nav class="menu">
        <ul>
          <li><a href="../view.php">Acceuil</a></li>
          <li><a href="../docs/reforme-retraite.txt" download>Télécharger</a></li>
          <li><a href="#" onclick="window.print(); return false;">Imprimer</a></li>
          <li><a href="#">+ aA</a></li>
          <li><a href="#">- aA</a></li>
        </ul>
      </nav>
      <main class="content">        
        <?php 

if(isset($_GET["chemin"])) {
    
    // chemin du fichier
    $chemin = htmlspecialchars($_GET["chemin"]);

    echo "<h1>$chemin</h1>";

    //echo $chemin;

    // contenu du fichier
    $contenu = file_get_contents($chemin);

    //var_dump($contenu);
    // nl2br pour garder les paragraphes
    echo nl2br($contenu);
}

?>
      </main>
    </div>




  </body>
</html>


<script>
// récupérer les liens "+ aA" et "- aA"
const augmenterTexte = document.querySelector('li:nth-child(4) a');
const diminuerTexte = document.querySelector('li:nth-child(5) a');

// récupérer l'élément .content
const contenu = document.querySelector('.content');

// initialiser la taille de police actuelle à 16px
let taillePolice = 16;

// ajouter un gestionnaire d'événement click à chaque lien
augmenterTexte.addEventListener('click', () => {
  taillePolice++;
  contenu.style.fontSize = `${taillePolice}px`;
});

diminuerTexte.addEventListener('click', () => {
  taillePolice--;
  contenu.style.fontSize = `${taillePolice}px`;
});


</script>