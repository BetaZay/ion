<?php

// core/http/Response.php
namespace core\http;

class Response
{
    public function send(string $content, int $status = 200): void
    {
        http_response_code($status);
        echo $content;
    }

    public function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function view(string $view, array $data = []): void
    {
        \core\pulse\View::render($view, $data);
    }

    public function redirect(string $location): void
    {
        header("Location: $location");
        exit;
    }
}
