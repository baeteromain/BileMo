# Projet 7 Bilemo - Créez un web service exposant une API

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/2109fb8e122d4fe093a6b732f4b1b45d)](https://www.codacy.com/gh/baeteromain/BileMo/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=baeteromain/BileMo&amp;utm_campaign=Badge_Grade)
## Prérequis / Environnement de développement

```
PHP 7.4
Composer 2.1.3
Symfony 5.3.9
Api-Platform 2.6
```

## Installation :
Telechargez directement le projet ou effectuez un git clone via la commande suite :

https://github.com/baeteromain/BileMo.git

En suivant, effectuez un composer install à la racine du projet permettant d'installer les dépendances utilisées dans ce projet.

## Base de données :
### Configuration
Modifiez le fichier ```.env``` situé à la racine du projet avec vos informations spécifiques à votre base de données, voir l'exemple ci-dessous :

```
DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7
```

Si vous avez le CLI symfony vous pouvez effectuer les commandes suivantes :

Création de la base de donnée via la commande suivante :

```symfony console doctrine:database:create```

Lancement de la migration via la commande suivante :

```symfony console doctrine:migrations:migrate```

Ajout des fixtures en base de données permettant d'avoir un échantillon de données :

```symfony console doctrine:fixtures:load```

*Note : Si vous n'avez pas le client symfony, remplacez ```symfony console``` par ```php bin/console``` ( ex : ```php bin/console doctrine:database:create```)*
## Sécurisation de l'API

**L'API est sécurisé via JWT, pour ce faire vous devez générer vos clés SSL**
### Générer clés SSL:
Exécutez la commande suivante :
```
 php bin/console lexik:jwt:generate-keypair
```

Vos clés atterriront dans **config/jwt/private.pem** et **config/jwt/public.pem** (sauf si vous avez configuré un chemin différent).

Options disponibles :

--skip-if-exists ne fera rien en silence si les clés existent déjà.
--overwrite écrase vos clés si elles existent déjà.
Sinon, une erreur sera levée pour vous empêcher d'écraser vos clés accidentellement.

Vous pouvez vous référer à la documentation du bundle :
https://github.com/lexik/LexikJWTAuthenticationBundle/blob/2.x/Resources/doc/index.md#getting-started

## Usage
### Développement

Lancez la commande ```symfony serve``` *(ou ```php bin/console server:run```, si vous n'avez pas le CLI symfony)*

Vous pouvez accéder à l'API via https://127.0.0.1:8000/api. L'API étant développée via Api-Platform, vous arrivez sur une documentation OpenAPI intéractive vous permettant de l'utiliser'.


L'API étant protégée par JWT (cf ci-dessus), vous devez vous authoriser via le token récupéré via la route disponible sur la documentation, dans le bloc **"Token"**, à savoir : **/api/login**

![](https://md.itsm-factory.com/uploads/upload_1f0ea39bcfa5bc2e727780c7cf08491c.png)

Vous devez saisir un email et password d'un client
Si vous avez lancé les fixtures, voici le compte administrateur par défault :

```
{
    "email" : "api@bilemo.com",
    "password" : "adminadmin"
}
```

Pour vous connecter avec un compte non-administrateur :
```
{
    "email" : "**email dans la table customer de la base de donnée de votre choix**",
    "password" : "azertyazerty"
}
```

Cliquez sur le bouton Authorize, et renseignez le token reçu en réponse

![](https://md.itsm-factory.com/uploads/upload_12406119583a7f8df3805a528994d3e2.png)

Vous pouvez vous référer à la documentation d'Api Platform (https://api-platform.com/docs/core/jwt/#adding-a-new-api-key)

Vous pouvez maintenant tester les différentes routes disponibles de l'API.

#### Postman / Curl / ...

L'API peut être requêtée par tous autres outils tels que Postman, Curl.


## Contexte :

BileMo est une entreprise offrant toute une sélection de téléphones mobiles haut de gamme.

Vous êtes en charge du développement de la vitrine de téléphones mobiles de l’entreprise BileMo. Le business modèle de BileMo n’est pas de vendre directement ses produits sur le site web, mais de fournir à toutes les plateformes qui le souhaitent l’accès au catalogue via une API (Application Programming Interface). Il s’agit donc de vente exclusivement en B2B (business to business).

Il va falloir que vous exposiez un certain nombre d’API pour que les applications des autres plateformes web puissent effectuer des opérations.

## Besoin client
Le premier client a enfin signé un contrat de partenariat avec BileMo ! C’est le branle-bas de combat pour répondre aux besoins de ce premier client qui va permettre de mettre en place l’ensemble des API et de les éprouver tout de suite.

Après une réunion dense avec le client, il a été identifié un certain nombre d’informations. Il doit être possible de :

* consulter la liste des produits BileMo ;
* consulter les détails d’un produit BileMo ;
* consulter la liste des utilisateurs inscrits liés à un client sur le site web ;
* consulter le détail d’un utilisateur inscrit lié à un client ;
* ajouter un nouvel utilisateur lié à un client ;
* supprimer un utilisateur ajouté par un client.
* Seuls les clients référencés peuvent accéder aux API. Les clients de l’API doivent être authentifiés via OAuth ou JWT.

Vous avez le choix entre mettre en place un serveur OAuth et y faire appel (en utilisant le FOSOAuthServerBundle), et utiliser Facebook, Google ou LinkedIn. Si vous décidez d’utiliser JWT, il vous faudra vérifier la validité du token ; l’usage d’une librairie est autorisé.