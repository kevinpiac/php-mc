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
includes.php // Il ne s'agit pas d'une class php, donc minuscule.
```


### Nommage des models ###
*Un nom de model prend le nom de sa table **MAIS au singulier**, avec **une lettre majuscul**e au début. 
* * --> exemple: la tables "**cards**", permet de lister les fiches. Le model correspondant se nome "**Card**". 


--------------------------------------------------
# ---------------------- LES MODELS ---------------------- #

## Création d'un model ##

Un fichier .php doit être Cree selon la norme ci-dessus. Imaginons un model Card, le fichier se nomera donc Card.php.

```
#!php

// contenu du fichier /model/Card.php
class Card extends Model
{
     public function maMethode()
     {
         // faire quelque chose ici.
     }
}
```

**Important :** un model **héritera toujours** de la class Model.

## Utilisation ##

Le model se charge depuis un Controller en utilisant la methode loadModel():

```
#!php

$c = new Controller();   // initialisation du controller
$c->loadModel('Card');   // chargement du model 'Card'. On peut donc utiliser des methodes de 'Card'.
$c->Card->maMethode();
```
---------------------------------------------

# Récupérer des données #

## find([$params]) ##

La méthode find() permet de récupérer des données. Elle acceptent un tableau (facultatif) en paramètre. Ce tableau peut contenir les champs a récupérer, les conditions de recherche (WHERE), la limit et l'ordre, comme suit.

```
#!php
$params = array(
   'fields' => array('id', 'email'),
   'conditions' => array(
      'email' => "= 'example@gmail.com'",
      'age'   => 'BETWEEN 18 AND 39'
    ),
   'limit' => 10,
   'order' => array(
      'email' => 'desc',
      'name'  => 'asc'
    )
);
$res = $c->ModelName->find($params);
```
Plus le tableau est complet plus la requête est précise. Dans le cas ou aucun champ n'est précisé le comportement de la fonction sera :
```
#!php

SELECT * FROM tableName as ModelName
```

IMPORTANT : find retourne un tableau d'objet (stdClass). Le traitement se fera donc de la maniere suivante.


```
#!php

foreach ($res as $k => $v)
{
         echo $v->email; *// et non $v['email']*
         echo $v->active; *// et non $v['active']*
         echo $v->created; *// et non $v['created']*
}
```


## findFirst([array $params]) ##
Fonctionne exactement de la même manière que find() sauf qu'elle retourne le premier élément trouvé.


## findById($id, array [$fields]) ##
Effectue un findFirst() avec l'id passe en paramètre ($id). Le paramètre $fields est facultatif. Si $fields n'est pas passé, alors la fonction récupérera tous les champs. Exemple de requête :


```
#!php

$res = $this->ModelName->findById(12, array('email', 'age', 'name')) // récupère les champs 'email', 'age' et 'active' pour l'entrée ayant l'id égal a 12.
```


## save(array $data) ##
Si la variable $id du model existe, alors la fonction exécute un updateById($this->$id, $data). Dans le cas contraire save() créera une nouvelle ligne en base de données. Pour cette raison il est préférable d'utiliser la méthode **create()** avant un save si l'objectif est de créer une nouvelle entrée.

Le paramètre $data est requis et contient les champs a ajouter/éditer et leur valeurs comme suit.


```
#!php

$c->Card->save(array(
    'email' => "'testsave@gmail.com'",
    'name' => 'Kevin',
    'blabla' => 'blabla
));
```

## create() ##
Lorsqu'une recherche est faite (avec les methodes findFirst(), findById()), si l'id du champ est récupéré par le model, alors la valeur de l'id du model est défini a la valeur de l'id du champ. Exemple :


```
#!php

$c->ModelName->id // au début cette valeur est null.
$res = $c->ModelName->findFirst(array(
    'fields' => array('id', 'email')
));
// imaginons que $res->id est egal a 4, alors :
$c->$ModelName->id // est égal a 4.
```
Cela implique qu'en cas de tentative de sauvegarde avec save() apres un findFirst(), un id sera 'set', donc aucun champ ne sera crée. Reprenons l'exemple :
```
#!php

$c->ModelName->id // au début cette valeur est null.
$res = $c->ModelName->findFirst(array(
    'fields' => array('id', 'email')
));
// imaginons que $res->id est egal a 4, alors :
$c->$ModelName->id // est égal a 4.
$c->save($data) // ici le save renvera un updateById() car l'id est défini. 
```
Pour être certain d'empêcher ce comportement nous utilisons donc la methode create() qui 'reset' l'id du Model a null. Ainsi : 
```
#!php

// ici $c->ModelName->id est égal 4.
$c->create();
$c->$ModelName->id // ici la valeur est null
$c->$ModelName->save($data) // on est sur de créer une nouvelle entrée.
```