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
                '/post/:id/delete' => ['controller' => 'post', 'action' => 'delete'],
                '/post/:id' => ['controller' => 'post', 'action' => 'show'],
            ]
        );

        $result = $router->resolve("/");
        $this->assertSame($result['controller'], "home");
        $this->assertSame($result['action'], "welcome");

        $result = $router->resolve("/login");
        $this->assertSame($result['controller'], "login");
        $this->assertSame($result['action'], "login");

        $result = $router->resolve("/post/1");
        $this->assertSame($result['controller'], "post");
        $this->assertSame($result['action'], "show");
        $this->assertSame($result['id'], "1");

        $result = $router->resolve("/post/99/delete");
        $this->assertSame($result['controller'], "post");
        $this->assertSame($result['action'], "delete");
        $this->assertSame($result['id'], "99");

    }
}