<?php


namespace App\Http\Controller;


use App\Session;
use Interop\Container\ContainerInterface;
use Slim\Views\Twig;

abstract class BaseController
{

    /**
     * @var \Interop\Container\ContainerInterface
     */
    protected $container;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var Twig
     */
    protected $view;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->session = $this->container->get("session");
        $this->view = $this->container->get("view");
    }
}
