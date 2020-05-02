<?php

use Slim\App;
use Illuminate\Database\Capsule\Manager as Capsule;

return function (App $app) {
    $container = $app->getContainer();

    // view renderer
    $container['renderer'] = function ($c) {
        $settings = $c->get('settings')['renderer'];
        return new \Slim\Views\PhpRenderer($settings['template_path']);
    };

    // monolog
    $container['logger'] = function ($c) {
        $settings = $c->get('settings')['logger'];
        $logger = new \Monolog\Logger($settings['name']);
        $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
        $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
        return $logger;
    };

    // conexão com banco de dados
    $container['db'] = function($c){
        $capsule = new Capsule;

        $capsule->addConnection($c->get('settings')['db']);
        
        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        return $capsule;
    };
};
