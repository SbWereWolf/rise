<?php


namespace BusinessLogic\Rate;


use DateTime;
use DateTimeImmutable;
use LanguageFeatures\ArrayParser;

class Factory
{
    const DATE = 'date';
    const SOURCE = 'source';
    const TARGET = 'target';

    private $arguments;

    public function __construct(array $arguments)
    {
        $this->arguments = $arguments;
    }

    public function exchange(): Exchange
    {
        $arguments = $this->arguments;
        $parser = new ArrayParser($arguments);

        $rawDate = $parser->getString(self::DATE);
        $rawDate = "$rawDate 00:00:00";
        $dateTime = (DateTime
            ::createFromFormat("d.m.Y H:i:s", $rawDate));

        $date = 0;
        if($dateTime){
            $date = $dateTime->getTimestamp();
        }

        $source = $parser->getString(self::SOURCE);
        $target = $parser->getString(self::TARGET);
        $exchange = new Exchange($date, $source,$target);

        return $exchange;
    }
}
