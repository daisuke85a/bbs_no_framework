<?php

abstract class Controller
{
    public function run($action, $params = []){

        $this->action_name = $action;

        $action_method = $action . 'Action';

        if(!method_exists($this, $action_method)){
            $this->forward404();
        }

        $content = $this->$action_method($params);

        return $content;
    }

    protected function render($variables = [], $view_file , $template = null ){
        
        $defaults =
         [ 
        //    'base_url' => $this->request->getBaseUrl(),
        //    'session'  => $this->session,
         ];

        $view = new View($defaults);

        return $view->render($view_file, $variables);

    }
}