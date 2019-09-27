<?php


namespace BusinessLogic\User;


class Factory
{
    private $properties;

    public function __construct(array $properties)
    {
        $this->properties = $properties;
    }

    public function user(): User
    {
        $properties = $this->properties;
        $login = $properties['login'];
        $secret = $properties['password'];
        $hash = crypt($secret, SALT);

        $user = new User($login,$hash);

        return $user;
    }
}
