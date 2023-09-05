таблицы для базы в файле sum1_test.sql

# Задание 1.

php 7.4
нужно указать конфиг соединения mysql
config/mysql.php

POST /users/ - создание пользователя
PUT  /users/{username}/ - обновление данных
GET  /users/{username}/ - инф-ия о пользователе


# Задание 2.

SELECT t.id, max(case when (t.code='name') then t.value else NULL end) as 'name',
max(case when (t.code='login') then t.value else NULL end) as 'login'
FROM `objects` AS t
GROUP BY t.id

# Задание 3.

файл скрипта: log_prepare.py
файл nginx лога: site.com-access.log