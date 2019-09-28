<?php

namespace Environment\Rate;


use BusinessLogic\Rate;
use BusinessLogic\Session;
use Slim\Http\Response;
use Slim\Http\StatusCode;

class Controller extends \Environment\Basis\Controller
{
    public function process(): Response
    {
        $request = $this->getRequest();
        $response = $this->getResponse();

        $isGet = $request->isGet();
        $isAllow = false;
        if ($isGet) {
            $isAllow = $this->checkPermission();
        }
        if ($isAllow && $isGet) {
            $response = $this->request();
        }

        return $response;
    }

    private function request(): Response
    {
        $isSuccess = $this->run();
        $response = $this->getResponse();

        if ($isSuccess) {
            $response = $response->withStatus(
                StatusCode::HTTP_OK);
        }
        if (!$isSuccess) {
            $response = $response->withStatus(
                StatusCode::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }

    private function run(): bool
    {
        $arguments = $this->getArguments();
        $dataSource = $this->getDataPath();
        $manager = new Rate\Manager($arguments, $dataSource);

        $isSuccess = $manager->find();
        if ($isSuccess) {
            $rate = $manager->getRate();
            $output = $this->getResponse()->withJson($rate);
            $this->setResponse($output);
        }

        return $isSuccess;
    }

    private function checkPermission():bool
    {
        $arguments = $this->getArguments();
        $dataSource = $this->getDataPath();
        $manager = new Session\Manager($arguments, $dataSource);

        $isAllow = $manager->check();

        return $isAllow;

    }
}
