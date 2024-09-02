<div id="top"></div>

## Docker
### 1- First create a docker bridge network, so that individual containers connect to database(mysql) container seemlessly.
```
sudo docker network create mysqlDB-network
```
<p align="right">(<a href="#top">back to top</a>)</p>

## Laravel
### 1- Create a laravel app or use existing app with dockerfile to create docker image.
```
sudo docker build -t testservice:dev  -f docker/dev/containerDB/dockerfile .
```
### 2- Run created image as container.
```
sudo docker run -v ${PWD}:/app -d -p 8060:8060 --name testservice --network mysqlDB-network --link mysqlLocalDB:db testservice:dev 
```
### 3- Bash into created container to start laravel app.
#### replace <container_id or container_name> in later command with <laravel_app> or with the container id to check container id execute
```
docker ps
```
```
docker exec -it <container_id or container_name> sh
```
