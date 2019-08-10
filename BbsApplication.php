<?php

class BbsApplication extends Application
{
    protected function registerRoutes(): array
    {
        return [
            '/' => ['controller' => 'home', 'action' => 'welcome'],
            '/login' => ['controller' => 'login', 'action' => 'login'],
            '/logout' => ['controller' => 'login', 'action' => 'logout'],
            '/signup' => ['controller' => 'login', 'action' => 'signupView'],
            '/register' => ['controller' => 'login', 'action' => 'register'],
            '/post/create' => ['controller' => 'post', 'action' => 'create'],
            '/post/:id/delete' => ['controller' => 'post', 'action' => 'delete'],
            '/post/:id' => ['controller' => 'post', 'action' => 'show'],
            '/page/:page' => ['controller' => 'post', 'action' => 'showPage'],
        ];
    }
}