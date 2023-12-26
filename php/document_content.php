<?php
require_once 'utils.php';
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>MyEngine</title>
  <link rel="stylesheet" href="../css/style.css">
  <style>
    #modal-wordcloud {
      display: none;
      /* Par défaut, le modal est masqué */
      position: fixed;
      top: 50%;
      /* Le modal sera centré verticalement */
      left: 50%;
      /* Le modal sera centré horizontalement */
      transform: translate(-50%, -50%);
      /* Centrer le modal précisément */
      background-color: white;
      padding: 20px;
      height: fit-content;
      overflow: auto;
      width: fit-content;
      /* Vous pouvez ajuster la largeur selon vos besoins */
      /*max-width: 400px; /* Définissez une largeur maximale si nécessaire */
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      z-index: 1;
    }
  </style>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>
  <div class="wrapper">
    <nav id="document-content-nav-menu">
      <ul>
        <li><a href="../index.php">Acceuil</a></li>

        <!-- TODO : Télèchargement du fichier -->
        <?php
        // Check if the 'chemmin' parameter is set in the query string
        if (isset($_GET['chemin'])) {
          // Get the value of the 'chemmin' parameter
          $chemin = "../" . $_GET['chemin'];

          // Validate or sanitize the path if needed (to prevent security issues)
          // For simplicity, let's assume the value is safe for demonstration purposes

          // Construct the download link with the provided path
          $downloadLink = '<li><a id="download-btn" href="' . $chemin . '" download>Télécharger</a></li>';

          echo $downloadLink;
        } else {
          // Default download link if 'chemmin' is not set
          $downloadLink = '<li><a id="download-btn" href="" download>Télécharger</a></li>';
          echo $downloadLink;
        }
        ?>
        <li><a href="#" onclick="window.print(); return false;">Imprimer</a></li>
        <li><a href="#">+ aA</a></li>
        <li><a href="#">- aA</a></li>

        <!-- nuage de mots-clés -->
        <li><a id="wordcloud-btn" class="open-modal" href="">Nuage de mots-clés</a></li>

      </ul>
    </nav>
    <main class="content">
      <?php




      // 
      if (
        isset($_GET["chemin"])
        && !empty($_GET["chemin"])
      ) {

        // chemin du fichier
        $chemin = htmlspecialchars($_GET["chemin"]);

        echo "<h1>$chemin</h1>";

        //echo $chemin;

        // contenu du fichier
        $contenu = file_get_contents(dirname(__FILE__, 2) . "/" . $chemin);

        //
        $info_fichier = pathinfo($chemin);

        //
        $format = $info_fichier['extension'];

        // 
        $docs_path = dirname(__FILE__, 2);

        $absolute_path = "../" . $chemin;


        switch ($format) {
            // .txt
          case 'txt':
            //var_dump($contenu);
            // nl2br pour garder les paragraphes
            echo nl2br($contenu);
            break;
          case ($format === 'html' || $format === 'htm'):
            //var_dump($absolute_path);
            // bouton pour avoir une redirection page html             
            echo '<a href="' . $absolute_path . '" target="_blank">Ouvrir le fichier</a>';
            break;
          case 'pdf':
            echo '<a href="' . $absolute_path . '" target="_blank">Ouvrir le fichier</a>';
            echo '<br><br>';
            echo '<h1>Contenu du fichier</h1>';
            echo get_pdf_text($absolute_path);
            break;

          case 'docx':
            echo '<a href="' . $absolute_path . '" target="_blank">Ouvrir le fichier</a>';
            echo '<br><br>';
            echo '<h1>Contenu du fichier</h1>';
            echo get_docx_text($absolute_path);
            break;
          default:
            echo "ERREUR : fichier non valide.";
        }
      }

      ?>
    </main>
  </div>



  <!-- TODO : modal pour le nuage de mots-clés -->
  <div id="modal-wordcloud" style="">

  </div>




</body>

</html>


<script>
  // récupérer les liens "+ aA" et "- aA"
  const _AUGMENTER_TEXTE = document.querySelector('li:nth-child(4) a');
  const _DIMINUER_TEXTE = document.querySelector('li:nth-child(5) a');

  // récupérer l'élément .content
  const contenu = document.querySelector('.content');

  // initialiser la taille de police actuelle à 16px
  let _taille_police = 16;

  // ajouter un gestionnaire d'événement click à chaque lien
  _AUGMENTER_TEXTE.addEventListener('click', () => {
    _taille_police++;
    contenu.style.fontSize = `${_taille_police}px`;
  });

  _DIMINUER_TEXTE.addEventListener('click', () => {
    _taille_police--;
    contenu.style.fontSize = `${_taille_police}px`;
  });
</script>


<script>
  // fonction pour l'affichage/ fermeture des modals
  function toggleElementDisplay(elementId) {

    const element = document.getElementById(elementId);

    if (element.style.display === 'none' || element.style.display === '') {
      element.style.display = 'block'; // Change to 'block' to make it visible
    } else {
      element.style.display = 'none'; // Change to 'none' to hide it
    }
  }

  // Fonction pour mélanger un tableau (utilise l'algorithme de Fisher-Yates)
  function shuffleArray(array) {
    for (let i = array.length - 1; i > 0; i--) {
      const j = Math.floor(Math.random() * (i + 1));
      [array[i], array[j]] = [array[j], array[i]];
    }
  }

  // Fonction pour générer une couleur aléatoire
  function getRandomColor() {
    const letters = "0123456789ABCDEF";
    let color = "#";
    for (let i = 0; i < 6; i++) {
      color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
  }
</script>



<script>
  // 
  document.addEventListener('DOMContentLoaded', function() {

    // 
    console.log("DOM prêt");

    //
    const _download_btn = document.getElementById('download-btn');
    const _wordcloud_btn = document.getElementById('wordcloud-btn');
    const _modal = document.getElementById('modal-wordcloud');



    _download_btn.addEventListener('click', () => {
      console.log('click download');
    });

    _wordcloud_btn.addEventListener('click', function(e) {
      e.preventDefault();

      console.log('click wordcloud');

      // toggle le modal
      if (window.getComputedStyle(_modal).display === 'none') {
        _modal.style.display = "block";
      } else {
        _modal.style.display = "none";

      }


      // TODO : requête pour recuperer l'ensemble des mots du fichier 
      $.ajax({
        url: "get_wordcloud.php",
        method: "POST", // HTTP request method
        data: {
          file_path: '<?php echo str_replace("\\", "/", $chemin) ?> ' // chemin du fichier
        },
        //dataType: "json", // Expected data type
        success: function(response) {
          //console.log(response);         

          let data = JSON.parse(response);
          console.log(data['res']);




          // construire le nuage de mots
          let modal_inner_div = "<div>";

          // Trouver la fréquence maximale et minimale dans vos données
          let maxFrequence = Math.max(...data['res'].map(item => item['frequence_mot']));
          let minFontSize = 12; // Taille de police minimale en pixels
          let maxFontSize = 60; // Taille de police maximale en pixels

          const spans = []; // Tableau pour stocker les éléments span

          // Utilisation d'une boucle for pour parcourir le tableau
          for (var i = 0; i < data['res'].length; i++) {
            let contenu_mot = data['res'][i]['contenu'];
            let frequence_mot = data['res'][i]['frequence_mot'];

            // Créer un élément span pour chaque mot et ajouter le contenu_mot
            let spanElement = document.createElement("span");
            spanElement.textContent = contenu_mot + "(" + frequence_mot + ")";

            // Ajouter la classe "mot" au span (vous pouvez personnaliser la classe selon vos besoins)
            spanElement.classList.add("mot");

            // Calculer la taille de police proportionnelle à la fréquence
            let fontSize = minFontSize + (maxFontSize - minFontSize) * (frequence_mot / maxFrequence);

            // Définir la taille de police en pixels
            spanElement.style.fontSize = fontSize + "px";

            // Générer une couleur aléatoire pour le texte
            let randomColor = getRandomColor();
            spanElement.style.color = randomColor;

            // Ajouter le span au tableau
            spans.push(spanElement);
          }

          // Mélangez le tableau pour réorganiser aléatoirement les éléments
          shuffleArray(spans);

          // Ajoutez les éléments dans le désordre à votre conteneur _modal
          for (const span of spans) {
            modal_inner_div += span.outerHTML;
            modal_inner_div += " "; // Ajoute un espace pour séparer les mots
          }

          // Fermer la div modal_inner_div
          modal_inner_div += "</div>";

          // Maintenant, ajoutez modal_inner_div à votre élément _modal
          _modal.innerHTML = modal_inner_div;





        },
        error: function(xhr, status, error) {
          console.error("Error: " + error);
        }
      });




    });



  });
</script>


<script type="text/javascript">
  function openInNewTab(url) {
    var win = window.open(url, '_blank');
    win.focus();
  }
</script>