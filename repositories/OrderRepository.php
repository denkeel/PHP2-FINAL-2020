<?php

namespace App\repositories;

class OrderRepository extends Repository
{
    protected function getTableName(): string
    {
        return 'orders';
    }

    protected function getEntityName(): string
    {
        return '';
    }

    public function createOrder($user_id)
    {
        $sql =  "INSERT INTO `orders` (`user_id`) VALUES (:user_id)";
        if ($this->db->executeQueryBool($sql, [':user_id' => $user_id])) {
            return $this->db->lastInsertId();
        }

        return false;
    }

    public function fillOrder($order_id, $basket)
    {
        foreach ($basket as $good) {
            $good_id = $good['id'];
            $qty = $good['count'];
            if (!$this->addGood($order_id, $good_id, $qty)) {
                return false;
            };
        }

        return true;
    }

    protected function addGood($order_id, $good_id, $qty)
    {
        $sql = "INSERT INTO `orders_goods` (`order_id`, `good_id`, `qty`) VALUES (:order_id, :good_id, :qty);";
        return $this->db->executeQueryBool($sql, [':order_id' => $order_id,
                                                  ':good_id' => $good_id,
                                                  ':qty' => $qty]);
    }
}
