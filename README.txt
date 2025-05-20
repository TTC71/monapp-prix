#########################################
#          PACK IMPORT CSV NAS          #
#########################################

CONTENU :
---------

- import_csv.php => Script d'import CSV vers MariaDB (avec debug intégré via php_debug.log)

INSTRUCTIONS :
--------------

1. Copier TOUS les fichiers dans le dossier partagé 'web' de votre NAS Synology :
   -> Exemple : \Synology_NAS\web\

2. Ouvrir votre navigateur et exécuter le script :
   -> http://192.168.1.16/import_csv.php

3. Visualiser les résultats :
   -> Si l'import se passe bien => "Import terminé !"
   -> Si une erreur survient => Un fichier php_debug.log sera créé à côté avec le détail de l'erreur.

AUTRES INFOS :
--------------

- Le fichier BaseProduits.csv doit être placé à côté de import_csv.php (dans le même dossier)
- Le script ignore les doublons grâce à INSERT IGNORE
- Compatible PHP 7.4 -> 8.2 avec gestion avancée des erreurs

BONNE UTILISATION !
