<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

# Achievements & Badges APIs

This application aims to simulate a achievement system. It was used PHP Laravel Framework (8.40) + MySQL + Docker.

<!-- ## Live Demo

You can get live demo in the following link: https://tbnb-api.herokuapp.com -->

## Installation

You must have Docker installed in your environment, all the docker config is inside `.docker` folder.

> PS: you need to free up ports 8080 and 3306 to run the services by default, but you can change it in the `.env` located in `.docker` folder.

Then, clone the repo and copy the necessary files.

```
cp .docker/.env.example .docker/.env
cp .docker/mysql/docker-entrypoint-initdb.d/createdb.sql.example .docker/mysql/docker-entrypoint-initdb.d/createdb.sql
cp .env.example .env
```

Then enter the `.docker` directory and start the containers:

```
docker-compose up -d --build
```

It will run a startup with following steps:

```
# Install the Dependencies
composer install

# Clear All Caches
php /var/www/app/artisan optimize:clear

# Starts Queue
nohup php /var/www/app/artisan queue:listen &

# Run Migrations and Seeders
php /var/www/app/artisan migrate --seed
```

You can get a realtime pipeline logs by running:

```
docker logs --tail 1000 -f <<CONTAINER_ID>>
```

To get container id, just run the following:

```
docker ps
```

and get column "CONTAINER ID".

Now the application is running on [http://localhost:8080](http://localhost:8080).

## Full Docs

You can find the Laravel full docs [here](https://laravel.com/docs/8.x).

## Testing

The application works with default tests Laravel pattern (_tests/Feature_ and _tests/Unit_).

To test, run the following:

```
php artisan test
```

Btw, it have a dedicated tests database, you can find the credentials on `.docker/mysql/docker-entrypoint-initdb.d/createdb.sql.example` if you want to run the tests on side database.

For that you just need to create a `.env.testing` file and set the database credentials in it.
