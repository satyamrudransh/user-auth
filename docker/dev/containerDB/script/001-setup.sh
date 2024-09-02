#!/bin/bash

# Delay function
delay() {
  sleep 2
}

#sudo sed -i "s/DB_HOST=.*/DB_HOST=db/" .env
#sudo sed -i "s/DB_HOST_SERVICE=.*/DB_HOST_SERVICE=db/" .env


git reset --hard
git pull

# Remove existing container
docker rm -f testservice
delay

# Remove existing image
docker rmi testservice:dev
delay

# Build the image
docker build -t testservice:dev -f docker/dev/containerDB/dockerfile .
delay


# Create the container using the newly built image
docker run -v ${PWD}:/app -d -p 8060:8060 --name testservice --network mysqlDB-network --link mysqlLocalDB:db testservice:dev
delay
