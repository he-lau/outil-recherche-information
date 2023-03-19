<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>outil-recherche-mot</title>
    <style>
/* body styles */
body {
  margin: 0;
  font-family: 'Roboto', sans-serif;
  background-color: #f2f2f2;
}

/* header styles */
header {
  background-color: #fff;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  padding: 20px;
  text-align: center;
}

h1 {
  margin: 0;
  font-size: 36px;
  font-weight: 700;
  color: #3c4043;
}

/* form styles */
form {
  margin: 50px auto;
  max-width: 600px;
  display: flex;
  justify-content: center;
}

input[type="text"] {
  flex: 1;
  padding: 16px;
  font-size: 18px;
  border: none;
  border-radius: 8px 0 0 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

button[type="submit"] {
  padding: 16px 32px;
  font-size: 18px;
  color: #fff;
  background-color: #4285f4;
  border: none;
  border-radius: 0 8px 8px 0;
  cursor: pointer;
  transition: background-color 0.2s ease-in-out;
}

button[type="submit"]:hover {
  background-color: #357ae8;
}

/* search result styles */
.search-result {
  margin: 50px auto;
  max-width: 600px;
  list-style:decimal;
  padding: 0;
}

.search-result li {
  background-color: #fff;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  margin-bottom: 16px;
  position: relative;
  padding-left: 20px;
  color: #4285f4; /* couleur de numéro */
  font-size: 1.5rem;  
}


.search-result li:last-child {
  margin-bottom: 0;
}

.search-result li a {
  display: block;
  padding: 16px;
  font-size: 18px;
  color: #3c4043;
  text-decoration: none;
  transition: background-color 0.2s ease-in-out;
}

.search-result li a:hover {
  background-color: #f2f2f2;
}


.search-result-count {
  padding: 20px;
  margin: 50px auto;
  max-width: 600px;
}



.search-result li {
  background-color: #fff;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  margin-bottom: 16px;
  position: relative;
  padding-left: 20px;
}

.search-result li::before {
  content: "";
  display: block;
  position: absolute;
  left: 0;
  top: 0;
  bottom: 0;
  width: 12px;
  background-color: #4285f4;
}

.search-result li:nth-child(2n)::before {
  background-color: #34a853;
}

.search-result li:nth-child(3n)::before {
  background-color: #fbbc05;
}

.search-result li:nth-child(4n)::before {
  background-color: #ea4335;
}





    </style>
  </head>
  <body>
    <header>
      <h1>MyEngine</h1>
    </header>
    <form method="get" action="php/search.php">
      <input type="text" name="query" placeholder="Rechercher...">
      <button type="submit">Rechercher</button>
    </form>

    <h2 class="search-result-count">Nombre de réponse(s) pour (mot) : 6</h2>
    

    <ul class="search-result">
        <li><a href=""><h3>toto.txt (1)</h3></a></li>
        <li><a href=""><h3>tata.txt (3)</h3></a></li>
        <li><a href=""><h3>toto.txt (7)</h3></a></li>
        <li><a href=""><h3>tata.txt (3)</h3></a></li>
        <li><a href=""><h3>toto.txt (1)</h3></a></li>
        <li><a href=""><h3>tata.txt (42)</h3></a></li>
    </ul>

  </body>
</html>
