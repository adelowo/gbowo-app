<?php

namespace App\Http\Controller\User;

use App\Entity\User;
use function Gbowo\generate_trans_ref;
use App\Exception\NotFoundEntityException;
use App\Http\Controller\BaseController;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Gbowo\GbowoFactory;
use Slim\Http\Request;
use Slim\Http\Response;

class PaymentController extends BaseController
{

    public function index(Request $request, Response $response, array $args)
    {
        try {

            $product = (new ProductRepository($this->container->get('db')))
                ->findByMoniker($args['productname']);

            return $this->view->render($response, "dashboard/payment.html.twig", [
                'csrf_field' => \App\generate_csrf_form_fields($request, $this->container->get('csrf')),
                'amount' => $product->getPrice()
            ]);

        } catch (NotFoundEntityException $e) {
            echo $e->getMessage();
            die(500);
        }
    }

    public function charge(Request $request, Response $response, array $args)
    {
        $adapterName = $args['adapter'];

        $email = $this->session->get("user")->getEmailAddress();

        $user = (new UserRepository($this->container->get("db")))
            ->findByEmail($email);

        $adapter = (new GbowoFactory())
            ->createAdapter($adapterName);

        return $response->withRedirect(
            $adapter->charge(
                $this->makePaymentRequestData($adapterName, $user, $request)
            )
        );
    }

    protected function makePaymentRequestData(string $adapterName, User $user, Request $request)
    {
        $data = [];

        $amountTobeCharged = $request->getParam('_amount');

            $uri = $request->getUri();

            $callbackUri = 'http://'.$uri->getHost().':'.$uri->getPort(); //to be concatenated with a generated named route


        if (GbowoFactory::PAYSTACK === $adapterName) {

            $data['name'] = $user->getFullName();
            $data['amount'] = $amountTobeCharged * 100;
            $data['email'] = $user->getEmailAddress();

            $data['reference'] = generate_trans_ref();
            $data['callback_url'] = $callbackUri.$this->container->get("router")->pathFor("app.payment.data", ["adapter" => "paystack"]);

        } else { //amplifypay

            $data['customerName'] = $user->getFullName();
            $data['customerEmail'] = $user->getEmailAddress();
            $data['Amount'] = (float)$amountTobeCharged;

            $data['redirectUrl'] = $callbackUri.$this->container->get("router")->pathFor("app.payment.data", ["adapter" => "amplifypay"]);

            $data['paymentDescription'] = "Buy a PHPer image";

            $data['transID'] = pow(random_int(0, 1000), 2);
        }

        return $data;

    }

    public function getPaymentData(Request $request, Response $response, array $args)
    {
        $adapterName = $args['adapter'];

        $adapter = (new GbowoFactory())
            ->createAdapter($adapterName);

        $reference = '';

        if (GbowoFactory::AMPLIFY_PAY === $adapterName) {
            $reference = $request->getQueryParam('tran_response');
        } elseif (GbowoFactory::PAYSTACK === $adapterName) {
            $reference = $request->getQueryParam('trxref');
        } else {
            throw new \Exception(
                "How you got here. GOD alone Knoweth"
            );
        }

        return $response->withJson(
            $adapter->getPaymentData($reference)
        );
    }
}
