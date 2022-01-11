# Test technique Klaxoon

Ce test a pour but d’évaluer vos compétences en **PHP** et **Symfony**.
Il ne sera pas utilisé par Klaxoon.

_**Temps estimé** : entre 3 et 4 heures_

_**Note** : Si vous n'avez pas le temps de finir, ajoutez des TODO explicites avec des explications 
sur comment vous auriez implémenté la partie manquante.
Privilégiez la conception et l'implémentation sur la documentation._

## Énoncé

On voudrait créer une application de gestion de bookmarks, qui permet d'ajouter différents types de 
liens (vidéo, musique, image, gif...).

Chaque type de lien peut avoir plusieurs providers. (liens vidéo avec Vimeo, Youtube…)

L'application sera **évolutive** et **résiliente**

## Instructions

Pour le test vous devrez implémenter:
- une API REST au format JSON qui permet de:
  + Lister les liens
  + Ajouter un lien
  + Supprimer un lien
- deux types de liens avec des providers différents :
  + Lien de type vidéo du provider **Vimeo**
  + Lien de type photo du provider **Flickr**

Les propriétés communes d’un lien référencé sont :
* URL
* Nom du provider
* Titre du lien
* Auteur du lien
* Date d'ajout dans l'application que vous développez
* Date de publication

Les liens de type vidéo auront les propriétés spécifiques suivantes :
* largeur
* hauteur
* durée

Idem pour les liens de type image :
* largeur
* hauteur

La récupération des propriétés d’un lien référencé se fait en utilisant le protocole ouvert [oEmbed](http://oembed.com/).
_Exemple de librairie qui implémente oembed: https://github.com/oscarotero/Embed_

L'Application front qui consomme l'API n'a pas besoin de la date de publication.

## Contraintes

- Utiliser **Symfony 5.x** et **PHP 7.x**
- Ne pas utiliser de générateur d'API tel que **API Platform**, **Fos RestBundle** ou des générateurs de code tel que le **symfony/maker-bundle**
- Pas besoin de faire la partie front qui consomme l'API.

Le livrable attendu est une archive de l’application incluant si besoin les instructions d’installation.

## Info supplémentaire

Si vous avez des méthodes ou outils particuliers pour tester votre application, merci de nous l'indiquer.

## Aide

### Docker
Pour vous aider à démarrer, nous vous proposons un squelette d’application Symfony avec un docker-compose.

Les pré-requis pour utiliser le squelette sont:

* Être sous Linux
* Installer docker https://docs.docker.com/install/
* Installer docker-compose https://docs.docker.com/compose/install/

### oEmbed

Pour récupérer les données vimeo et flickr, si vous utilisez la bibliothèque proposée :

Vous pouvez récupérer les infos via : `$info = $this->embed->get($url)`.
Vous pouvez récupérer les champs spécifiques via `$info->getOEmbed()`.

### Mysql

L'url pour se connecter à MySQL est la suivante : `mysql://root@mysql:3306/symfony?serverVersion=5.7`
