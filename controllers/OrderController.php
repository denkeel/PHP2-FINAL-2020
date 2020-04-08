<?php

namespace App\controllers;

class OrderController extends Controller
{
    public function indexAction()
    {
        return $this->showAllAction();
    }
    
    public function showAllAction() {
        $user_id = $this->app->request->getSession('user_id'); 
        //var_dump($user_id);
        if (empty($user_id)) {
            $this->request->addMsg('Вы не авторизированы. Пожалуйста авторизируйтесь');
            $this->redirectApp('/user/login');
        }
        
        $orders = $this->app->db->orderRepository->getAllUserOrders($user_id);
        return $this->render(
            'orders',
            [
                'title' => 'Мои заказы',
                'orders' => $orders
            ]
        );
    }
    
    public function showAction() {
        $user_id = $this->app->request->getSession('user_id'); 
        $is_admin = $this->app->request->getSession('is_admin'); 
        if (empty($user_id)) {
            $this->request->addMsg('Вы не авторизированы. Пожалуйста авторизируйтесь');
            $this->redirectApp('/user/login');
        }
        
        $order_id = $this->request->get('id');
        $orders_user_id = $this->app->db->orderRepository->getUserIdByOrder($order_id);
        //var_dump($orders_user_id);
        //var_dump($orders_user_id);
        if (($user_id !== $orders_user_id) && (!$is_admin)) {
            $this->redirectApp('/order');
        }

        $order = $this->app->db->orderRepository->getOrder($order_id);
        foreach ($order as &$good) {
            $good_full = $this->app->db->goodRepository->getOne($good['good_id']);
            unset($good['good_id']);
            $good['name'] = $good_full->getName();
            $good['info'] = $good_full->getInfo();
            $good['price'] = $good_full->getPrice();
        }
        $date = $this->app->db->orderRepository->getOrderDate($order_id);
        
        return $this->render(
            'order',
            [
                'title' => 'Заказ',
                'date' => $date,
                'order' => $order
            ]
        );
    }
    
    public function makeAction() {
        if ($this->app->orderService->saveOrder($this->app)) {
            $this->request->setSession('good', []);
            $this->request->addMsg('Ваш заказ сохранен.');
        } else {
            $this->request->addMsg('Произошла ошибка с базой данных. Обратитесь в поддерджку');
        }
        $this->redirectApp();
    }
}
