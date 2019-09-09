# emlparser  
Парсер писем в EML-формате в БД MySQL
## Требования
PHP7.0 и выше  
MySQL 5.5 и выше

## Установка:  
```
git clone https://github.com/jagoree/emlparser.git  
```
настройка подключения к БД в файле  
```
config/app.php  
```
секция `Datasources`

Начальный дамп БД
```
config/schema/projects-production.sql
```
Архив для проверки:
```
tmp/mailparsing.zip
```
Установить права 777 на папки
```
tmp
logs
```
Например, вирутальный хост **parser.local**  
Открываем в браузере http://parser.local, через форму добавляем файл архива, нажимаем "Загрузить".  
В случае успешного импорта покажет, сколько добавлено проектов, пользователей, постов.
