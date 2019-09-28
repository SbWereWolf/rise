<?php


namespace BusinessLogic\Rate;


use DateTimeImmutable;

class Exchange
{
    /**
     * @var int
     */
    private $date = 0;
    /**
     * @var string
     */
    private $source ='';
    /**
     * @var string
     */
    private $target = '';

    public function __construct(int $date, string $source, string $target)
    {
        $this->source = $source;
        $this->target = $target;
        $this->date = $date;
    }

    /**
     * @param string $date
     *
     * @return Exchange
     */
    public function setDate(string $date): Exchange
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @param string $source
     *
     * @return Exchange
     */
    public function setSource(string $source): Exchange
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * @param string $target
     *
     * @return Exchange
     */
    public function setTarget(string $target): Exchange
    {
        $this->target = $target;
        return $this;
    }

    /**
     * @return string
     */
    public function getTarget(): string
    {
        return $this->target;
    }

}
