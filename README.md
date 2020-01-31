# B-Locker API

_The official B-Locker API._


## Pre-requisites

- Web server (e.g. nginx/Apache)
- Composer (https://getcomposer.org)
- PHP >= 7.2.0
- BCMath PHP Extension
- Ctype PHP Extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension

(Or see: https://laravel.com/docs/6.x#server-requirements)


## Installation

1. Clone repo
1. Go to project root
1. Run `composer install`
1. `cp .env.example .env`
1. `php artisan key:generate`
1. `php artisan jwt:secret`
1. _Do not forget to change database config in `.env`_
1. _Do not forget to change e-mail config in `.env`_
1. _Do not forget to change HW_TUNNEL_BIN config in `.env`_


## Automated testing

For unit and feature tests, it is important that the database connection works.
The configuration can be set with a `.env.testing` file, which can be based on
`.env.example`.

Once the configuration is set up, tests can be run using:
```
clear; composer run tests
```
...which clears the terminal screen and runs the tests.
