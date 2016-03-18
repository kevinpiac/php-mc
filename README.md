*Dernière mise à jour le 18/03/2016*
#Arborescence du projet#


```
#!php

/config
   Database.php
/controller
   ControllerName.php
/model
   ModelName.php
/core
   Controller.php
   Model.php
   includes.php
   ShellRouter.php
   Router.php
/webroot
   index.php
/js
   account_gen.js
   token_gen.js
   token_get.js
   facebook.js

```


#Structuration technique du projet#

## Rappel ##

Le projet fonctionne selon une architecture Model - Controller. Les models sont des classes d'abstraction de base de donnée. En d'autres termes ils permettent de gérer l'ensemble des connexion / requêtes à la base de donnée. Les controllers, eux, représentent le "cerveau" du projet. Ce sont eux qui seront en charge du traitement des informations et du fonctionnement du projet.

## Convention de structure : ##
### Les models ###
- **Un** model correspond à **une** table en base de donnée.
- Tous les models seront créés dans le dossier model tel que :
```
#!php

/model/ModelName.php
```
### Les controllers ###
**Un** controller peut correspondre soit à **une table** en base de donnée, soit à **un** groupement logique de tâches.
* Par exemple, une table users aura un controller *Users* qui sera en charge de plusieurs action (méthodes):

```
#!php

Users->login()
Users->logout()
Users->register()
Users->remove()
Users->listAll()
etc.
```
* Dans certain cas un controller ne correspond pas forcement a une table de ou un Model. Nous pouvons imaginer un Controller *Verifs* qui aura pour rôle de s'occuper de toutes les vérifications dont notre application a besoin avec les actions suivantes :

```
#!php

Verifs->isEmailFormat()
Verifs->isActive()
Verifs->isServerAlive()
Verifs->wathever()
```


### Conventions de nommage (généralité): ###

* Une table de base de donnée doit avoir un nom explicite.
* Un nom de table est toujours écrit au pluriel et en minuscule. 
* De préference en anglais pour facilier les pluriels. 
* Chaque nom de class (objet) doit commencer par une majuscule et s'écrire en CamelCase (y compris le nom du fichier). Exemple :

```
#!php

Model.php //Il s'agit d'un fichier contenant une class php (donc CamelCase.).
ShellRouter.php // Pareil.
includes.php // Il ne s'agit pas d'une class php, donc miniscule.
```


### Nommage des models ###
*Un nom de model prend le nom de sa table **MAIS au singulier**, avec **une lettre majuscul**e au début. 
* * --> exemple: la tables "**cards**", permet de lister les fiches. Le model correspondant se nome "**Card**". 



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