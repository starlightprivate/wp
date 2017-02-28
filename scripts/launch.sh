docker rm $(docker ps -a -q -f) || true
docker rmi $(docker images -q -force dangling=true) || true
docker-compose stop
docker-compose build 
docker-compose up -d