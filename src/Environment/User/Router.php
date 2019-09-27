<?php

namespace Environment\User;


use Environment\Basis\Routing;
use Slim\Http\Request;
use Slim\Http\Response;

class Router extends \Environment\Basis\Router
{
    public function settingUpRoutes(): Routing
    {
        $app = $this->getHandler();
        $dataSource = $this->getDataSource();
        $app->post('/api/v1/user', function (Request $request, Response $response, array $arguments)
        use ($dataSource) {
            $response = (new Controller($request, $response, $arguments, $dataSource))
                ->process();

            return $response;
        });

        return $this;
    }
}
