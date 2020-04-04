<?php
namespace App\services;

use App\main\App;
use PDOException;

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
}
