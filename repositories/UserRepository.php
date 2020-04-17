<?php

namespace App\repositories;

class UserRepository extends Repository
{
    protected function getTableName(): string
    {
        return 'users';
    }

    protected function getEntityName(): string
    {
        return 'User::class';
    }

    public function getUser($login)
    {
        $sql = "
        SELECT 
            id, login, password, is_admin 
        FROM 
            users 
        WHERE 
            login = :login
	    ";
        return $this->db->find($sql, [':login' => $login]);
    }

    public function insertUser($login, $pass) {
        $sql = "INSERT INTO `users` (`login`, `password`) VALUES (:_login, :pass)";
        return $this->db->executeQueryBool($sql, [':_login' => $login, ':pass' => $pass]);
    }
}
