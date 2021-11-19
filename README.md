[![GitHub issues](https://img.shields.io/github/issues/madcoda9000/starterApp?color=blue&style=for-the-badge)](https://github.com/madcoda9000/starterApp/issues)
[![GitHub stars](https://img.shields.io/github/stars/madcoda9000/starterApp?style=for-the-badge)](https://github.com/madcoda9000/starterApp/stargazers)
[![GitHub forks](https://img.shields.io/github/forks/madcoda9000/starterApp?style=for-the-badge)](https://github.com/madcoda9000/starterApp/network)
[![GitHub license](https://img.shields.io/github/license/madcoda9000/starterApp?color=blue&style=for-the-badge)](https://github.com/madcoda9000/starterApp/blob/main/LICENSE)

# Starter Application

This is a starter application written in php,css and javascript.
It is inspired by this tutorial: https://codeofaninja.com/2018/09/rest-api-authentication-example-php-jwt-tutorial.html

Please see credits.txt for a list of used libraries in this project.

### Goals

The main goal of this project is to have a main web application providing the following features:

1. usermanagement (signup, modify, delete)
2. group management
3. secure authentication
4. Two factor authentication
5. fast frontend using a secure api
6. easy customizing (Database, App title, app description, frontend)

### Features

1. secure backend api using jwt and openssl
2. optional two factor authentication with robthree/twofactorauth
3. data storage using mysql
4. nice customizeable frontend using bootstrap 5, jquery, popper.js and jquery-confirm with selectable theme switcher
5. customizeable app title & description using config file
6. config section for using smtp auth
7. config section for database settings
8. config section for application parameters

### Todo's

- make it possible to host the api on a seperate host (configureable api url)
- use j4mie/idiorm ORM class for database actions instead of direct pdo
- implement goups and admin role
- implement admin interface for manging user accounts
- implement admin interface for managing groups
- create installation tutorial

### Screenshots

![Login Page](/Documentation/login.png)

![MFA login](/Documentation/mfa-login.png)

![MFA settings](/Documentation/mfa-settings.png)

![account settings](/Documentation/account-settings.png)

![themes](/Documentation/themes.png)

#### License
This code is published under MIT license. Used libraries in this project might using other licenses. Please take a look at [credits.md](/credits.md) for a overview.