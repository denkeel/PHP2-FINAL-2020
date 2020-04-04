<?php


namespace App\services\renders;


interface IRenderer
{
    public function render($template, $params = []);
}