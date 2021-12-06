## Getting started

### Startup

To bring the project up use the following command:

```bash
make up
```

The first time you run this command, it will accomplish the followings:

- install composer dependencies
- build application containers
- generate application encryption key
- migrate and seed the database
- install and compile frontend dependencies

Once all of the above is completed, you can access the application in your web browser at: [http://localhost](http://localhost).

### Tear down

To stop all containers use the following command:

```bash
make down
```

### Build

To migrate the database, build and compile frontend run the following command:

```bash
make build
```

### Refresh database

You can also utilize a fresh build command that will refresh and reseed the database:

```bash
make fresh
```

### Backend watch

In order to automatically rerun PHPUnit tests when source code changes use:

```bash
make watchback
```

### Frontend watch

In order to automatically rebuild frontend when resources change use:

```bash
make watchfront
```

## Digging deeper

### Laravel Sail

This boilerplate utilizes [Laravel Sail](https://laravel.com/docs/8.x/sail), a built-in solution for running Laravel project using Docker.

By default, Sail commands are invoked using the `vendor/bin/sail` script:

```bash
./vendor/bin/sail up
```

However, instead of repeatedly typing `vendor/bin/sail` to execute Sail commands, you may wish to configure a Bash alias that allows you to execute Sail's commands more easily:

```bash
alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'
```

### Using Sail

Sail provides a convenient way to run various commands against your application such as arbitrary PHP commands, Artisan commands, Composer commands, and Node / NPM commands.

To run desired commands within the context of a container you should execute those commands using Sail:

```bash
# Running commands locally...
php artisan queue:work
composer require laravel/sanctum
npm run prod

# Running commands within Laravel Sail...
sail artisan queue:work
sail composer require laravel/sanctum
sail npm run prod
```

### Container CLI

Sometimes you may wish to start a Bash session within your application's container. You may use the shell command to connect to your application's container, allowing you to inspect its files and installed services as well execute arbitrary shell commands within the container:

```bash
sail shell

sail root-shell
```

To start a new Laravel Tinker session, you may execute the tinker command:

```bash
sail tinker
```

### Further reading

More information about Laravel Sail can be found on it's official documentation page: [https://laravel.com/docs/8.x/sail](https://laravel.com/docs/8.x/sail)


## Github workflow

To test github workflow locally you need to install [https://github.com/nektos/act](https://github.com/nektos/act).

To run github workflow run:

```
make act
```
