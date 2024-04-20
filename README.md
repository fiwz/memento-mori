# CRUD Family & API

Technical test for PT Javan Cipta Solusi

Author: Afiani Fauziah

## Installation

Before continue to the installation, please clone this repository first:

```bash
git clone git@github.com:fiwz/memento-mori.git
```

Please follow the instructions below:

1. Create `.env` file from `.env.example`
2. Set the database environment in `.env`
3. Install the project

```bash
composer install
php artisan key:generate
```

4. Generate the table and seeder

```bash
php artisan migrate
php artisan laravolt:indonesia:seed
```

5. Run application

```bash
php artisan serve
```

## Postman Collection

The Postman Collection can be found in `postman_collection/AfianiFauziah-PT Javan-NationMaps.postman_collection`

The environment of collection is in `EnvNationMaps.postman_environment`