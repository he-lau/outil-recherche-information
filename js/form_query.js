// DOM chargé correctement  
$(document).ready(function() {
  // Listener sur le formulaire
  $('#search-form').submit(function(event) {
    // pas prendre en compte action 
    event.preventDefault(); 
    // requête de l'utilisateur
    var formData = $(this).serialize();

    $.ajax({
      url: 'php/search.php',
      type: 'GET',
      data: formData,
      // requête OK
      success: function(response) {
        // Supprime les resultats si existant
        $('#search-results').remove();

        // Element à injecter dans le DOM
        var resultDiv = $('<div>').attr('id', 'search-results');

        // Ajout de la réponse serveur dans la div
        resultDiv.html(response);        

        // Ajout au DOM dans le body
        $('body').append(resultDiv);
      },
      // erreur
      error: function(e) {
        alert('Error: ' + e.status + ' ' + e.statusText);
      }
    });
  });
});