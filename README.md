<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>


## VlOlL1 based on Laravel Requirements

This Web Project is compatible with Laravel 11.9 and requires PHP 8.2.

## Naklonujte repozitár:
```bash
git clone https://github.com/oldopo/VlOlL1.git
cd VlOlL1
```

## skopírujte .env.example na .env , prípadná kontrola obsahu .env:
```text
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=simple_api
DB_USERNAME=root
DB_PASSWORD=heslo
```

## uistite sa, ze Vám beži Docker Desktop<br>
## pripojte sa k docker containeru nainštalujte závislosti:
```bash
docker-compose exec app bash
composer install
exit
```

Spustenie programu a kontrola pripojenia:
```bash
docker-compose up -d --build
```
spustenie migrácií:
```bash
docker-compose exec app php artisan migrate
```
spustenie testov:
```bash
docker-compose exec app php artisan test
```
Postman testovanie, import collection z priečinka a manuálna kontrola:
```text
/postman-collection/VlOlL1.postman_collection.json
```

ukončenie aplikácie a testovania:
```bash
docker-compose down
```
