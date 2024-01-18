## Laravel Calculator
### Install And Start
```
git clone https://github.com/henry11996/laravel-calculator.git && cd laravel-calculator
docker build -t laravel-calculator . && docker run -it --name laravel-calculator laravel-calculator --scale 10
```
### Start
```
docker start laravel-calculator && docker exec -it laravel-calculator php artisan app:calculator --scale 10
```
---
### Run Test
```
docker start laravel-calculator && docker exec -it laravel-calculator vendor/bin/phpunit 
```
