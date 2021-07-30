# User Portal

## Documentation

This is a User Portal application for manage users

### This application build with

- [Laravel 6](https://laravel.com/docs/6.x) & VueJs
- MySql database
- Repository pattern implemented

## Requirements

- [Laravel requirements](https://laravel.com/docs/7.x/#server-requirements)
- [Composer](https://getcomposer.org)
- Terminal

## How to run this application

- Set up PHP Mysql environment
- Clone project : Choose any of the option
  - SSH &ensp;- git clone git@github.com:sumeshvasu/user-portal.git
  - HTTP - git clone https://github.com/sumeshvasu/user-portal.git
- cd user-portal
- run : composer install
- run : php artisan key:generate
- run : npm install
- run : npm run dev OR npm run watch
- run in new console : php artisan migrate && php artisan passport:install --force
