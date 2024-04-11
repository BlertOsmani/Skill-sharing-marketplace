# Skill-sharing-marketplace

## Artisan Command Line Interface
Artisan is the command-line interface included with Laravel. We utilized its powerful commands for various tasks throughout the development process.

## Database Configuration
To configure the database, update the `database.php` file located in the `config` directory. Within this file, you can specify the default database connection using the `'default'` key:

```php
'default' => env('DB_CONNECTION', '<specifiedDb>'), 

Below this line, you will find the definitions for all the database connections used by your application. Configure the host, port, database name, username, and password for the desired connection.

Additionally, update the .env file by setting the DB_CONNECTION variable to the specified database you intend to use.

##Migrations
In this application, migrations are utilized to manage and version-control changes to the database schema. To create a new migration in Laravel, open the terminal and execute the following command:

```php
php artisan make:migration <name_of_migration>

After creating the migration, ensure that the database is properly configured. If the database is set up correctly, run the following command to apply the migrations and create the tables defined in the migration files:

```php
php artisan migrate

Note: Do not delete the sessions table defined in the migrations by default when creating a Laravel project, as this will prevent the application from functioning properly. If you accidentally delete it, you can recreate the sessions table using the following command:

```php 
php artisan session:table

##Object-Relational Mapping (ORM)
This application's server is built with Laravel, which uses the Eloquent ORM by default.

###Integration
There are no specific integration steps required for using Eloquent as an ORM, as it is installed by default when creating a Laravel project.

###Usage within the Application
Eloquent is utilized for the following:

*User Registration:
*Checking for existing users before insertion, using the email as a unique identifier.
*Inserting new users into the database.