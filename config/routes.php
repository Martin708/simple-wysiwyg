<?php
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;

Router::plugin(
    'Wysiwyg',
    ['path' => '/wysiwyg'],
    function (RouteBuilder $routes) {
        $routes->fallbacks('DashedRoute');
    }
);
