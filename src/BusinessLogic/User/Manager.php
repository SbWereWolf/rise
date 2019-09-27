<?php

namespace BusinessLogic\User;


use DataStorage\Basis\DataSource;
use PDO;

class Manager
{
    private $properties = [];
    private $dataPath = null;

    public function __construct(array $properties, DataSource $dataPath)
    {
        $this->setDataPath($dataPath)
            ->setProperties($properties);
    }

    private function getProperties(): array
    {
        return $this->properties;
    }

    private function setProperties(array $properties): self
    {
        $this->properties = $properties;
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

    public function registration(): bool
    {
        $factory = new Factory($this->getProperties());
        $user = $factory->user();
        $isSuccess = $this->write($user);

        return $isSuccess;
    }

    /**
     * @param $login
     * @param $hash
     */
    private function write(User $user): bool
    {
        $dataSource = $this->getDataPath();
        $db = new PDO(
            $dataSource->getDsn(),
            $dataSource->getUsername(),
            $dataSource->getPasswd(),
            $dataSource->getOptions());

        $isSuccess = false;
        $request = 'INSERT INTO user (login,hash)VALUES(:login,:hash)';
        $command = $db->prepare($request);
        if (!empty($command)) {
            $isSuccess = $command->bindValue(':login', $user->getLogin());
        }
        if ($isSuccess) {
            $isSuccess = $command->bindValue(':hash', $user->getHash());
        }
        if ($isSuccess) {
            $isSuccess = $command->execute() !== false;
        }

        return $isSuccess;
    }
}
