<?php

require_once "vendor/autoload.php";

use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as v;

define("APP_NAME", "Opensource Swag");
define("USER_TYPE", "user_type");
define("LOGGED_IN_USER", "logged");

$app = new \Slim\App();

\App\Session::getInstance()->start();

(new Dotenv\Dotenv(__DIR__))->load();

$container = $app->getContainer();

$container['session'] = function (\Interop\Container\ContainerInterface $container) {
    return \App\Session::getInstance();
};

$container['csrf'] = function (\Interop\Container\ContainerInterface $container) {
    return new \Slim\Csrf\Guard();
};

$container['auth'] = function (Interop\Container\ContainerInterface $container) {
    return new \App\Http\Middleware\Authenticate($container);
};

$container['is_authenticated_user'] = function (\Interop\Container\ContainerInterface $container) {
    return new \App\Http\Middleware\RedirectIfAuthenticated($container);
};

$container["db"] = function (\Interop\Container\ContainerInterface $container) {

    return \Doctrine\DBAL\DriverManager::getConnection(
        [
            "url" => "sqlite:///data/gbowo.sqlite"
        ],
        new \Doctrine\DBAL\Configuration()
    );
};

$container['view'] = function (\Interop\Container\ContainerInterface $container) {

    $view = new \Slim\Views\Twig('views'); //no fancy caching or crap here. Take this to production, thou mustn't!!!

    $basePath = rtrim(
        str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/'
    );

    $view->addExtension(new \Slim\Views\TwigExtension($container['router'], $basePath));


    return $view;
};

$app->add($container->get("csrf"));

$app->get("/", function (
    Request $request,
    Response $response
) use ($container) {

    return $response->withRedirect(
        $container->get("router")
            ->pathFor("dashboard.index")
    );

});

$app->get("/logout", function (
    Request $request,
    Response $response
) use ($container) {

    /**
     * @var \App\Session $session
     */
    $session = $container->get("session");

    $session->destroy();

    return $response->withRedirect(
        $container->get("router")->pathFor("app.login")
    );
})
    ->setName('app.logout');

$loginValidators = [
    "mail" => v::notBlank()->email(),
    "pass" => v::notBlank()
];

$isAuthenticatedMiddleware = $container->get("is_authenticated_user");

$app->get("/login", 'App\Http\Controller\Account\LoginController:show')
    ->setName("app.login")
    ->add($isAuthenticatedMiddleware);

$app->post("/login", 'App\Http\Controller\Account\LoginController:login')
    ->add($isAuthenticatedMiddleware)
    ->add(new \DavidePastore\Slim\Validation\Validation($loginValidators));

$registrationValidators = [
    "fullname" => v::notBlank()->alpha()->length(6, 50),
    "mail" => v::notBlank()->email(),
    "pass" => v::notBlank()
];

$app->get("/signup", 'App\Http\Controller\Account\RegisterController:show')
    ->setName("app.register")
    ->add($isAuthenticatedMiddleware);

$app->post("/signup", 'App\Http\Controller\Account\RegisterController:create')
    ->add($isAuthenticatedMiddleware)
    ->add(new \DavidePastore\Slim\Validation\Validation($registrationValidators));

$app->group("/dashboard", function () use ($app) {

    $app->get("/", 'App\Http\Controller\User\HomeController:home')
        ->setName("dashboard.index");

    $app->get("/products[/{name}]", 'App\Http\Controller\User\ProductsController:index')
        ->setName('products.index');

    $app->get("/payment/{productname}/start", 'App\Http\Controller\User\PaymentController:index')
        ->setName('app.charge');

    $app->post("/payment/{adapter}", 'App\Http\Controller\User\PaymentController:charge')
        ->setName('app.charge.adapter');

    $app->any("/payment/{adapter}/data", 'App\Http\Controller\User\PaymentController:getPaymentData')
        ->setName('app.payment.data');

})->add($container->get("auth"));

$app->run();
