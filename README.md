# Simple Symfony Docker starter

You only need `docker` and `docker-compose` installed

## Start server

The following command will start the development server at URL http://127.0.0.1:8000/:

```bash
./dc up # dc is a wrapper around docker-compose that allows services to be run under the current user
```

## Useful commands

```bash
# Composer is installed into the php container, it can be invoked as such:
./dc exec php composer [command]

# This is a Symfony Flex project, you can install any Flex recipe
./dc exec php composer req annotations

# Symfony console
./dc exec php bin/console

# Start the MySQL cli
./dc exec mysql mysql symfony

# Stop all services
./dc down

# Clear cache
./dc exec php bin/console cache:clear
```

# Usage

## Start the app and launch automated tests

* Copy paste the `.env` to `.env.local`
* Init the app with : `./dc up ` 
* Create database and schema for prod and test :
```bash
./dc exec php bin/console --env=test doctrine:database:create && ./dc exec php bin/console --env=test doctrine:schema:create
./dc exec php bin/console doctrine:database:create && ./dc exec php bin/console doctrine:schema:create
```
* Launch phpunit tests : `./dc exec php ./vendor/bin/phpunit`

* Create Link : http://127.0.0.1:8000/api/v1/link, method=POST, body=
 ```{
  "url": "https://www.flickr.com/photos/adrianafuchter/10336601893/in/photostream/"
  }
  ```
* List link : http://127.0.0.1:8000/api/v1/links?page=1, method=GET
* Delete link : http://127.0.0.1:8000/api/v1/links/{id}, method=DELETE

# To do

## Versionning
* Version API with accept header in place of url
* Add since() configuration from attributes versioning

## Cache
* Make List API cacheable by using redis cache

## Authentication
* Add authentication mechanism for API

## Pagination
* For list API : remove unnecessary information added by KnpPaginatorBundle when list is rendered in JSON

## Tests
* Add unit tests
* For LinkBuilderTest emulate behaviour of remote. Do not call 
remote url but use a fixture page and use internally this page to test the url.
* Add functional test for API

## Documentation
* Complete the API documentation
* Install assets for nelmio api doc bundle