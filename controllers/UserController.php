<?php

namespace App\controllers;

use App\models\User;

class UserController extends Controller
{
    public function indexAction()
    {
        $this->loginAction();
    }

    public function loginAction()
    {
        return $this->render('login', ['msg' => $this->request->getMsg()]);
    }

    public function authAction()
    {
        $login = $this->request->post('login');
        $pass = $this->request->post('password');

        if ($this->app->auth->auth($login, $pass, $this->app)) {
            $this->redirectApp('/');
        } else {
            $this->redirectApp('');
        }
    }

    public function logoutAction()
    {
        session_destroy();
        //$this->request->unsetSession('user');
        $this->redirectApp('/');
    }

    public function signupAction()
    {
        if ($this->request->isPost()) {
            $login = $this->request->post('login');
            $pass = $this->request->post('password');
            $this->app->auth->signup($login, $pass, $this->app);
        }
        return $this->render('signup', ['msg' => $this->request->getMsg()]);
    }
}
