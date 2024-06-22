<?php

namespace Solisty\Authentication;

class Auth
{
    public function check($user)
    {
        return false;
    }

    public function login($user)
    {
    }

    public function logout($user)
    {
    }

    public function cookie(): string
    {
        return 'cookie';
    }
}
