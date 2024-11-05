## Развернуть проект
Создать .env файл (за основу можно взять .env.example)
Создать пользователя phpuser:phpgroup и дать права на папки
/var/run/php
/var/www/html

Выполнить команды
```
$ docker compose up --build --detach --remove-orphans
$ docker compose exec php composer install
```

или

```
$ make create
```