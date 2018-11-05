C'est pour MYSQL

Ne pas oublier de faire un 
    
    $ composer install
    
    Allez modifier le fichier .env cette ligne 
    DATABASE_URL=mysql://root@127.0.0.1:3306/upjv-test 
    remplacer par vos innformation de basse de donnée
    Example
    
    Si vous aves un mot de passe :
    DATABASE_URL=mysql://<Utilisateur>:<password>@<addresse>:<port>/database
     Si vous aves pas mot de passe :
    DATABASE_URL=mysql://<Utilisateur>@<addresse>:<port>/database

    Pour finir
    php bin/console doctrine:database:create
    php bin/console make:migration
    php bin/console doctrine:migrations:migrate

