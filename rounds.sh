#!/bin/bash

# rounds-cli install

function rounds() {
    if [ -z $1 ]
    then
        echo "Error! usage: rounds [start, stop, ...]"
    elif [ $1 = "dev" ]
    then
        docker-compose up -d
        docker exec -it rounds_api_1 npm install
        docker exec -it rounds_api_1 php composer.phar install
        docker exec -it rounds_api_1 php artisan migrate
        npm run watch & php artisan serve "${@:2}"
    elif [ $1 = "start" ]
    then
        docker-compose up "${@:2}"
    elif [ $1 = "stop" ]
    then
        docker-compose down "${@:2}"
    elif [ $1 = "artisan" ]
    then
        docker exec -it rounds_api_1 php artisan "${@:2}"
    else
        docker exec -it rounds_api_1 "$@"
    fi
}

echo "rounds cli installed successfully!"