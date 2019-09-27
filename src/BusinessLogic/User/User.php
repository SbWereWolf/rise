<?php


namespace BusinessLogic\User;


class User
{
    private $login = '';
    private $hash = '';

    public function __construct(string $login, string $hash)
    {
        $this->login = $login;
        $this->hash = $hash;
    }

    /**
     * @param string $login
     *
     * @return User
     */
    public function setLogin(string $login): User
    {
        $this->login = $login;
        return $this;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @param string $hash
     *
     * @return User
     */
    public function setHash(string $hash): User
    {
        $this->hash = $hash;
        return $this;
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }


}
