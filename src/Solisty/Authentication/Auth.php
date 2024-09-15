<?php

namespace Solisty\Authentication;

use Solisty\Database\Model;
use Solisty\Http\Session\Session;

class Auth extends Session
{
    private string $userModel = 'App\\Models\\User';
    private ?Model $currentUser = null;
    private Auth $instance;
    private bool $isLogged = false;

    public function __construct()
    {
        parent::__construct();
        if ($this->get('authenticated')) {
            $this->isLogged = true;
            // TODO: after completing Schema system
            // $this->currentUser = $this->userModel::find($this->get('authenticated_user_id'));
        }
    }

    public function check($user = null)
    {
        if ($this->isLogged) {
            return true;
        }

        return false;
    }

    // authenticate user by email and password
    public function attempt($email, $password)
    {
        $user = $this->userModel::where('email', $email)->where('password', $password)->get()->first();

        if ($user) {
            return $this->login($user);
        }

        return false;
    }

    public function login($user)
    {
        $this->currentUser = $user;
        $this->isLogged = true;
        $this->set('authenticated', true);
        $this->set('authenticated_user_id', $user->id);

        return true;
    }

    public function logout()
    {
        $this->set('authenticated', false);
        $this->set('authenticated_user_id', null);
        $this->currentUser = null;
        $this->isLogged = false;

        return true;
    }

    public function user()
    {
        if ($this->currentUser) {
            return $this->currentUser;
        } 
        
        return null;
    }

    public function cookie(): string
    {
        return '';
    }
}
