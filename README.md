## Laravel Calculator
### Install And Start
```
docker build -t laravel-calculator . && docker run -it --name laravel-calculator laravel-calculator --scale 10
```
### Start
```
docker start laravel-calculator && docker exec -it laravel-calculator php artisan app:calculator --scale 10
```
---
### Run Test
```
vendor/bin/phpunit 
```
