<?php

namespace Environment\Setup;

use Environment\Storage\Router;
use Exception;
use Slim\App;

class Setup
{
    private $handler = null;

    public function __construct(App $app)
    {
        $this->handler = $app;
    }

    /**
     * @return App
     * @throws Exception
     */
    public function perform(): App
    {
        $app = $this->handler;

        $app = (new Router($app))->settingUpRoutes()->getHandler();

        return $app;
    }

}
