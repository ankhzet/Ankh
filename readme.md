## Ankh
============

Ankh is a service for SAMLIB updates tracking/versioning with public API.

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

- [x] Author entity
	- [ ] Author entity admin-UI
	- [x] Authors listing page
	- [x] Author info page
	- [x] Author submit page
	- [x] Author edit page
	- [x] Author-related updates page

- [x] Group entity
	- [ ] Group entity admin-UI
	- [x] Groups listing page
		- [x] Author-groups listing page
	- [x] Group info page
	- [x] Group edit page
	- [x] Group-related updates page

- [x] Page entity
	- [ ] Page entity admin-UI
	- [x] Pages listing page
		- [x] Author-pages listing page
		- [x] Group-pages listing page
	- [x] Page info page
	- [x] Page edit page
	- [x] Page-related updates page
	- [ ] Page versions page
		- [ ] Page version view
		- [ ] Page version diff
		- [ ] Page version download
	- [ ] Pages collection
		- [ ] Pages collection admin-UI
		- [ ] Pages collection page
		- [ ] Pages collection API

- [x] Update entity
	- [ ] Update entity admin-UI
	- [x] Updates page
	- [x] Updates RSS

- [ ] Update watcher

- [x] Updates checker
	- [ ] Load balancer
	- [ ] Cron command
		- [ ] Cron command admin-UI

- [x] REST'ful service API
	- [x] Author API
	- [x] Group API
	- [x] Page API
	- [ ] Pages collection API
	- [ ] Update API
	- [ ] Update watch API


### Change notes

- 0.0 Initial commit
- 0.1 Common entity types implementation
- 0.2 Basic UI
- 0.3 Basic Updates functionality implementation
- 0.4 Added RSS feeds
- 0.5 Basic forms
- 0.6 Updates handling, views & formatting
- 0.7 Synk subsystem implementation
