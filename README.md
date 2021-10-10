# Plan your Day

A demo Laravel application created using TALL Stack, for [larajam](https://larajam.dev/). No Controllers were created during the development of the Application. For Features, refer to the Home Page of the Application.


## Installation

Clone the repo locally:

```sh
git clone https://github.com/saurabh85mahajan/plan_your_day.git
cd plan_your_day
```

Install PHP dependencies:

```sh
composer install
```

Install NPM dependencies:

```sh
npm ci
```

Build assets:

```sh
npm run dev
```

Setup configuration:

```sh
cp .env.example .env
```

Generate application key:

```sh
php artisan key:generate
```

Create Mysql Database and configure the value in .env file.

Also create another Mysql Database for Testing and make sure the value matches in phpunit.xml


Run database migrations:

```sh
php artisan migrate
```

Run database seeder:

```sh
php artisan db:seed
```

Start the dev server

```sh
php artisan serve
```

Open the Link in Browser. You can use following User to Login

Username: user1@locahost.com

Password: password

To Run the Test using the following command

```sh
./vendor/bin/phpunit
```


Following Third Packages were used for development:

1. [ascsoftw/tall-crud-generator](https://github.com/ascsoftw/tall-crud-generator)

2. [christophrumpel/missing-livewire-assertions](https://github.com/christophrumpel/missing-livewire-assertions)

3. [nunomaduro/larastan](https://github.com/nunomaduro/larastan)