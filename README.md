# TP API PHP

Création d'une API en PHP

  

## Base de données

Pensez à mettre à jour les données de connexion dans le fichier index.php si vous voulez tester en local.
Pour que l'API fonctionne il faut avoir une base de données nommée pokedex qui contient une table pokemons avec les champs suivants :

|Nom du champ| Type de champ |
|--|--|
| id |  INT primary key|
| name |  VARCHAR(255)|
| hp | INT
| type | INT foreign key

Vous pouvez également créer une table types avec les champs suivants :
|Nom du champ| Type de champ |
|--|--|
| id |  INT primary key|
| name |  VARCHAR(255)|

## Fichier .htaccess

Le fichier `.htaccess` permet d'utiliser des URL propres type `/pokemons/1`. Attention à vérifier que cette configuration fonctionne bien sur votre serveur. Vous pouvez toujours passer des paramètres en GET à la place : `/pokemons?id=1`.

## Endpoints
Cette API permet l'utilisation des endpoints suivants (j'ai ajouté les méthodes PUT et DELETE par rapport au TP vu en classe pour modifier et supprimer un pokémon)

| Méthode | URL  | Action |
|--|--|--|
| GET | `/pokemons` | read |
| POST | `/pokemons` | create |
| GET | `/pokemons/123` | read |
| PUT | `/pokemons/123` | update |
| DELETE | `/pokemons/123` | delete |
