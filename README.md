# B-Locker API Server

_The official B-Locker API Server._


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
