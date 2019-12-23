# Subterfuge Backend

[![Run in Postman](https://run.pstmn.io/button.svg)](https://app.getpostman.com/run-collection/832fc79f1e130e713524)

### Setup Instructions
Step 1: Go to https://www.apachefriends.org/download.html
Step 2: Download the latest release and install it
Step 3: Change the default password for mysql root
```
mysql -u root
SET PASSWORD FOR 'root'@'localhost' = PASSWORD([Password]);
```
Step 4: Add your profile which you want to use with mysql
```
mysql -u root -p
CREATE USER [Username] IDENTIFIED BY [Password];
```
Step 5: Add the required permissions to your profile (we will add all for now, use either % for network-wide or localhost)
```
GRANT ALL PRIVILEGES ON * . * TO [Username]@'%';
```
Step 6: Create the Databases
```
CREATE DATABASE sandbox;
CREATE DATABASE events_ongoing_rooms;
```
Step 7: Import the .sql presets
```
mysql -u [Username] -p sandbox < [pathTo:sandbox.sql]
mysql -u [Username] -p sandbox < [pathTo:events_ongoing_rooms.sql]
```
Step 8: Go to https://getcomposer.org/download/
Step 9: Download and install composer
Step 10: Check if composer installed correctly
```
composer
```
Step 11: Clone this repository in your /xampp/htdocs/ location
Step 12: Switch to the sandbox folder
Step 13: Install project dependencies
```
composer install
```
Step 14: Open XAMPP and start Apache and MySQL services

Final note: [Username] [Password] and similar should be replaced respectively