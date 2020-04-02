# Scores test project

This project is for the architecture and code solutions show purpouses only.

## Functionality

The project contains of two parts. 

* Fetching the data from third party service and storing
them in the Mongo database.
* Exposing data with the REST Api

## Instalation

This project is shiped with the docker compose setup.

To get the containers run and install all dependencies, use:

``docker-compose up``

``docker-compose exec php bin/php composer install``

## Usage

### Fetching data from third party service

To populate the database, use the following command:

``docker-compose exec php bin/php app:fetch-scores``

### Accessing data by the API

Use the following url to access data (the sort param can be either `date` or `score` - for default it is a `date`)

``http://localhost:8083/api/scores?sort=date``

## Documentation of API

Documentation of the REST Api is available at the URL:

``http://loclahost:8083/api/doc``

## Testing

To run the tests, use:

``docker-compose exec php bin/phpunit``
