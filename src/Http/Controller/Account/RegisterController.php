<?php

namespace App\Http\Controller\Account;

use App\Entity\User;
use App\Http\Controller\BaseController;
use App\Repository\UserRepository;
use Doctrine\DBAL\DBALException;
use Slim\Http\Request;
use Slim\Http\Response;

class RegisterController extends BaseController
{

    public function show(Request $request, Response $response)
    {

        $errors = $this->session->get("errors");
        $classicError = $this->session->get("classic");

        \App\clear_all_errors_from_session();

        $this->session->remove("classic");

        return $this->view->render($response, "uac/register.html.twig", [
            "csrf_fields" => \App\generate_csrf_form_fields($request, $this->container->get("csrf")),
            "errors" => $errors,
            "classic_error" => $classicError
        ]);
    }

    public function create(Request $request, Response $response)
    {
        if ($request->getAttribute("has_errors")) {

            $errors = [];

            foreach ($request->getAttribute("errors") as $key => $value) {
                $errors[$key] = $value[0];
            }

            $this->session->put("errors", $errors);

            return $response->withRedirect(
                $this->container->get("router")->pathFor("app.register"),
                301
            );
        }

        try {

            $user = (new User())
                ->setPassword($request->getParam("pass"))
                ->setEmailAddress($request->getParam("mail"))
                ->setFullName($request->getParam("fullname"));

            $userRepo = new UserRepository($this->container->get("db"));

            if ($userRepo->add($user)) {

                $this->session->put(LOGGED_IN_USER, true);
                $this->session->regenerate();
                $this->session->put("user", $user);

                return $response->withRedirect(
                    $this->container->get("router")->pathFor("dashboard.index")
                );
            }

            $this->session->put("classic", "An error occurred");

            return $response->withRedirect(
                $this->container->get("router")->pathFor("app.register")
            );

        } catch (DBALException $e) {
            echo $e->getMessage();
        }
    }
}
