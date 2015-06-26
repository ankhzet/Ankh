## Ankh
============

Ankh is service for SAMLIB updates tracking/versioning with public API.

Installation
----------------

### Technical requirements

- Any webserver, that meets Laravel 5 requirements


### Installation Instructions

- Clone this repo
- Pull app dependencies via composer:
```bash
machine:~/ user$ cd ankh/
machine:ankh user$ composer install
machine:ankh user$ chmod -R a+rw storage
machine:ankh user$ chmod -R a+rw bootstrap/cache
```
- Configure database (you can do that while dependencies are downloading):
```bash
machine:ankh user$ sudo mysql
mysql> CREATE USER 'ankh'@'localhost' IDENTIFIED BY 'ankh';
mysql> SET PASSWORD FOR 'ankh'@'localhost' = PASSWORD('secret');
mysql> CREATE DATABASE ankh_db;
mysql> GRANT ALL PRIVILEGES ON ankh_db . * TO 'ankh'@'localhost';
mysql> FLUSH PRIVILEGES;
```
Note: If yours database/user preferences differs, dont forget to make changes in `.env` file.
- Create all required database tables and seed them with data with artisan:
```bash
machine:ankh user$ php artisan migrate --seed
```
- Now you can open site in browser and login as `ankhzet@gmail.com`, password `password`
Note: You can change database/seeds/UsersTableSeeder.php to seed database with yours credentials.

### Implementation progress

- [x] Users/Roles



### Change notes

- 0.0 Initial commit
