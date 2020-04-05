# Subterfuge Backend

[![Run in Postman](https://run.pstmn.io/button.svg)](https://app.getpostman.com/run-collection/832fc79f1e130e713524)

### Setup Instructions

Step 1: Docker is supposed to run on Linux machines, but Windows support is provided by the Docker Toolbox. Follow the steps from here: https://docs.docker.com/toolbox/toolbox_install_windows/

Step 2: Open the Docker Quickstart Terminal

Step 3: Browse to the root folder of this project, for example:
```shell script
cd ~/PhpstormProjects/subterfuge-backend/
```
Step 4: Copy the `.env.example` file and adjust its values if desired. You may leave them as they are for local development. You can simply do this from your IDE/Explorer as well.
```shell script
cp .env.example .env
```
Step 5: Run the Docker containers:
```shell script
docker-compose up -d
```
Step 6: Upon first run, Docker will build the images. This may take a short time and produce a lot of output, especially when compiling PHP extensions. However you should not need to do anything.

Step 7: Verify that the application is working by asking Docker to display the running containers. You should see two containers, one for the app (PHP) and one for the database (MySQL).
```shell script
docker ps
```
Step 8: We need to go inside the PHP container to install the composer packages. You can do that as follows:
```shell script
docker-compose exec app bash
cd sandbox
composer install
```
Step 9: Exit the container (`exit`).

Step 10: Run the migrations.
```shell script
docker-compose exec db bash
cat /data/migrations/*sql | mysql -u root -p
[enter MySQL root paassword as specified in .env file]
```

Once completed, access `http://localhost` or `http://localhost/event_exec.php`.
In the backend php files, the domain `db` will resolve to the `db` docker service, allowing database connections.
