@echo off

:: rounds-cli

echo all: %*
for /f "tokens=1,* delims= " %%a in ("%*") do set ALL_BUT_FIRST=%%b

IF NOT DEFINED "%1%" (
    ECHO Error! usage: rounds [start, stop, ...]
) ELSE IF "%1%"=="dev" (
    docker-compose up -d
    docker exec -it rounds_api_1 npm install
    docker exec -it rounds_api_1 php composer.phar install
    docker exec -it rounds_api_1 php artisan migrate
    npm run watch & php artisan serve "$%ALL_BUT_FIRST%"
) ELSE IF "%1%"=="start" (
    docker-compose up "$%ALL_BUT_FIRST%"
) ELSE IF "%1%"=="stop" (
    docker-compose down "$%ALL_BUT_FIRST%"
) ELSE IF "%1%"=="artisan" (
    docker exec -it rounds_api_1 php artisan "$%ALL_BUT_FIRST%"
) ELSE IF "%1%"=="artisan" (
    docker exec -it rounds_api_1 php artisan "%ALL_BUT_FIRST%"
) ELSE (
    docker exec -it rounds_api_1 "%*"
)