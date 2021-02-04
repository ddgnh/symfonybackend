## Installation

First, clone this repository.

Then, create your environment by editing `.env` and save as `.env.local` or you can use OS's environment variable or use [Symfony Secrets](https://symfony.com/doc/current/configuration/secrets.html). Create your JWT passphrase on the JWT_PASSPHRASE key.
Make sure to adjust the credentials on the environment for the Docker. You can find inside docker-compose.yaml file

Create the docker environment:
```bash
$ docker-compose up -d
```

Generate Private and public key for JWT Token:

On Linux:

```bash
$ docker-compose exec php sh -c '
    set -e
    apk add openssl
    mkdir -p config/jwt
    jwt_passphrase=${JWT_PASSPHRASE:-$(grep ''^JWT_PASSPHRASE='' .env | cut -f 2 -d ''='')}
    echo "$jwt_passphrase" | openssl genpkey -out config/jwt/private.pem -pass stdin -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
    echo "$jwt_passphrase" | openssl pkey -in config/jwt/private.pem -passin stdin -out config/jwt/public.pem -pubout
    setfacl -R -m u:www-data:rX -m u:"$(whoami)":rwX config/jwt
    setfacl -dR -m u:www-data:rX -m u:"$(whoami)":rwX config/jwt
'
```

On Windows:

```bash
> docker-compose exec php /bin/sh
```

You will enter docker shell, then run (line by line, do not paste it as a whole):

```bash
set -e
apk add openssl
mkdir -p config/jwt
export jwt_passphrase=${JWT_PASSPHRASE:-$(grep ''^JWT_PASSPHRASE='' .env | cut -f 2 -d ''='')}
echo "$jwt_passphrase" | openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096 --pass stdin
echo "$jwt_passphrase" | openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout --passin stdin
setfacl -R -m u:www-data:rX -m u:"$(whoami)":rwX config/jwt
setfacl -dR -m u:www-data:rX -m u:"$(whoami)":rwX config/jwt
exit
```

Install dependency

```bash
$ docker-compose exec php composer install
```


### Fresh Install
For new installation, do the following, existing installation can proceed to migration.

```bash
$  docker-compose exec php bin/console doctrine:database:drop --force
$  docker-compose exec php bin/console doctrine:database:create
$  docker-compose exec php bin/console make:migration
$  docker-compose exec php bin/console doctrine:migrations:migrate --no-interaction
```

Prepopulate the database with default content:
```bash
$  docker-compose exec php bin/console doctrine:fixtures:load --no-interaction
```

### Migration
For existing database, do migration check and make migration if needed. if you have installed this before, you can make migration from previous release:
```bash
$  docker-compose exec php bin/console make:migration
```

Lastly, run the migration:
```bash
$  docker-compose exec php bin/console doctrine:migrations:migrate --no-interaction
```

Now your app are ready to use:

Landing page: [https://localhost/](https://localhost/)

API Endpoint and Docs: [https://localhost/api](https://localhost/api)

Admin page: [https://localhost/admin](https://localhost/admin)

default credentials:
```bash
root:toor
admin:admin
upk_pusat:upk_pusat
```

## Test

Unit testing also available with the following command:

```bash
$ docker-compose exec php bin/phpunit
```

