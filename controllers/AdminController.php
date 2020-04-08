<?php

namespace App\controllers;

class AdminController extends Controller
{
    public function indexAction()
    {
        $is_admin = $this->app->request->getSession('is_admin'); 
        if (!$is_admin) {
            $this->request->addMsg('Недостаточно прав. Доступ только для администраторов');
            $this->redirectApp('/user/login');
        }
        
        return $this->render(
            'admin',
            [
                'title' => 'Панель администрирования'
            ]
        );
    }

    public function ordersAction()
    {
        $user_id = $this->app->request->getSession('user_id'); 
        if (empty($user_id)) {
            $this->request->addMsg('Вы не авторизированы. Пожалуйста авторизируйтесь');
            $this->redirectApp('/user/login');
        }
        
        $is_admin = $this->app->request->getSession('is_admin'); 
        if (!$is_admin) {
            $this->request->addMsg('Недостаточно прав. Доступ только для администраторов');
            $this->redirectApp('/user/login');
        }
        
        $orders = $this->app->db->orderRepository->getAllOrders();
        return $this->render(
            'admin_orders',
            [
                'title' => 'Заказы',
                'orders' => $orders
            ]
        );
    }
}
