# smartread

Запуск Docker'а:
`docker-compose --build -d`

Вход в контейнер:
`docker-compose exec php sh`

Запуск миграций:
`php artisan migrate`

Запуск силирования:
`php artisan db:seed`

Запуск консольной команды подсчёта:
`php artisan app:groups-analyse-users`
