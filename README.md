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

### Fix host file ###

To get this setup ready, please make sure to fill-in your host file to catch these domains (see [howtogeek](http://www.howtogeek.com/howto/27350/beginner-geek-how-to-edit-your-hosts-file/)):

 - app.dev: Production web project;
 - gitlab.dev: Git repositories;
 - {lab.dev, local.dev}: Web projects laboratory.

Your host file should look like (depending to your system):
```
127.0.0.1 localhost local.dev gitlab.dev lab.dev app.dev
```

### Browse your applications ###

When done, turn on your web browser and crawl:

 - [http://app.dev/](http://app.dev/): phpinfo() passed to PHP-FPM;
 - [http://app.dev/index.html](http://app.dev/index.html): Static web page delivered directly by Nginx;
 - [http://app.dev/adminer/](http://app.dev/adminer/): Adminer authentication page to test MariaDB connection (default creds: **admin-app**:**app-admin**);
 - [http://gitlab.dev/](http://gitlab.dev/): Gitlab-CE setup page proxified by Nginx;
 - [http://lab.dev/](http://lab.dev/): Projects list in laboratory.

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

