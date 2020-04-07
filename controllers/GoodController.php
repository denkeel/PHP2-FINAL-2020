<?php
namespace App\controllers;

use App\entities\Good;

class GoodController extends Controller
{

    public function indexAction()
    {
        //var_dump($_SESSION);
        return $this->render('home');
    }

    public function oneAction()
    {
        $id = $this->getId();
        $good = $this->app->db->goodRepository->getOne($id);
        return $this->render(
            'good',
            [
                'good' => $good,
                'title' => 'Католог товаров',
                'msg' => $this->request->getMsg(),
            ]
        );
    }

    public function allAction()
    {
        $goods = $this->app->db->goodRepository->getAll();
        //var_dump($goods);
        return $this->render(
            'goods',
            [
                'goods' => $goods,
                'title' => 'Католог товаров',
                'msg' => $this->request->getMsg()
            ]
        );
    }

    public function addAction()
    {
        $good = new Good();
        if ($this->request->isPost()) {
            $good->setName($_POST['name']);
            $good->setInfo($_POST['info']);
            $good->setPrice($_POST['price']);
            $this->app->db->goodRepository->save($good);

            header('location: /good/all');
            return '';
        }

        return $this->render(
            'formGood',
            [
                'action' => '/good/add',
                'good' => $good,
            ]
        );
    }

    public function updateAction()
    {
        $id = $this->getId();
        $good = $this->app->db->goodRepository->getOne($id);
        if ($this->request->isPost()) {
            $good->setName($_POST['name']);
            $good->setInfo($_POST['info']);
            $good->setPrice($_POST['price']);
            $this->app->db->goodRepository->save($good);

            header('location: /good/all');
            return '';
        }

        return $this->render(
            'formGood',
            [
                'action' => "/good/update/?id={$id}",
                'good' => $good,
            ]
        );
    }



    public function ajaxAction()
    {
        header('Content-type: application/json');
        $params = [
            'error' => 'asdasd',
        ];
        return json_encode($params);
    }


}
