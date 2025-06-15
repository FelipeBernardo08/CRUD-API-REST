<?php

namespace App\Controllers;

require_once __DIR__ . "/../Models/UserModel.php";
require_once __DIR__ . '/../../app/Services/JwtAuth.php';

use App\Models\UserModel;
use App\Services\JwtAuth;
use DateTime;

class UserController
{
    private $userModel;
    private $jwtAuthService;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->jwtAuthService = new JwtAuth();
    }

    public function createUser(array $request): array
    {
        if (!isset($request['email']) || !isset($request['password']) || !isset($request['name'])) {
            return [
                "success" => false,
                "error" => "Payload incorreto!"
            ];
        }

        if (!filter_var($request['email'], FILTER_VALIDATE_EMAIL)) {
            return [
                "success" => false,
                "error" => "E-mail inválido!"
            ];
        }

        if (!empty($this->userModel->getUserByEmail($request['email']))) {
            return [
                "success" => false,
                "error" => "E-mail já cadastrado!"
            ];
        }

        $result = $this->userModel->createUser($request);

        if (!$result) {
            return [
                "success" => false,
                "error" => "Erro ao criar usuário, tente novamente mais tarde!"
            ];
        }

        return [
            "success" => true,
            "data" => [
                "msg" => "Usuário criado com sucesso!"
            ]
        ];
    }

    public function login(array $request): array
    {
        if (!isset($request['email']) || !isset($request['password'])) {
            return [
                "success" => false,
                "error" => "Payload incorreto!"
            ];
        }

        if (!filter_var($request['email'], FILTER_VALIDATE_EMAIL)) {
            return [
                "success" => false,
                "error" => "E-mail inválido!"
            ];
        }

        $resultUser = $this->userModel->getUserByEmail($request["email"]);

        if (empty($resultUser)) {
            return [
                "success" => false,
                "error" => "Nenhum usuário encontrado"
            ];
        }

        if (!password_verify($request['password'], $resultUser['password'])) {
            return [
                "success" => false,
                "error" => "Senha incorreta!"
            ];
        }

        $date = new DateTime();
        $date->modify("+ 1 hour");
        $dateFormat = $date->format("Y-m-d H:i:s");

        $resultUser['exp'] = $dateFormat;

        $token = $this->jwtAuthService->createToken($resultUser);

        return [
            "success" => true,
            "data" => [
                "token" => $token
            ]
        ];
    }

    public function readUser(array $request): array
    {
        return [
            "success" => true,
            "data" => $request["authUser"]
        ];
    }

    public function updateUser(array $request): array
    {

        return [];
    }
}
