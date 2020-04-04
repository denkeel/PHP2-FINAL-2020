<?php

namespace App\services;


class Request
{
    const MSG = 'msg';

    protected $params = [
        'post' => [],
        'get' => [],
    ];

    protected $controllerName;
    protected $actionName;
    protected $requestString;

    public function __construct()
    {
        session_start();
        $this->requestString = $_SERVER['REQUEST_URI'];
        $this->parseRequest();
    }

    protected function parseRequest()
    {
        $pattern = "#(?P<controller>\w+)[/]?(?P<action>\w+)?[/]?[?]?(?P<params>.*)#ui";
        if (preg_match_all($pattern, $this->requestString, $matches)) {
            $this->actionName = $matches['action'][0];
            $this->controllerName = $matches['controller'][0];

            $this->params = [
                'post' => $_POST,
                'get' => $_GET,
            ];
        }
    }

    /**
     * @return mixed
     */
    public function getControllerName()
    {
        return $this->controllerName;
    }

    /**
     * @return mixed
     */
    public function getActionName()
    {
        return $this->actionName;
    }

    /**
     * @return mixed
     */
    public function getRequestString()
    {
        return $this->requestString;
    }

    public function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }

    public function get($name = '')
    {
        if (empty($name)) {
            return $this->params['get'];
        }

        if (empty($this->params['get'][$name])) {
            return [];
        }

        return $this->params['get'][$name];
    }

    public function getSession($key)
    {
        if (empty($key)) {
            return $_SESSION;
        }

        return !empty($_SESSION[$key])
            ? $_SESSION[$key]
            : [];
    }

    public function setSession($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function redirect($path = '')
    {
        if (empty($path)) {
            $path = $_SERVER['HTTP_REFERER'];
        }
        return header('Location: ' . $path);
    }

    public function addMsg($text)
    {
        $_SESSION[static::MSG] = $text;
    }

    public function getMsg()
    {
        $msg = '';
        if (!empty($_SESSION[static::MSG])) {
            $msg = $_SESSION[static::MSG];
            unset($_SESSION[static::MSG]);
        }

        return $msg;
    }


}
