## Парсинг валют
1. Установить GIT и склонировать репозиторий
```
git clone git@github.com:developer-r/test-app.git
```
2. Установить PHP и composer
3. Перейти в проект и установить пакеты
```
composer install
```
4. Создать в корне проекта файл .env и скопировать в него содержимое .env.example
5. Сгенерировать APP KEY
```
php artisan key:generate
```
6. Установить docker и docker-compose
7. Установить контейнеры 
```
docker-compose up -d
```
8. Зайти в bash проекта
```
docker-compose exec app bash
```
9. В bash установить миграции
```
php artisan migrate
```
10. Запустить команду с парсингом
```
php artisan parse:currencies
```
11. Запустить воркер очередей
```
php artisan queue:work --queue=parse_currencies
```
12. Через браузер перейти по url либо установить postman и постучать на url с параметрами date и code
```
http://127.0.0.1:8080/api/currencies/difference?date=23.09.2023&code=EUR
```
