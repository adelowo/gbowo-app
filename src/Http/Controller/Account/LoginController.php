<?php

namespace App\Http\Controller\Account;

use App\Exception\NotFoundEntityException;
use App\Http\Controller\BaseController;
use App\Repository\UserRepository;
use Interop\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use function App\generate_csrf_form_fields;
use function App\clear_all_errors_from_session;


class LoginController extends BaseController
{

    public function show(Request $request, Response $response)
    {
        $errors = $this->session->get("errors");
        clear_all_errors_from_session();

        $classic = $this->session->get("classic");
        $this->session->remove("classic");

        return $this->view->render($response, "uac/login.html.twig", [
            "csrf_fields" => generate_csrf_form_fields($request, $this->container->get("csrf")),
            "errors" => $errors,
            "classic" => $classic
        ]);
    }

    public function login(Request $request, Response $response)
    {
        if ($request->getAttribute("has_errors")) {
            $this->session->put("errors", $request->getAttribute("errors"));

            return $response->withRedirect(
                $this->container->get("router")->pathFor("app.login")
            );
        }

        try {

            $userRepository = new UserRepository($this->container->get("db"));
            $user = $userRepository->findByEmail($request->getParam("mail"));

            if (password_verify($request->getParam("pass"), $user->getPassword())) {
                $user->setPassword(null); // Still nullify this, even though it is not persisted to session. Users' password cannot be hanging around.

                $this->session->regenerate();
                $this->session->put(LOGGED_IN_USER, true);
                $this->session->put("user", $user);

                $uri = $request->getUri();
                $previousUri = $uri->getPath() . $uri->getQuery();
                $dashboardUri = $this->container->get("router")->pathFor("dashboard.index");

                return $response->withRedirect(
                    ($previousUri !== "/login") ? $previousUri : $dashboardUri
                );
            }

        } catch (NotFoundEntityException $e) {

        }

        return $this->sendInvalidCredentialsResponse($request, $response);
    }

    protected function sendInvalidCredentialsResponse(Request $request, Response $response)
    {
        $this->session->put("classic", "The login could not be authenticated because of invalid credentials");

        return $response->withRedirect(
            $this->container->get("router")->pathFor("app.login")
        );
    }
}
