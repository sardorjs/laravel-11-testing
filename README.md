# Setup | Laravel 11 Project in Docker

Follow the steps below to set up and run this Laravel 11 Project in Docker.

## Prerequisites

Ensure you have the following installed on your system:
- Docker
- Docker Compose
- Git

## 1. Clone the Repository

Start by cloning the repository to your local machine:

```bash
git clone https://github.com/sardorjs/laravel-11-testing.git
cd laravel-11-testing
```

## 2. Copy the `.env` File

Create the environment configuration by copying the example `.env` file:

```bash
cp .env.example .env
```

### Configure Database Credentials

Open the `.env` file and update the following variables with your database information:

```env
DB_DATABASE=laravel
DB_USERNAME=project_user
DB_PASSWORD=14ufFIjgfoB32fkS3
DB_ROOT_PASSWORD=549JryqIfS483FG
```

## 3. Start the Containers

Bring up the containers to initialize the environment:

```bash
make up
```

## *3.2. Troubleshoot with error during connect:

If you get the errors similar to:

```
docker compose up -d --build
error during connect: Get "http://%2F%2F.%2Fpipe%2FdockerDesktopLinuxEngine/v1.46/containers/json?all=1&filters=%7B%22label%22%3A%7B%22com.docker.compose.config-hash%22%3Atrue%2C%22com.docker.compose.project%3Dlaravel-11-websocket%22%3Atrue%7D%7D": open //./pipe/dockerDesktopLinuxEngine: The system cannot find the file specified.
make: *** [Makefile:26: up] Error 1
```

- you should launch the **Docker Desktop** in your computer

## *3.1. Troubleshoot with run containers

If you get the errors similar to:

```
 => ERROR [app internal] load build context                                                                                                                                                                                  108.5s
 => => transferring context: 108.71MB                                                                                                                                                                                        108.1s 
------
 > [app internal] load build context:
------
failed to solve: archive/tar: unknown file mode ?rwxr-xr-x
```
- you should delete the **node_modules** folder, and vendor if exists


## 4. Install Dependencies

Once the containers are running, enter the container and install the PHP dependencies:

```bash
make cli
composer install
```

## *4.1. Troubleshoot with composer

If you get the errors similar to:
- Install of fakerphp/faker failed
- Could not delete /var/www/vendor/composer/9b9bafa4/FakerPHP-Faker-bfb4fe1/src/Faker/Provider:

You should use this as solution:

```
make cli
cd /root/.composer
rm -rf cache
nano config.json
{
  "config": {
	  "process-timeout":      600,
	  "preferred-install":    "dist",
	  "github-protocols":     ["https"]
  }
}

Save it and try one more time: composer install
*maybe in new Git Bash terminal window
```

## 5. Set Directory Permissions

Ensure the `storage` and `bootstrap/cache` directories are writable by running:

```bash
make cli
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

## 6. Generate Application Key

Generate the application key:

```bash
make cli
php artisan key:generate
```

## 7. Cache Configurations

Update the `.env` variables in the configuration file:

```bash
make config-clear
```

## 8. Run Database Migrations

Apply the migrations to set up the database schema:

```bash
make cli
php artisan migrate
```

## 9. Access the Application

The application should now be accessible at [http://localhost](http://localhost).

## *10. Xdebug Installation

- all steps are located in: `./INSTALLATION_XDEBUG_PHPSTORM.md` file


## Additional Notes

- To stop the application, run:

```bash
make down
```

- If you need to rebuild the containers, use:

```bash
make up-force
```

- Use make help to see a list of all available commands with descriptions:

```bash
make help
```

That's it! Your Laravel project should now be running smoothly.
