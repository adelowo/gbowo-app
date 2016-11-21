<?php

namespace App\Http\Controller\User;

use App\Http\Controller\BaseController;
use Slim\Http\Request;
use Slim\Http\Response;

class PaymentController extends BaseController
{

    public function index(Request $request, Response $response)
    {
        return $this->view->render($response, "dashboard/payment.html.twig", [
            'csrf_field' => \App\generate_csrf_form_fields($request, $this->container->get('csrf'))
        ]);
    }

    public function charge(Request $request, Response $response, array $args)
    {
        $adapterName = $args['adapter'];

    }
}
