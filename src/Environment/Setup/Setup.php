<?php

namespace Environment\Setup;

use Exception;
use Slim\App;
use \Environment\Storage;
use \Environment\User;
use \Environment\Session;
use \Environment\Rate;

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

        $app = (new Storage\Router($app))->settingUpRoutes()->getHandler();
        $app = (new User\Router($app))->settingUpRoutes()->getHandler();
        $app = (new Session\Router($app))->settingUpRoutes()->getHandler();
        $app = (new Rate\Router($app))->settingUpRoutes()->getHandler();

        return $app;
    }

}
