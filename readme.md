# Основа для разработки сайта на Symfony 5
## Минимальные требования:
- PHP 7.3  и выше
- MySQL 5.6 и выше

## Установка:

- Склонировать репозиторий
- В корневой дирректории проекта создать файл ".env.local". В файл добавить следующие строки:
```
APP_ENV=dev
DATABASE_URL=mysql://user:password@127.0.0.1:3306/db_name
```
- Заменить **user**, **password**, **db_name** данными для подключения к локально БД
- В корневой папке проекта через терминал выполнить:
```
composer install
```
Создать БД и выполнить миграции:
```
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```
## Создать пользователя в БД:
В таблице user
 - username - Указать произвольное имя пользователя
 - roles - ["ROLE_ADMIN"]
 - password - указать хэш пароля.
Получить хэш пароля можно с помощью утилиты:
```
php bin/console security:encode
```
## Работа с Фронт-эндом (JS и CSS)
Сборка осуществляется посредством [WebPack](https://webpack.js.org/). Для настройки которого используется [Encore](https://symfony.com/doc/current/frontend.html)
1. Установить глобально Node.js
2. Установить зависимости - в папке проекта выполнить:
```
yarn install
```
3. Запустить сервер разработки с автоматической пересборкой:
```
yarn encore dev --watch
или
yarn watch
```
4. JS и CSS (SCSS) хранятся в папке /assets/
5. Подключение JS осуществляется в файле assets/js/app.js с помощью синтаксиса импорта:
```
import './global/callback-popup'
```
6. Подключение SCSS осуществляется в файле assets/scss/app.scss с помощью синтаксиса импорта:
```
@import "/global/callback-popup";
```
7. Настройка WebPack в файле - webpack.config.js
8. Сборка на продакшене:
```
yarn encore production
```
