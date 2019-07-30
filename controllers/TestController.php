<?php

class TestController extends Controller
{
    public function indexAction(){
        return $this->render(
            [ 'login' => 'TRUE',
              'body' => 'default body'
            ],
            'TestIndex.php'
        );
    }
}