<?php

namespace App\main;

use App\repositories\GoodRepository;
use App\services\BasketService;
use App\services\DB;
use App\services\renders\TwigRenderer;
use App\services\Request;
use App\services\TSingleton;

/**
 * Class App
 * @package App\main
 *
 * @property DB db
 * @property TwigRenderer renderer
 * @property Request request
 * @property BasketService basketService
 * @property Auth auth
 */
class App
{
    use TSingleton;

    /**
     * @return App
     */
    static public function call()
    {
        return static::getInstance();
    }

    protected $components = [];
    protected $config;

    public function run($config)
    {
        $this->config = $config;
        $this->runController();
    }

    protected function runController()
    {
        $request = App::call()->request;

        $controllerName = $request->getControllerName();
        if (empty($controllerName)) {
            $controllerName = $this->config['defaultController'];
        }

        $actionName = $request->getActionName();
        if (empty($actionName)) {
            $actionName = '';
        }

        $controllerClass = 'App\\controllers\\' . ucfirst($controllerName) . 'Controller';

        if (class_exists($controllerClass)) {
            /** @var \App\controllers\Controller $controller */
            $controller = new $controllerClass(
                App::call()->renderer,
                $request,
                App::call()
            );
            echo $controller->run($actionName);
        }
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->components)) {
            return $this->components[$name];
        }

        if (!array_key_exists($name, $this->config['components'])) {
            return null;
        }

        $className =  $this->config['components'][$name]['class'];
        if (!class_exists($className)) {
            return null;
        }

        if (array_key_exists('config', $this->config['components'][$name])) {
            $config = $this->config['components'][$name]['config'];
            $component = new $className($config);
        } else {
            $component = new $className();
        }

        $this->components[$name] = $component;
        return $component;
    }
}