#!/bin/bash

# rounds-cli install

function rounds() {
    if [ -z $1 ]
    then
        echo "Error! usage: rounds [start, stop, ...]"
    elif [ $1 = "dev" ]
    then
        docker-compose up -d
        winpty docker exec -it rounds_api_1 npm install
        winpty docker exec -it rounds_api_1 php composer.phar install
        winpty docker exec -it rounds_api_1 php artisan migrate
        npm run watch & php artisan serve "${@:2}"
    elif [ $1 = "start" ]
    then
        docker-compose up "${@:2}"
    elif [ $1 = "stop" ]
    then
        docker-compose down "${@:2}"

    elif [ $1 = "artisan" ]
    then
        winpty docker exec -it rounds_api_1 php artisan "${@:2}"
    else
        winpty docker exec -it rounds_api_1 "$@"
    fi
}

echo "roundsw cli installed successfully!"