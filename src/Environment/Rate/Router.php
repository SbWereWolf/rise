<?php

namespace Environment\Rate;


use BusinessLogic\Rate\Factory;
use BusinessLogic\Session\Manager;
use Environment\Basis\Routing;
use Slim\Http\Request;
use Slim\Http\Response;

class Router extends \Environment\Basis\Router
{
    public function settingUpRoutes(): Routing
    {
        $app = $this->getHandler();
        $dataSource = $this->getDataSource();
        $app->get('/api/v1/rate/{'
            .Factory::DATE.'}/{'
            .Factory::SOURCE.'}/{'
            .Factory::TARGET.'}/{'
            .Manager::SESSION.'}',
            function (Request $request, Response $response,
                      array $arguments)
            use ($dataSource) {
                $response = (new Controller($request, $response,
                    $arguments, $dataSource))
                    ->process();

                return $response;
            });

        return $this;
    }
}
