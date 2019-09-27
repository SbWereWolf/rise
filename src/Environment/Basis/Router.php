<?php

namespace Environment\Basis;


use DataStorage\Basis\DataSource;
use Exception;
use Slim\App;

class Router implements Routing
{
    private $handler = null;
    private $dataSource = null;

    public function __construct(App $app)
    {
        $this->handler = $app;
        $this->dataSource = $app->getContainer()->get(SETTINGS_KEY)[DATA_SOURCE_KEY];
    }

    public function getHandler(): App
    {
        return $this->handler;
    }

    /** @throws Exception Method Not Implemented */
    public function settingUpRoutes(): Routing
    {
        throw new Exception('Method settingUpRoutes() Not Implemented');
    }

    public function getDataSource(): DataSource
    {
        return $this->dataSource;
    }
}
