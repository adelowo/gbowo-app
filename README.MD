### Gbowo App


A slimphp app that shows the [gbowo](https://github.com/adelowo/gbowo) library for paystack and amplifypay in a ***real*** app.

> THIS IS A `FAKE TEST APP` THAT ALLOWS YOU ***BUY*** THE IMAGE OF SOME OF MY FAVORITE PHPers. This is an app to try out the gbowo library with **YOUR OWN API KEYS**.

### Quick Usage

Here is the [file,PaymentController](https://github.com/adelowo/gbowo-app/blob/develop/src/Http/Controller/User/PaymentController.php) you should checkout to see how it was done. Or better still checkout this [commit](https://github.com/adelowo/gbowo-app/commit/17a9f37d2ff25d4a19a996bee80d3a78dbf39e71) that details everything.

### I Want to try out the app

- Pull the project

```bash
git clone git@github.com:adelowo/gbowo-app.git
```

- Install the dependencies 

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

> The sqlite db has been populated with some PHPers. All you have to do is create an account and test the app out
