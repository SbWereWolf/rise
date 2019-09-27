<?php

namespace Environment\Storage;


use DataStorage\Basis\DataSource;
use PDO;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\StatusCode;

class Controller extends \Environment\Basis\Controller
{
    const INSTALL_SQLITE = "
create table exchange
(
    id INTEGER
        constraint exchange_pk
            primary key autoincrement,
    date INTEGER NOT NULL,
    source NVARCHAR not null,
    ratio DOUBLE PRECISION NOT NULL,
    target NVARCHAR not null
);

create unique index exchange_date_source_target_uindex
    on exchange (date, source, target);

create table user
(
    id INTEGER
        constraint user_pk
            primary key autoincrement,
    login NVARCHAR not null,
    hash NVARCHAR not null
);

create unique index user_login_uindex
    on user (login);

create table session
(
    id INTEGER
        constraint session_pk
            primary key autoincrement,
    user_id int not null
        constraint session_user_id_fk
            references user,
    session NVARCHAR not null
);

create unique index session_session_uindex
    on session (session);

create index session_user_id_session_index
    on session (user_id, session);
    ";
    const UNMOUNT_SQLITE = '
DROP TABLE IF EXISTS session;
DROP TABLE IF EXISTS user;
DROP TABLE IF EXISTS exchange;

VACUUM;
';
    private $install = '';
    private $unmount = '';

    public function __construct(Request $request, Response $response, array $parametersInPath, DataSource $dataPath)
    {
        parent::__construct($request, $response, $parametersInPath, $dataPath);

        switch (DBMS) {
            case SQLITE:
                $this->setInstall(self::INSTALL_SQLITE);
                $this->setUnmount(self::UNMOUNT_SQLITE);
                break;
        }
    }

    private function setInstall(string $install): Controller
    {
        $this->install = $install;
        return $this;
    }

    private function setUnmount(string $unmount): Controller
    {
        $this->unmount = $unmount;
        return $this;
    }

    public function process(): Response
    {
        $request = $this->getRequest();
        $response = $this->getResponse();

        $isPost = $request->isPost();
        if ($isPost) {
            $response = $this->create();
        }
        $isDelete = $request->isDelete();
        if ($isDelete) {
            $response = $this->delete();
        }

        return $response;
    }

    private function create(): Response
    {
        $response = $this->executeCommand($this->getInstall());
        $status = $response->getStatusCode();

        $isSuccess = ($status == StatusCode::HTTP_CREATED);
        if (!$isSuccess) {
            $response = $response->withStatus(StatusCode::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }

    private function executeCommand($requestText): Response
    {
        $dataSource = $this->getDataPath();
        $db = new PDO(
            $dataSource->getDsn(),
            $dataSource->getUsername(),
            $dataSource->getPasswd(),
            $dataSource->getOptions());

        $isSuccess = $db->exec($requestText) !== false;

        $isDelete = $this->getRequest()->isDelete();
        $isPost = $this->getRequest()->isPost();
        $response = $this->getResponse();
        if ($isSuccess && $isPost) {
            $response = $response->withStatus(StatusCode::HTTP_CREATED);
        }
        if ($isSuccess && $isDelete) {
            $response = $response->withStatus(StatusCode::HTTP_NO_CONTENT);
        }
        if (!$isSuccess) {
            $response = $response->withStatus(StatusCode::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $response;
    }

    private function getInstall(): string
    {
        return $this->install;
    }

    private function delete(): Response
    {
        $response = $this->executeCommand($this->getUnmount());

        return $response;
    }

    private function getUnmount(): string
    {
        return $this->unmount;
    }
}
