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

    public function getAllOrders() {
        $sql = "SELECT order_id, `user_id`, date, (SELECT login FROM `users` WHERE id = `user_id`) `login` FROM `orders`";

        return $this->db->findAll($sql);
    }

    public function getAllUserOrders($id) {
        $sql = "SELECT * FROM `orders` WHERE user_id = :id";

        return $this->db->findAll($sql, [':id' => $id]);
    }

    public function getOrder($order_id) {
        $sql = "SELECT good_id, qty FROM `orders_goods` WHERE order_id = :order_id";

        return $this->db->findAll($sql, [':order_id' => $order_id]);
    }

    public function getUserIdByOrder($order_id) {
        $sql = "SELECT user_id FROM `orders` WHERE order_id = :order_id";

        return $this->db->find($sql, [':order_id' => $order_id])['user_id'];
    }

    public function getOrderDate($order_id) {
        $sql = "SELECT `date` FROM `orders` WHERE order_id = :order_id";

        return $this->db->find($sql, [':order_id' => $order_id])['date'];
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
