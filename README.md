# outil-recherche-information

## Présentation

L’objectif est de créer un outil pour chercher des informations utilisant des mots-clés dans un ensemble de documents. L’application devra fournir une interface de recherche à l’utilisateur et permettre la visualisation des documents.

## Installation

### Prérequis
- php 7 +
- apache
- mysql

### Mise en place
- création des tables nécessaire dans `db/db.sql`
- changer les paramétres de connexion à la base de donnée (SERVER,USERNAME,PASSWORD) dans `php/const.php`;
- lancer l'indexation avec le script  `LireRecursDir.php`