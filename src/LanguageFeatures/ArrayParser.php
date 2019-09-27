<?php

namespace LanguageFeatures;


class ArrayParser
{
    private $parameters = array();

    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    public function getInteger(string $field): int
    {

        $value = intval($this->safely($field));
        return $value;
    }

    private function safely(string $key)
    {

        $parameters = $this->parameters;
        $isExists = array_key_exists($key, $parameters);

        $value = null;
        if ($isExists) {
            $value = $parameters[$key];
        }
        return $value;
    }

    public function getFloat(string $field): float
    {

        $value = floatval($this->safely($field));
        return $value;
    }

    public function getString(string $field): string
    {

        $value = strval($this->safely($field));
        return $value;
    }

}
