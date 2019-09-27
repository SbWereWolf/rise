<?php

namespace Environment\Session;


use BusinessLogic\Session\Manager;
use Slim\Http\Response;
use Slim\Http\StatusCode;

class Controller extends \Environment\Basis\Controller
{
    public function process(): Response
    {
        $request = $this->getRequest();
        $response = $this->getResponse();

        $isPost = $request->isPost();
        if ($isPost) {
            $credential = $request->getParsedBody();
            $response = $this->create($credential);
        }

        return $response;
    }

    private function create(array $credential): Response
    {
        $isSuccess = $this->startSession($credential);
        $response = $this->getResponse();

        if($isSuccess){
            $response = $response->withStatus(
                StatusCode::HTTP_CREATED);
        }
        if (!$isSuccess) {
            $response = $response->withStatus(
                StatusCode::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }

    private function startSession(array $credential): bool
    {
        $dataSource = $this->getDataPath();
        $manager = new Manager($credential,$dataSource);

        $isSuccess = $manager->start();
        if($isSuccess){
            $session = $manager->getSession();
            $output = $this->getResponse()->withJson($session);
            $this->setResponse($output);
        }

        return $isSuccess;
    }
}
