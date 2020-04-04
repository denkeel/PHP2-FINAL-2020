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
}
