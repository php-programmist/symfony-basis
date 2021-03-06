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
Создать БД, выполнить миграции и фикстуры:
```
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```
## Создать пользователя:
С помощью команды можно создать пользователя **admin** с паролем **password**
```
php bin/console user:create admin password
```
Изменить пароль можно так:
```
php bin/console user:change-password admin new_password
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
yarn encore build
```

## Ленивая загрузка изображений и видео
Обычные изображения:
```
<img {{ lazy_load('/img/menu.jpg',['some-class']) }} alt="Menu">
```
Фоновые изображения:
```
<div {{ lazy_load_bg('/img/works-bg.png',['some-class']) }}></div>
```
Изображения слайдера Slick:
```
<img {{ slick_lazy_load(asset('img/bslider1/1.jpg')) }} alt="*">
```
Видео HTML5:
```
<video muted="" autoplay="" loop="" preload="auto" class="lazy">
    <source data-src="{{ asset('video/video.mp4') }}" type="video/mp4">
</video>
```
Видео YouTube:
1. Раскомментировать в assets/js/app.js строку:
```
import './libs/lazy_youtube'
```
2. в Twig:
```
{{ lazy_youtube('https://youtu.be/yfTLx-fcJio') }}
```

## Настройка отправки почты в dev-режиме
Для тестирования отправки почты в процессе разработки можно использовать Gmail или Mailtrap
### Отправка через Gmail:
Gmail-транспорт уже установлен. Нужно лишь в **.env.local** добавить строку для настройки подключения:
```
MAILER_DSN=gmail://GMAIL_LOGIN:GMAIL_PASSWORD@default
```
Вместо **GMAIL_LOGIN** и **GMAIL_PASSWORD** указать свои логин и пароль от аккаунта Gmail. В настройках этого аккаунта нужно разрешить [использование небезопасных приложений](https://myaccount.google.com/lesssecureapps)
### Отправка через MailTrap:
Сервис MailTrap не производит реальную отправку писем, а лишь собирает их в виртуальном почтовом ящике, который доступен только Вам
1. Необходимо зарегистрировать аккаунт в [MailTrap](https://mailtrap.io)
2. Выбрать бесплатный план
3. Перейти в настройки ящика и взять от туда логин и пароль
4. Добавить строку подключения в **.env.local**:
```
MAILER_DSN=smtp://LOGIN:PASSWORD@smtp.mailtrap.io:25
```
Вместо **LOGIN** и **PASSWORD** указать свои логин и пароль из настроек ящика.
### Обработчик формы обратной связи:
Роут:
```
POST mail/callback/consultation
```
В админке необходимо указать получателей и отправителей в параметрах, начинающихся с **mail.**

