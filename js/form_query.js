// DOM chargé correctement
$(document).ready(function () {
  // Listener sur le formulaire
  $("#search-form").submit(function (event) {
    // pas prendre en compte action
    event.preventDefault();
    // requête de l'utilisateur
    var formData = $(this).serialize();

    $.ajax({
      url: "php/search.php",
      type: "GET",
      data: formData,
      // requête OK
      success: function (response) {
        // Supprime les resultats si existant
        $("#search-results").remove();

        // Element à injecter dans le DOM
        var resultDiv = $("<div>").attr("id", "search-results");

        // Ajout de la réponse serveur dans la div
        resultDiv.html(response);

        // Ajout au DOM dans le body
        $("body").append(resultDiv);
      },
      // erreur
      error: function (e) {
        alert("Error: " + e.status + " " + e.statusText);
      },
    });
  });
});

// DOM prêt
document.addEventListener("DOMContentLoaded", function () {
  // Ajouter un event listener au clic pour tous les liens avec la classe 'suggestion-link'
  document.addEventListener("click", function (event) {
    var clickedElement = event.target;

    // Vérifier si l'élément cliqué est un lien avec la classe 'suggestion-link'
    if (clickedElement.classList.contains("suggestion-link")) {
      event.preventDefault(); // Empêche le comportement par défaut du lien
      var linkId = clickedElement.id;
      // Faites quelque chose avec l'ID du lien ou effectuez une action spécifique
      console.log("Link clicked with ID:", linkId);

      query = linkId.split("_")[1];

      // requête search avec le lien cliqué
      $.ajax({
        url: "php/search.php",
        type: "GET",
        data: {
          query: query,
        },
        // requête OK
        success: function (response) {
          // TODO : MAJ DOM : liste res + barre de recherche
          // Supprime les resultats si existant
          $("#search-form-input").val(query);
          $("#search-results").remove();

          // Element à injecter dans le DOM
          var resultDiv = $("<div>").attr("id", "search-results");

          // Ajout de la réponse serveur dans la div
          resultDiv.html(response);

          // Ajout au DOM dans le body
          $("body").append(resultDiv);
        },
        // erreur
        error: function (e) {
          alert("Error: " + e.status + " " + e.statusText);
        },
      });
    }
  });
});
