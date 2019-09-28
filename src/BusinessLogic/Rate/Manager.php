<?php

namespace BusinessLogic\Rate;


use DataStorage\Basis\DataSource;
use LanguageFeatures\ArrayParser;
use PDO;

class Manager
{
    private $arguments = [];
    private $dataPath = null;
    private $rate = 0.0;

    public function __construct(array $arguments, DataSource $dataPath)
    {
        $this->setDataPath($dataPath)
            ->setArguments($arguments);
    }

    private function getArguments(): array
    {
        return $this->arguments;
    }

    private function setArguments(array $arguments): self
    {
        $this->arguments = $arguments;
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

    public function find(): bool
    {
        $factory = new Factory($this->getArguments());
        $exchange = $factory->exchange();

        $isSuccess = $this->search($exchange);
        if ($isSuccess) {
            $isSuccess = !empty($this->getRate());
        }
        $requested = false;
        if (!$isSuccess) {
            $this->request($exchange);
            $isSuccess = !empty($this->getRate());
            $requested = true;
        }
        if ($isSuccess && $requested) {
            $this->write($exchange);
        }

        return $isSuccess;
    }

    /**
     * @param $login
     * @param $hash
     */
    private function search(Exchange $exchange): bool
    {
        $dataSource = $this->getDataPath();
        $db = new PDO(
            $dataSource->getDsn(),
            $dataSource->getUsername(),
            $dataSource->getPasswd(),
            $dataSource->getOptions());

        $request = '
SELECT 
       rate 
FROM exchange 
WHERE 
      date = CAST(:date AS INTEGER) 
  AND source = :source 
  AND target = :target';
        $command = $db->prepare($request);
        $isSuccess = !empty($command);
        if ($isSuccess) {
            $isSuccess = $command->bindValue(':date',
                $exchange->getDate());
        }
        if ($isSuccess) {
            $isSuccess = $command->bindValue(':source',
                $exchange->getSource());
        }
        if ($isSuccess) {
            $isSuccess = $command->bindValue(':target',
                $exchange->getTarget());
        }
        if ($isSuccess) {
            $isSuccess = $command->execute() !== false;
        }
        if ($isSuccess) {
            $rate = $command->fetch(PDO::FETCH_COLUMN);
            $this->setRate($rate);
            $command->closeCursor();
        }

        return $isSuccess;
    }

    private function request(Exchange $exchange): bool
    {
        $date = gmdate("Y-m-d", $exchange->getDate());
        $source = $exchange->getSource();
        $target = $exchange->getTarget();

        $response = file_get_contents(
            "https://api.exchangeratesapi.io/$date?&base=$source&symbols=$target");

        $rates = [];
        $answer = json_decode($response,true);
        $isSuccess = !empty($answer);

        $parser = null;
        if($isSuccess){
            $parser = new ArrayParser($answer);
            $rates = $parser->safely('rates');
            $isSuccess = !empty($rates);
        }
        if($isSuccess){
            $parser = new ArrayParser($rates);
            $rate = $parser->getFloat($target);
            $isSuccess = !empty($rate);
        }
        if ($isSuccess) {
            $this->setRate($rate);
        }

        return $isSuccess;
    }

    /**
     * @param float $rate
     *
     * @return Manager
     */
    private function setRate(float $rate): Manager
    {
        $this->rate = $rate;
        return $this;
    }

    /**
     * @return float
     */
    public function getRate(): float
    {
        return $this->rate;
    }

    private function write(Exchange $exchange)
    {
        $dataSource = $this->getDataPath();
        $db = new PDO(
            $dataSource->getDsn(),
            $dataSource->getUsername(),
            $dataSource->getPasswd(),
            $dataSource->getOptions());

        $request = '
INSERT INTO exchange (date,source,rate,target)
VALUES (:date, :source, CAST(:rate AS DOUBLE PRECISION), :target)';
        $command = $db->prepare($request);
        $isSuccess = !empty($command);
        if ($isSuccess) {
            $isSuccess = $command->bindValue(':date',
                $exchange->getDate());
        }
        if ($isSuccess) {
            $isSuccess = $command->bindValue(':source',
                $exchange->getSource());
        }
        if ($isSuccess) {
            $isSuccess = $command->bindValue(':target',
                $exchange->getTarget());
        }
        if ($isSuccess) {
            $isSuccess = $command->bindValue(':rate',
                $this->getRate());
        }
        if ($isSuccess) {
            $isSuccess = $command->execute() !== false;
        }

        return $isSuccess;
    }

}
