[![languages](https://img.shields.io/github/languages/top/Subterfuge-Revived/Remake-Backend)]()
[![code-size](https://img.shields.io/github/languages/code-size/Subterfuge-Revived/Remake-Backend)]()
[![commit-activity](https://img.shields.io/github/commit-activity/y/Subterfuge-Revived/Remake-Backend)](https://github.com/Subterfuge-Revived/Remake-Backend/pulse/yearly)
[![license](https://img.shields.io/github/license/Subterfuge-Revived/Remake-Backend)](LICENSE)
[![discord](https://img.shields.io/discord/617149385196961792)](https://discord.gg/GNk7Xw4)
[![issues](https://img.shields.io/github/issues/Subterfuge-Revived/Remake-Backend)](https://github.com/Subterfuge-Revived/Remake-Backend/issues?q=is%3Aopen)
[![issues-closed-raw](https://img.shields.io/github/issues-closed/Subterfuge-Revived/Remake-Backend)](https://github.com/Subterfuge-Revived/Remake-Backend/issues?q=is%3Aclosed+)
[![Banner](banner.png)]()

[![Run in Postman](https://run.pstmn.io/button.svg)](https://app.getpostman.com/run-collection/832fc79f1e130e713524)
### Quick note:

This document is in an unfinished state, if you find that something is wrong or missing let us know.

# Remake-Backend

Provides necessary components in order to enable multi-player functionality through endpoints. Currently supported: Linux 
(LAMP Stack) and Windows (WAMP Stack), there are plans to dockerize the project in future.

## Usage

We provide a Postman API package which you can get by pressing the `Run in Postman` button at the top of this 
document. Once imported various requests can be tested. 
 - Select a request, navigate to `Body > form-data` and fill-in all empty values
 - Press `Send` - The response shows up at the bottom. For improved readability switch from `HTML` to `JSON` next
 to the `Pretty | Raw | Preview | Visualize` menu
 
 - Note: If you the request returns a `404` code it might be that you have to change the link. It is generally the path 
 to the `event_exec.php` which might be different on your system.
 As an example:
    - Switching from `http://localhost/sandbox/event_exec.php` to `http://localhost/subterfuge/sandbox/event_exec.php`
    solves the issue in most cases

## Linux Setup

1. Installing the LAMP Stack (Linux, Apache, MariaDB, PHP) Part 1

   - Open a terminal window
   - Enter the following: 
     - `sudo apt install apache2`
     - `sudo apt install mariadb-server mariadb-client`
     
2. Setup database administrative user (root) password
   - Run following instructions one by one (paste them into your terminal window):
     - `sudo mysql -u root`
     - `use mysql;`
     - `update user set plugin='' where User='root';`
     - `flush privileges;`
     - `quit`
   - Now set database administrative password using
     - `sudo mysql_secure_installation`
        - Press ENTER (2x)
        - Enter password (Needed later)
        - Re-enter password
        - Press ENTER (4x)
        
3. Installing the LAMP Stack (Linux, Apache, MariaDB, PHP) Part 2
   - `sudo apt install php libapache2-mod-php php-mysql`
     
4. Fetching
   - Install git via: `sudo apt install git`
   - Switch to your LAMP directory, default: /var/www/html/
   - Create (another) subfolder and switch to it
     - Open a new terminal window  here
   - Enter: `sudo git clone https://gitlab.com/subterfugeRemake/subterfuge-backend.git . -b dev`
     
5. Creating the database
   - `mysql -u root -p`
   - Enter your password from step 2
   - `CREATE DATABASE sandbox;`
   - `quit`
     
6. Importing the preset
   - `mysql -u root -p sandbox < {0}`
     > Index {0} - Path to sandbox.sql - Can be found under /mysqldump from step 4 (LAMP directory)
   
7. Installing composer
   - `sudo apt-get install composer`
   
8. Installing project dependencies
   - Switch to the sandbox subfolder from step 4
     - Open a new terminal window here
   - `sudo mkdir vendor`
   - `sudo chown -R {1}:{1} composer.json` 
     > Index {1} - Refers to the username that is currently logged in
   - `sudo chown -R {1}:{1} composer.lock`
   - `sudo chown -R {1}:{1} ./vendor`
   - `composer install`
   - `composer update`
   
9. Adding credentials
   - `sudo touch credentials.php`
   - `sudo gedit credentials.php`
   - Paste:
        ```php
        <?php
        
        class credentials
        {
           public function get_username(){
        
              return "root";
           }
        
           public function get_password(){
        
              return "{2}";
           }
        }
        ```
        > Index {2} - Password from step 2.

## Windows Setup

1. Go to https://www.apachefriends.org/download.html

2. Download the latest release and install it

3. Setup database administrative user (root) password
   - Open a new command window
   - Change the default password for mysql root by entering:
        ```
        mysql -u root
        SET PASSWORD FOR 'root'@'localhost' = PASSWORD({0});
        ```
        > Index {0} - Choose a password

4. Profile setup 
   - Add your profile which you want to use with mysql
        ```
        mysql -u root -p
        CREATE USER {1} IDENTIFIED BY {2};
        ```
        > Index {1} - Choose a username (Needed later)
                                                                                                                                                                   
        > Index {2} - Choose a password (Needed later)                                                                                                                                                           

5. Permissions
   - Add the required permissions to your profile (we will add all for now, use either % for network-wide or localhost)
        ```
        GRANT ALL PRIVILEGES ON * . * TO {1}@'%';
        ```
        
6. Creating the database
    ```
    CREATE DATABASE sandbox;
    ```

7. Importing the .sql preset
    ```
    mysql -u {1} -p sandbox < {3}
    ```
    > Index {3} - Path to your sandbox.sql 

8. Go to https://getcomposer.org/download/

9. Download and install composer

10. Check if composer installed correctly
    ```
    composer
    ```
11. Clone the repository into your /xampp/htdocs/ location

12. Switch to the sandbox folder
    - Open a command window here
    
13. Install project dependencies
    ```
    composer install
    ```
    
14. Credentials
    - Create a new file `credentials.php` inside the sandbox folder
    - Paste:
        ```php
        <?php
        
        class credentials
        {
           public function get_username(){
        
              return "root";
           }
        
           public function get_password(){
        
              return "{2}";
           }
        }
         ```

15. Open XAMPP and start Apache and MySQL services