<?php

namespace App\Http\Controller\User;

use App\Http\Controller\BaseController;
use Slim\Http\Request;
use Slim\Http\Response;

class HomeController extends BaseController
{

    public function home(Request $request, Response $response)
    {
        return $this->view->render($response, "dashboard/index.html.twig");
    }
}

