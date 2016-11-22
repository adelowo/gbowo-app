### Gbowo App

> WIP

A slimphp app that shows gbowo in a ***real*** app.

> THIS IS A `FAKE TEST APP` THAT ALLOWS YOU ***BUY*** THE IMAGE OF SOME OF MY FAVORITE PHPers. This is an app to try out the gbowo library with **YOUR OWN API KEYS**.


- Pull the project

```bash
git clone git@github.com:adelowo/gbowo-app.git
```

- Install the dependencies ((SlimPHP)[slim],(Gbowo)[gbowo], Doctrine DBAL ...)

```bash
cd gbowo-app
composer install
```

- Create and update the `.env` file

```bash
cp .env.example .env
vi .env #change as deem fit. Make sure to get your api keys from either paystack or amplifypay
```

- Start PHP's server

```bash
php -S localhost:8000
```


[slim](https://slimframework.com)
[gbowo](https://github.com/adelowo/gbowo)
