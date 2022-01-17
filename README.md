# Fiche Info Ciné
Ceci est le dépôt git pour l'exercice intitulé "Fiche Info Ciné".

## Commande
La commande Laravel pour exécuter le script est la suivante :
> php artisan lp:export [options]

Cette commande va générer les fiches JSON des personnes concernées, et les enregistrer dans le répertoire de destination (par défaut : `storage/app/public/lp`)

## Paramètres optionnels

### Batch size
Le paramètre optionnel `--batch| permet de définir la taille d'un batch, permettant d'accélérer le temps de traitement. ATTENTION ! Plus cette taille est grande, plus la consommation-mémoire grossira aussi, au risque de saturer la RAM du serveur.

### Output
Le paramètre optionnel `--output=OUTPUT` permet de définir un autre répertoire de destnation que celui par défaut. Ce répertoire doit cependant être dans `storage/app/public`.
### Verbose
Le paramètre optionnel `-vvv` permet d'activer le mode DEBUG. Ce mode génère plus de traces de logs que la normale, à la fois dans le fichier de logs (`storage/logs/command.log`) et dans la console.
