<?php

namespace App\Services;

class ResponseHttpService
{
    public function responseOk(array $response): void
    {
        $data = [
            "success" => true,
            "data" => $response
        ];
        http_response_code(200);
        header("Content-Type: application/json");
        echo json_encode($data);
    }

    public function notFound(string $message): void
    {
        $data = [
            "success" => false,
            "error" => $message
        ];
        http_response_code(404);
        header("Content-Type: application/json");
        echo json_encode($data);
    }

    public function unauthorized(string $message): void
    {
        $data = [
            "success" => false,
            "error" => $message
        ];
        http_response_code(401);
        header("Content-Type: application/json");
        echo json_encode($data);
    }

    public function badRequest(string $message): void
    {
        $data = [
            "success" => false,
            "error" => $message
        ];
        http_response_code(400);
        header("Content-Type: application/json");
        echo json_encode($data);
    }

    public function badGateway(string $message): void
    {
        $data = [
            "success" => false,
            "error" => $message
        ];
        http_response_code(500);
        header("Content-Type: application/json");
        echo json_encode($data);
    }
}
