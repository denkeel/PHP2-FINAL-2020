<?php
namespace App\services;

use App\main\App;

class Auth
{
    public function auth($login, $pass, App $app)
    {
        $user = $app->db->userRepository->getUser($login);
        if ($user === false) {
            $app->request->addMsg('Пользователь не найден');
            return false;
        }
        
        if (password_verify($pass, $user['password'])) {
            $app->request->setSession('user', $login);
            return true;
        } else {
            $app->request->addMsg('Неверный пароль');
            return false;
        }
    }
    
    public function signup($login, $pass, App $app) {
        $user = $app->db->userRepository->getUser($login);
        if ($user !== false) {
            $app->request->addMsg('Такой пользователь уже существует');
            return false;
        }

        $pass_hash = password_hash($pass, PASSWORD_DEFAULT);
        if ($app->db->userRepository->insertUser($login, $pass_hash)) {
            $app->request->setSession('user', $login);
            $app->request->addMsg("$login, регистрация прошла успешно!");
            return true;
        } else {            
            $app->request->addMsg('Ошибка базы данных. Обратитесь в поддержку');
            return false;
        }
    }
}
