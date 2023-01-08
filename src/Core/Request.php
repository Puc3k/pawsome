<?php

namespace App\Core;

class Request
{
    private array $get;
    private array $post;
    private array $server;

    public function __construct(array $get, array $post, array $server)
    {
        $this->get = $get;
        $this->post = $post;
        $this->server = $server;
    }
    //Check if request is post
    public function isPost(): bool
    {
        return $this->server['REQUEST_METHOD'] === 'POST';
    }
    //Get param from $_GET
    public function getParam(string $name, $default = null)
    {
        return $this->get[$name] ?? $default;
    }
    //Get post param
    public function postParam(string $name, $default = null)
    {
        return $this->post[$name] ?? $default;
    }
    //Get $_POST array
    public function getPost(): array
    {
        return $_POST;
    }
}