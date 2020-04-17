<?php

namespace App\services;

use App\controllers\Controller;
use App\main\App;

class OrderService
{
    public function saveOrder(App $app) {
        
        $user_id = $app->request->getSession('user_id');
        $order_id = $app->db->orderRepository->createOrder($user_id);

        $basket = $app->request->getSession('good');
        return $app->db->orderRepository->fillOrder($order_id, $basket);
    }
}