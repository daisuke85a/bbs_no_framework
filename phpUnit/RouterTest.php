<?php

require __DIR__ . '/../core/Router.php';

use core\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    public function testPushAndPop()
    {
        $stack = [];
        $this->assertSame(0, count($stack));

        array_push($stack, 'foo');
        $this->assertSame('foo', $stack[count($stack) - 1]);
        $this->assertSame(1, count($stack));

        $this->assertSame('foo', array_pop($stack));
        $this->assertSame(0, count($stack));
    }

    public function testRouterResolve()
    {
        $router = new \Core\Router(
            [
                '/' => ['controller' => 'home', 'action' => 'welcome'],
                '/login' => ['controller' => 'login', 'action' => 'login'],
                '/logout' => ['controller' => 'login', 'action' => 'logout'],
                '/signup' => ['controller' => 'login', 'action' => 'signupView'],
                '/register' => ['controller' => 'login', 'action' => 'register'],
                '/post/create' => ['controller' => 'post', 'action' => 'create'],
                '/post/:id/delete' => ['controller' => 'post', 'action' => 'delete'],
                '/post/:id' => ['controller' => 'post', 'action' => 'show'],
                '/page/:page' => ['controller' => 'post', 'action' => 'showPage'],
            ]
        );

        $router->resolve("/");

    }
}