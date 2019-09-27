<?php


namespace BusinessLogic\Session;


use BusinessLogic\User\User;

class Builder
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function session(): Session
    {
        $login = $this->user->getLogin();
        $hash = $this->user->getHash();
        $salt = (string)microtime();

        $session = md5("$login$hash$salt");

        $user = new Session($this->user,$session);

        return $user;
    }
}
