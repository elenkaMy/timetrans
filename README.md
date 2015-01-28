Yii-Base Solves
===============

Installing
----------

1) Init git (don't forget to change path to git):

    git init
    git remote add origin ssh://path/to.git
    git pull origin master

2) Config your specified parameters:

    In directory www/protected/config/
     - copy file main-local.php.dist -> main-local.php and customize if need
     - copy file env-local.php.dist -> env-local.php and customize if need

    *Note!* Recommendations for production environment:
     - env-local - comment YII_DEBUG
     - env-local - comment YII_TRACE_LEVEL
     - env-local - uncomment YII_LOAD_FILE
     - main-local - uncomment cache component, change class property to CFileCache
     - main-local - uncomment schemaCachingDuration property in db config

3) Apply all database migrations:

    php www/protected/yiic migrate

Upgrading
---------

1) Pull changes from git repository:

    git pull origin master

2) Apply new database migrations:

    php www/protected/yiic migrate

3) Translate all po files -> mo files (not required for dev environment):

    php www/protected/yiic translates

4) Clear assets and cache (not required for dev environment):

    php www/protected/yiic clearcache all

5) Combine & compress assets (not required for dev environment):

    php www/protected/yiic assets dump

Configure
---------

1) Create admin user:

    php www/protected/yiic user create --name=USER_NAME --email=USER_EMAIL --is_admin=1 [--password=PASSWORD]

2) Change user password:

    php www/protected/yiic user changePassword --nameOrEmail=USER_NAME_OR_EMAIL --newPassword=PASSWORD

3) Translate all po files -> mo files:

    php www/protected/yiic translates

4) Clear cache:

    php www/protected/yiic clearcache cache

5) Clear assets:

    php www/protected/yiic clearcache assets

6) Combine & compress assets:

    php www/protected/yiic assets dump
