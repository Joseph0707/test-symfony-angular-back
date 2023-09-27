## 1. Install the project

composer install

## 2. Change .env file

Change your environment variable by modifying the DATABASE_URL variable and make it match your environment

## 3. Create the database

php bin/console doctrine:database:create

## 4. Migrate the database

php bin/console doctrine:migrations:migrate

## 5. Run the project

symfony serve
