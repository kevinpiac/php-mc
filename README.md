*Dernière mise à jour le 18/03/2016*
#Arborescence du projet#

* /config
* * Database.php
* /controller
* * Cards.php
* * all other controllers
* /core
* * Controller.php
* * Model.php
* * includes.php
* * ShellRouter.php
* * Router.php
* /webroot
* * index.php

Structuration technique du projet
=======================


Convention de structure :
---------------------------
- Un model correspond a une table en base de donnee.
- Un model se trouve dans le dossier [root]/model/[Nom.php]


Convention de nommage :
----------------------------
- Une table de base de donnee doit avoir un nom explicite.
- Un nom de table est toujours ecrit au pluriel et en minuscule. 
- De preference en anglais pour facilite les pluriels. 
- Une nom de model prend le nom de sa table MAIS au singulier, avec une lettre majuscule au debut. 
--> exemple: 
la tables "**cards**", permet de lister les fiches. Le model correspondant se nome "**Card**". 
- Chaque nom de class (objet) doit commencer par une majuscule (y compris le nom du fichier). 
--> exemple:
la class **Model** dans **Model.php**, la class **Card** dans **Card.php**




Utilisation d'un model : 
======================

Introduction
---------------------------
Le model se charge depuis un Controller avec la **methode loadModel($name)**
--> exemple 
**$c = new Controller();** // initialisation du controller principal
**$c->loadModel('Card');** // chargement du Model Card.
**$c->Card->methode();**  // je peux acceder directement au model Card depuis mon controlleur et lui appliquer une methode. 

---------------------------------------------

Recuperer des donnees
-----------------------------------------------
**La methode find([array $data]).**
Tous les models heritent de cette methode. Elle peut prendre un array en parametre, mais cela reste facultatif. Pour le moment les parametres supportes sont les suivantes : 
- fields *// array contenant les champs que l'on souhaite recuperer*
- conditions *// array contenant les conditions (equivaut a WHERE)*
- limit *// indique le nombre max de donnee a charger*
- order *// array contenant les conditions d'organisation (equivaut a ORDER BY)

Si aucun parametre n'est passe a find. Alors la requete correspond a un SELECT * FROM la_table_du_model

---> exemples :

$res = $c->Card->find() // SELECT * FROM cards as Card
$res = $c->Card->find(array(
      'fields' => array(
            'email',
            'active',
            'created'
        ),
        'conditions' => array(
              'id' => '3',
               'age' => 'BETWEEN 28 AND 39'
        ),
        'limit' => '10',
        'order' => array(
              'email' => 'desc',
              'name' => 'asc'
        )
));

IMPORTANT : find retourne un tableau d'objet. Le traiment se fera donc de la maniere suivante.

foreach ($res as $k => $v)
{
         echo $v->email; *// et non $v['email']*
         echo $v->active; *// et non $v['active']*
         echo $v->created; *// et non $v['created']*
}

**La methode findFirst([array $data]).**
--> fonctionne exactement de la meme maniere que find() sauf qu'elle retourne le premier element trouve.

**La methode save(array $data).**
Cette methode permet d'inserer une ligne en base de donnee. Actuellement la methode gere uniquement l'insertion, a terme elle gerera automatiquement l'update de donnees si la ligne existe deja.

----> exemple :
$c->Card->save(array(
    'email' => 'testsave@gmail.com',
    'name' => 'Kevin',
    'blabla' => 'blabla
));

To be continued. PEACE.