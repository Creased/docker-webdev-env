Development environment based on PHP-FPM, Nginx and MariaDB
===========================================================

## Informations ##

### File Structure ###

	data        # Directory linked to VirtualBox API
	├─── build  # Data used to build containers (i.e., DockerFile)
	├─── conf   # Configuration data used by services
	├─── data   # Raw data used by services
	└─── log    # Logs issued by services

## Setup ##

### Requirements ###

- [Docker](https://docs.docker.com/engine/installation/);
- [Docker Compose](https://docs.docker.com/compose/install/);
- Less time to prepare environment, more time to develop.

### Download ###

```bash
git clone https://github.com/Creased/docker-compose-nginx-fpm-mariadb.git dev-env
pushd dev-env/
```

### Build ###

Build of containers based on docker-compose.yml:

```bash
docker-compose pull
docker-compose build
```

## Start ##

To get it up, please consider using:

```bash
docker-compose create
docker-compose start
docker-compose up
```

When done, turn on your web browser and crawl:

 - [http://127.0.0.1/](http://127.0.0.1/): phpinfo() passed to PHP-FPM;
 - [http://127.0.0.1/index.html](http://127.0.0.1/index.html): static web page delivered directly by Nginx;
 - [http://127.0.0.1/adminer/](http://127.0.0.1/adminer/): Adminer authentication page to test MariaDB connection (default creds: **admin-app**:**app-admin**).

## Live display of logs ##

```bash
docker-compose logs --follow
```

## Run bash on container ##

Template:

```bash
docker-compose exec SERVICE COMMAND
```

Example:

```bash
docker-compose exec db bash
```

Then you will be able to manage your configuration files, debug daemons and much more...

