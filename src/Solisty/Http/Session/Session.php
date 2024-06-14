<?php

namespace Solisty\Http\Session;

use Solisty\List\HashList;

class Session extends HashList
{
    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE)
            session_start();
        foreach ($_SESSION as $key => $value) {
            parent::add($key, $value);
        }
    }

    public function set($key, $value = null)
    {
        $_SESSION[$key] = $value;
        parent::add($key, $value);
    }

    public function unset($key) {
        if ($this->has($key)) {
            unset($_SESSION[$key]);
            $this->remove($key);
        }
    }

    public function clear() {
        parent::clear();
        session_unset();
    }

    public function regenerate()
    {
        app('app')->bind('session', new Session);
    }

    public function __destruct()
    {
        session_write_close();
    }
}
