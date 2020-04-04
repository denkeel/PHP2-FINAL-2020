<?php


namespace App\controllers;


class BasketController extends  Controller
{
    public function indexAction()
    {
        return $this->render(
            'basket',
            [
                'goods' => $this->app->basketService->getBasket($this->app),
            ]
        );
    }

    public function addAction()
    {
        $id = $this->getId();
        $msg = $this->app->basketService->add($id, $this->app);
        $this->request->addMsg($msg);
        $this->redirectApp();
    }
}