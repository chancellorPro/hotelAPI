<p align="center"><img src="https://www.hotel-khreschatyk.kiev.ua/en/assets/img/photo/home-slider2/hotel-khreschatyk-kiev-photo-002.jpg" width="500"></p>

<p align="center">
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## About Hotel tools

API for hotel room reservations.


## How to use

After clone project you should run commands:

```
composer install
```
```
php artisan key:generate
```
- then need to prepare the database on your host
```
php artisan migrate --seed
```
```
php artisan passport:install
```
- add this line to .env 
```
SWAGGER_VERSION=2.0
```
```
php artisan l5-swagger:generate
```
```
php artisan serve
```

Now go to [Swagger](http://127.0.0.1:8000/api/documentation) and test API endpoints
