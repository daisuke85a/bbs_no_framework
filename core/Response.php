<?php

class Response{
    public $content;
    public $status_code;
    public $status_text;

    public function send(){
        header('HTTP/1.1' . $this->status_code . ' ' . $this->status_text);

        echo $this->content;
    }

    
}