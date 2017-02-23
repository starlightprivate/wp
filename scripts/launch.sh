docker rm $(docker ps -a -q) || true
docker rmi $(docker images -q -f dangling=true) || true
docker-compose stop
docker-compose build 
docker-compose up -d