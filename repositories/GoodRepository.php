<?php

namespace App\repositories;

use App\entities\Good;

/**
 * Class GoodRepository
 * @package App\repositories
 *
 * @method Good getOne($id)
 */
class GoodRepository extends Repository
{
    protected function getTableName(): string
    {
        return 'goods';
    }

    protected function getEntityName(): string
    {
        return Good::class;
    }

    public function getAllOrders($id)
    {
        $sql = '';
        return $this->db->find($sql, [':id' => $id]);
    }
}
