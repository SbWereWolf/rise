<?php

namespace BusinessLogic\Session;


use BusinessLogic\User\Factory;
use DataStorage\Basis\DataSource;
use PDO;

class Manager
{
    private $credential = [];
    private $dataPath = null;
    /**
     * @var string
     */
    private $session = '';

    public function __construct(array $credential, DataSource $dataPath)
    {
        $this->setDataPath($dataPath)
            ->setCredential($credential);
    }

    private function getCredential(): array
    {
        return $this->credential;
    }

    private function setCredential(array $credential): self
    {
        $this->credential = $credential;
        return $this;
    }

    private function getDataPath(): DataSource
    {
        return $this->dataPath;
    }

    private function setDataPath(DataSource $dataPath): self
    {
        $this->dataPath = $dataPath;
        return $this;
    }

    public function start(): bool
    {
        $user = (new Factory($this->getCredential()))->user();
        $session = (new Builder($user))->session();

        $isSuccess = $this->write($session);
        if ($isSuccess) {
            $this->setSession($session->getSession());
        }

        return $isSuccess;
    }

    private function write(Session $session): bool
    {
        $dataSource = $this->getDataPath();
        $db = new PDO(
            $dataSource->getDsn(),
            $dataSource->getUsername(),
            $dataSource->getPasswd(),
            $dataSource->getOptions());

        $request = '
INSERT INTO session (user_id,session)
SELECT id,:session
FROM user
WHERE login=:login AND hash=:hash';
        $isSuccess = false;
        $command = $db->prepare($request);
        if (!empty($command)) {
            $isSuccess = $command->bindValue(':session',
                $session->getSession());
        }
        if (!empty($command)) {
            $isSuccess = $command->bindValue(':login',
                $session->getUser()->getLogin());
        }
        if ($isSuccess) {
            $isSuccess = $command->bindValue(':hash',
                $session->getUser()->getHash());
        }
        if ($isSuccess) {
            $isSuccess = $command->execute() !== false;
        }

        return $isSuccess;
    }

    /**
     * @param string $session
     *
     * @return Manager
     */
    public function setSession(string $session): Manager
    {
        $this->session = $session;
        return $this;
    }

    /**
     * @return string
     */
    public function getSession(): string
    {
        return $this->session;
    }
}
