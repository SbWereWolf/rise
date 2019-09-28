<?php

namespace LanguageFeatures;


class ArrayParser
{
    private $data = array();

    public function __construct(array $parameters)
    {
        $this->data = $parameters;
    }

    public function getInteger(string $key): int
    {

        $value = intval($this->safely($key));
        return $value;
    }

    public function safely(string $key)
    {

        $parameters = $this->data;
        $isExists = array_key_exists($key, $parameters);

        $value = null;
        if ($isExists) {
            $value = $parameters[$key];
        }
        return $value;
    }

    public function getFloat(string $key): float
    {

        $value = floatval($this->safely($key));
        return $value;
    }

    public function getString(string $key): string
    {

        $value = strval($this->safely($key));
        return $value;
    }

}
