#  API PHP

APi qui permet de gérer les réservations de mon site esprit-vigee.com. 
On peut voir, ajouter, modifier, ou supprimer des réservations.
Cette API permet également la connexion au backoffice à partir des infos de connexion stockés en BDD.


## Base de données

Pensez à mettre à jour les données de connexion dans le fichier index.php si vous voulez tester en local.


## Endpoints
Cette API permet l'utilisation des endpoints suivants :

| Méthode | URL  | Action |
|--|--|--|
| GET | `/reservation` | read |
| POST | `/reservation` | create |
| GET | `/reservation/123` | read |
| PUT | `/reservation/123` | update |
| DELETE | `/reservation/123` | delete |

| POST | `/connexion` | connexion backoffice |
| POST | `/deconnexion` | deconnexion backoffice |

