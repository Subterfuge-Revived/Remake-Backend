[![languages](https://img.shields.io/github/languages/top/Subterfuge-Revived/Remake-Backend)]()
[![code-size](https://img.shields.io/github/languages/code-size/Subterfuge-Revived/Remake-Backend)]()
[![commit-activity](https://img.shields.io/github/commit-activity/y/Subterfuge-Revived/Remake-Backend)](https://github.com/Subterfuge-Revived/Remake-Backend/pulse/yearly)
[![license](https://img.shields.io/github/license/Subterfuge-Revived/Remake-Backend)](LICENSE)
[![discord](https://img.shields.io/discord/617149385196961792)](https://discord.gg/GNk7Xw4)
[![issues](https://img.shields.io/github/issues/Subterfuge-Revived/Remake-Backend)](https://github.com/Subterfuge-Revived/Remake-Backend/issues?q=is%3Aopen)
[![issues-closed-raw](https://img.shields.io/github/issues-closed/Subterfuge-Revived/Remake-Backend)](https://github.com/Subterfuge-Revived/Remake-Backend/issues?q=is%3Aclosed+)
[![Banner](banner.png)]()


# Subterfuge Backend

[![Run in Postman](https://run.pstmn.io/button.svg)](https://app.getpostman.com/run-collection/832fc79f1e130e713524)

### Setup Instructions

Step 1: Docker is supposed to run on Linux machines, but Windows support is provided by the Docker Toolbox. Follow the steps from here: https://docs.docker.com/toolbox/toolbox_install_windows/
 - If you run a recent version of Windows 10 with WSL2, you can also natively virtualize the needed Linux environment using Docker Desktop. I personally recommend this if possible, as my experience with it is more smoothly (and feels less hacky).
Step 2: Open the Docker Quickstart Terminal (or your WSL shell of choice, if applicable)

Step 3: Browse to the root folder of this project, for example:
```shell script
cd ~/PhpstormProjects/subterfuge-backend/
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
Step 8: Set things up to get the application ready to go by running the start script. This script installs composer packages, takes care of migrations and starts the web server.
```shell script
./start.sh
```
Step 9: Observe glory by sending requests to `http://192.168.99.100/api` (if you use Docker Toolbox) or `http://localhost/api` (if you use Docker Desktop)!
