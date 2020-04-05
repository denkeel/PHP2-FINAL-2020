<?php


namespace App\services;

use App\controllers\Controller;
use App\main\App;

class BasketService
{
    public function getBasket(App $app)
    {
        $goods = $app->request->getSession(Controller::SESSION_NAME_GOODS);
        if (empty($goods)) {
            $goods = [];
        }
        return $goods;
    }

    public function add($id, App $app)
    {
        if (empty($id)) {
            return 'Не передан id товара';
        }

        $good = $app->db->goodRepository->getOne($id);

        if (empty($good)) {
            return 'Товар не найден';
        }

        $goods = $app->request->getSession(Controller::SESSION_NAME_GOODS);
        if (is_array($goods) && array_key_exists($id, $goods)) {
            $goods[$id]['count']++;
        } else {
            $goods[$id] = [
                'name' => $good->getName(),
                'price' => $good->getPrice(),
                'count' => 1,
            ];
        }

        $app->request->setSession(Controller::SESSION_NAME_GOODS, $goods);

        return 'Товар успешно добавлен';
    }

    public function remove($id, App $app) {
        if (empty($id)) {
            return 'Не передан id товара';
        }

        $goods = $app->request->getSession(Controller::SESSION_NAME_GOODS);
        //unset($goods[$id]);
        $app->request->setSession(Controller::SESSION_NAME_GOODS, $goods);

        return 'Товар удален';
    }

    public function getCurrency($price)
    {
        return 35 * $price;
    }
}