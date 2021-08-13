# Feature flags service

Self hosted feature flags service

# Install in docker

1 . Clone repository

```
git clone https://github.com/antonshell/feature-flags.git
```

2 . Run containers
```
docker-compose up
```

3 . Install dependencies

```
docker-compose exec php-fpm composer install
```

4 . Setup env variables

```
cp .env .env.local
nano .env.local
```

Set ```APP_SECRET```, ```ROOT_TOKEN``` variables

5 . Apply database migrations

```
docker-compose exec php-fpm php bin/console doctrine:migrations:migrate
```

6 . Open in browser

[http://127.0.0.1:16580/](http://127.0.0.1:16580/) - Healthcheck

# Usage

There is a Swagger API docs.

1 . Health check

```
curl --request GET \
  --url http://127.0.0.1:16580/
```

2 . Get feature value

```
curl --request GET \
  --url http://127.0.0.1:16580/feature/antonshell/demo/feature1/prod \
  --header 'Authorization: bearer demo_read_key'
```

3 . Manage features & environments

See Swagger API docs.

# Demo

There is a demo instance available: [https://feature-flags.antonshell.me](https://feature-flags.antonshell.me)

1 . Health check

```
curl --request GET \
  --url https://feature-flags.antonshell.me/
```

2 . Get feature value

```
curl --request GET \
  --url https://feature-flags.antonshell.me/feature/antonshell/demo/feature1 \
  --header 'Authorization: bearer demo_read_key'
```

# Tests

1 . Init testing environment

```
docker-compose exec php-fpm composer init-testing-environment
```

2 . Run tests

Local environment:
```
composer test
```

Docker environment:
```
docker-compose exec php-fpm composer test
```

# Codestyle

1 . Fix codestyle

Local environment:
```
composer cs-fixer src
```

Docker environment:
```
docker-compose exec php-fpm composer cs-fixer src
```

# Setup xdebug (Docker)

[https://blog.denisbondar.com/post/phpstorm_docker_xdebug](https://blog.denisbondar.com/post/phpstorm_docker_xdebug)
