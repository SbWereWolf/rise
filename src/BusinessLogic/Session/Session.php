<?php


namespace BusinessLogic\Session;


use BusinessLogic\User\User;

class Session
{
    private $session = '';
    /**
     * @var User
     */
    private $user;

    public function __construct(User $user,string $session)
    {
        $this->session = $session;
        $this->user = $user;
    }

    /**
     * @param string $session
     *
     * @return Session
     */
    public function setSession(string $session): Session
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

    /**
     * @param User $user
     *
     * @return Session
     */
    public function setUser(User $user): Session
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }


}
