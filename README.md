C'est pour SQLITE

Ne pas oublier de faire un 
    
    $ composer install
    
    Allez modifier le fichier .env cette ligne 
    DATABASE_URL=mysql://root@127.0.0.1:3306/upjv-test 
    remplacer par 
	DATABASE_URL=sqlite:///%kernel.project_dir%/var/data.db
	
	Puis dans: config/package/doctrine.yaml
	Changert pdo_msql par pdo_sqlite
	
    Pour finir
    php bin/console doctrine:database:create
    php bin/console make:migration
    php bin/console doctrine:migrations:migrate

