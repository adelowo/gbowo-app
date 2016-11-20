<?php

namespace App\Http\Middleware;

use App\Session;
use Slim\Http\Request;
use Slim\Http\Response;
use Interop\Container\ContainerInterface;

class RedirectIfAuthenticated
{

    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, callable $next)
    {
        /**
         * @var Session $session
         */
        $session = $this->container->get("session");

        if ($session->has(LOGGED_IN_USER) && $session->get("user")) {
            return $response->withRedirect(
                $this->container->get("router")
                    ->pathFor("dashboard.index")
            );
        }

        return $next($request, $response);
    }
}
