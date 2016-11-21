<?php

namespace App\Http\Controller\User;

use App\Exception\NotFoundEntityException;
use App\Http\Controller\BaseController;
use App\Repository\ProductRepository;
use Slim\Http\Request;
use Slim\Http\Response;

class ProductsController extends BaseController
{

    public function index(Request $request, Response $response, array $args)
    {
        $templateName = '';
        $data = [];

        $productRepo = new ProductRepository($this->container->get("db"));

        if (0 === count($args)) {

            $templateName = "dashboard/products.html.twig";
            $data["products"] = $productRepo->all();

        } else {

            $templateName = "dashboard/singleproduct.html.twig";

            try {
                $data['product'] = $productRepo->findByMoniker($args['name']);

            } catch (NotFoundEntityException $e) {
                echo $e->getMessage();
                die(500);
            }
        }

        return $this->view->render($response, $templateName, $data);
    }
}
