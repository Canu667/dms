# Document Management System

## Intro

This is just MVP of simplified Document Management System application consisting of 2 separate applications:

* api (Symfony 4 REST Api application)
* frontend (simple React application without Redux)

It allows user to simply upload, view list of uploaded files and delete files.

## TODOs

As next steps to improve overall app quality and security:

* properly configure nelmio/NelmioCorsBundle bundle
* install and configure lexik/LexikJWTAuthenticationBundle in api and implement it on the frontend app side
* implement pagination on the api/documents endpoint
* implement more filtering options on api endpoints
* implement downloading endpoint (e.g. streaming)
* write more tests (unit/functional) for even better code coverage of the API application
* prepare docker setup with docker-compose
* prepare automated installation script (e.g. Symfony's command, or phing build script)
* write some tests for frontend React app (in Jest)
* improve frontend application, make it look better

## Starting project

First clone the repo and in terminal change the location to where you just cloned it.

### Api

Go to API directory and install dependencies with composer

`cd api && composer install`

Configure the application by copying .env.dist to .env and changing variables there as desired

`cp .env.dist .env` 

After you configured your database connection in .env you need to create db schema:

`php bin/console doctrine:schema:create`

And run migrations:

`php bin/console doctrine:migrations:migrate`

Finally start the local server:

`php bin/console server:run`

It should give result like this:

```
 [OK] Server listening on http://127.0.0.1:8000


 // Quit the server with CONTROL-C.

PHP 7.2.8 Development Server started at Sun Jul 29 18:55:30 2018
Listening on http://127.0.0.1:8000
Document root is ...
Press Ctrl-C to quit.
```

This means that the API is already listening to requests under http://127.0.0.1:8000

#### Running Tests

Configure the environment for running tests by copying phpunit.xml.dist to phpunit.xml
and modifying whatever is neccessary, including test db connection:

`cp phpunit.xml.dist phpunit.xml`

Run tests:

`bin/phpunit`

#### Running Code Quality Checks

You can also verify general code quality by running GrumPHP with preconfigured checks like PHPMD, PHPCS etc.
Simply run:

`vendor/bin/grumphp run`

### Frontend

#### Prerequisites

Make sure you have [yarn](https://yarnpkg.com/lang/en/docs/install) installed.

Go to the frontend directory (if you're still in the api directory `cd ../fontend`) of the project and run:

`yarn install`

Copy the config.js.dist to config.js and configure the backendApi property to point to the api server started:

`cp config.js.dist config.js`

And start the frontend app:

`yarn start`

By default dev server would be started on http://127.0.0.1:9009

If you have problems starting it because of port being already in use,
please adjust server config in webpack.config.js
