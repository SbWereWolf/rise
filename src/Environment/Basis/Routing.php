<?php

namespace Environment\Basis;


use Slim\App;

interface Routing
{
    public function getHandler(): App;

    public function settingUpRoutes(): Routing;

}
