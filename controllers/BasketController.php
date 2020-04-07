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
                'msg' => $this->request->getMsg()
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
    
    public function removeAjaxAction()
    {
        $id = $this->getId();
        $msg = $this->app->basketService->remove($id, $this->app);
        $data = [$id, $msg];
        
        header('Content-Type: application/json');
        return json_encode($data);
    }
    
    public function orderAction() {
        if ($this->app->basketService->saveOrder($this->app)) {
            $this->request->setSession('good', []);
            $this->request->addMsg('Ваш заказ сохранен.');
        } else {
            $this->request->addMsg('Произошла ошибка с базой данных. Обратитесь в поддерджку');
        }
        $this->redirectApp();
    }
}
