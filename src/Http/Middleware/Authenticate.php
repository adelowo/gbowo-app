<?php

namespace App\Http\Middleware;

use App\Entity\User;
use App\Session;
use Slim\Http\Request;
use Slim\Http\Response;
use Interop\Container\ContainerInterface;

class Authenticate
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

        if (!$session->has(LOGGED_IN_USER) || (!$session->get("user") instanceof User)) {

            $uri = $request->getUri();
            $previousUri = $uri->getHost() . $uri->getPath() . $uri->getQuery();
            $session->put("previous_uri", $previousUri);

            return $response->withRedirect("/login")
                ->withStatus(301);
        }

        return $next($request, $response);
    }
}
