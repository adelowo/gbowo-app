<?php

namespace App\Http\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;
use Interop\Container\ContainerInterface;

class Authenticate
{

    public function __construct(ContainerInterface $container)
    {

    }

    public function __invoke(Request $request, Response $response, callable $next)
    {
        /**
         * @var Session $session
         */
        $session = $this->container->get("session");

        if (!$session->has(LOGGED_IN_USER) || empty($request->getUri()->getUserInfo())) {
            $uri = $request->getUri();
            $previousUri = $uri->getHost() . $uri->getPath() . $uri->getQuery();

            $session->put("previous_uri", $previousUri);

            return $response->withJson([
                "denied" => true
            ])->withStatus(401);
        }

        return $next($request, $response);

    }
}
