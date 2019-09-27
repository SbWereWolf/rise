<?php

namespace Environment\User;


use BusinessLogic\User\Manager;
use PDO;
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
            $userProperties = $request->getParsedBody();
            $response = $this->create($userProperties);
        }

        return $response;
    }

    private function create(array $userProperties): Response
    {
        $isSuccess = $this->userRegistration($userProperties);
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

    private function userRegistration(array $userProperties): bool
    {
        $dataSource = $this->getDataPath();
        $manager = new Manager($userProperties,$dataSource);
        $isSuccess = $manager->registration();

        return $isSuccess;
    }
}
