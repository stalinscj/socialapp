# SocialApp


## _Una Red Social introductoria_

[SocialApp] es una Red Social muy básica, desarrollada para fortalecer los conocimientos en el desarrollo de aplicaciones Web.

[Ver Demo ↗](http://socialapp-ss.herokuapp.com)


## Características

- Desarrollo guiado por pruebas (TDD).
- Login y Registro de Usuarios
- Creación de Estados en Tiempo Real
- Creación de Comentarios en Tiempo Real
- Likes en Tiempo Real
- Administrar Solicitudes de Amistad
- Lista de Amigos


## Tecnologías

- [Laravel 8] - Laravel is a web application framework with expressive, elegant syntax.
- [Laravel Dusk] - Laravel Dusk provides an expressive, easy-to-use browser automation and testing API.
- [MySQL] - MySQL is the world's most popular open source database.
- [PostgreSQL] - The World's Most Advanced Open Source Relational Database.
- [PHP 7.3] - PHP is a popular general-purpose scripting language that is especially suited to web development.
- [PHPUnit] - PHPUnit is a programmer-oriented testing framework for PHP.

- [Axios] - Promise based HTTP client for the browser and NodeJS.
- [Bootstrap] - The world’s most popular framework for building responsive, mobile-first sites.
- [Laravel Echo] - Laravel Echo is a JS library that makes it painless to subscribe to channels and listen for events broadcast by server-side broadcasting driver.
- [Pusher JS] - Pusher Channels is a hosted WebSockets solution for building powerful realtime interactive apps.
- [Vue] - The Progressive JavaScript Framework.


## Instalación

### Requisitos

- Composer >= 1.10
- Git >= 2.11
- MySQL >= 5.7 o PostgreSQL >= 9.4
- NPM >= 6.14
- SQLite >= 3.8.8
- PHP >= 7.2.5
- BCMath PHP Extension
- Ctype PHP Extension
- Fileinfo PHP extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension

### Pasos

```sh
git clone https://github.com/stalinscj/socialapp.git
```

```sh
cd SocialApp
```

```sh
git checkout develop
```

```sh
composer install
```

(Si no se copió automáticamente luego de la instalación):

```sh
cp .env.example .env
```

(Si no se generó automáticamente luego de la instalación):

```sh
php artisan key:generate
```

Si no se tiene una BBDD creada, desde la CLI de MySQL o PostgreSQL:

```sh
CREATE DATABASE db_database;
```

Se necesitan las credenciales de Pusher. 

En el archivo .env configurar las siguientes variables:

```sh
APP_NAME=
APP_URL=

DB_CONNECTION=
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

BROADCAST_DRIVER=pusher

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=
```

```sh
cp .env .env.dusk.local
```

En el archivo .env.dusk.local configurar las siguientes variables:

```sh
DB_CONNECTION=
DB_HOST=
DB_PORT=
DB_DATABASE=db_database_test
DB_USERNAME=
DB_PASSWORD=
```

```sh
php artisan migrate
```

```sh
npm install
```

```sh
npm run dev
```

```sh
php artisan test
```

```sh
php artisan dusk
```

```sh
php artisan serve
```

Desde un navegador ingresar a http://127.0.0.1:8000


[//]: # (Links) 

[SocialApp]: <https://socialapp-ss.herokuapp.com>
[Laravel 8]: <https://laravel.com>
[Laravel Dusk]: <https://laravel.com/docs/8.x/dusk>
[MySQL]: <https://www.mysql.com>
[PostgreSQL]: <https://www.postgresql.org>
[PHP 7.3]: <https://www.php.net>
[PHPUnit]: <https://phpunit.de>
[Axios]: <https://github.com/axios/axios>
[Bootstrap]: <https://getbootstrap.com>
[Laravel Echo]: <https://github.com/laravel/echo>
[Pusher JS]: <https://pusher.com>
[Vue]: <https://vuejs.org>
