<?php

namespace Routes;

require_once __DIR__ . "/../app/Services/JwtAuth.php";

use App\Services\JwtAuth;
use DateTime;

class Api
{
    private $requestUri;
    private $requestMethod;

    protected $routeRules = [
        [
            "http_route" => "/user/create",
            "http_method" => "POST",
            "method" => "createUser",
            "dir" => "/../app/Controllers/UserController.php",
            "import" => "UserController",
            "auth" => false,
            "auth_method" => "",
        ],
        [
            "http_route" => "/user/login",
            "http_method" => "POST",
            "method" => "login",
            "dir" => "/../app/Controllers/UserController.php",
            "import" => "UserController",
            "auth" => false,
            "auth_method" => "",
        ],
        [
            "http_route" => "/user/read",
            "http_method" => "GET",
            "method" => "readUser",
            "dir" => "/../app/Controllers/UserController.php",
            "import" => "UserController",
            "auth" => true,
            "auth_method" => "jwt",
        ],
        [
            "http_route" => "/user/update",
            "http_method" => "PUT",
            "method" => "updateUser",
            "dir" => "/../app/Controllers/UserController.php",
            "import" => "UserController",
            "auth" => true,
            "auth_method" => "jwt",
        ]
    ];

    public function __construct(string $uri, string $method)
    {
        $this->requestUri = $uri;
        $this->requestMethod = $method;
    }

    public function index(): void
    {
        try {
            $route = $this->findRoute($this->requestUri);

            if (empty($route)) {
                $msg = "Endpoint não encontrado!";
                $responseData = [
                    "success" => false,
                    "error" => $msg
                ];
                $this->responseHttp($responseData, 404);
                return;
            }

            if (strtolower($route["http_method"]) != strtolower($this->requestMethod)) {
                $msg = "Método HTTP incorreto. Rota aceita: " . $route["http_method"] . ".";
                $responseData = [
                    "success" => false,
                    "error" => $msg
                ];
                $this->responseHttp($responseData, 404);
                return;
            }

            require_once __DIR__ . $route["dir"];

            $className = "App\\Controllers\\" . $route["import"];

            if (!class_exists($className)) {
                $msg = "Classe não encontrada!";
                $responseData = [
                    "success" => false,
                    "error" => $msg
                ];
                $this->responseHttp($responseData, 404);
                return;
            }

            $authUser = [];

            if ($route["auth"]) {
                $headers = getallheaders();
                if (!isset($headers["Authorization"])) {
                    $msg = "Token não fornecido!";
                    $responseData = [
                        "success" => false,
                        "error" => $msg
                    ];
                    $this->responseHttp($responseData, 401);
                    return;
                }

                $token = $headers["Authorization"];

                $authUser = $this->verifyAuth($token);

                if (empty($authUser)) {
                    $msg = "Token inválido ou expirado!";
                    $responseData = [
                        "success" => false,
                        "error" => $msg
                    ];
                    $this->responseHttp($responseData, 401);
                    return;
                }
            }

            $controller = new $className;

            $method = $route["method"];

            $body = $this->getBody();

            $body["authUser"] = $authUser;

            $result = [];

            $result = $controller->$method($body);

            if (empty($result)) {
                $msg = "Erro inesperado, tente novamente mais tarde!";
                $responseData = [
                    "success" => false,
                    "error" => $msg
                ];

                $this->responseHttp($responseData, 500);
                return;
            }

            if (!$result["success"]) {
                $responseData = [
                    "success" => false,
                    "error" => $result["error"]
                ];

                $this->responseHttp($responseData, 404);
                return;
            }

            $responseData = [
                "success" => true,
                "data" => $result["data"],
            ];


            $this->responseHttp($responseData, 200);
            return;
        } catch (\Throwable $th) {
            $dataResponse = [
                "success" => false,
                "error" => $th->getMessage()
            ];

            $this->responseHttp($dataResponse, 500);
            return;
        }
    }

    private function findRoute(string $route): array
    {
        $result = array_filter($this->routeRules, function ($data) use ($route) {
            return $data["http_route"] == $route;
        });

        return is_array(reset($result)) ? reset($result) : [];
    }

    private function getBody(): array
    {
        $body = file_get_contents("php://input");
        $data = json_decode($body, true);

        return $data ?? [];
    }

    private function responseHttp(array $data, int $status): void
    {
        http_response_code($status);
        header("Content-Type: application/json");
        echo json_encode($data);
    }

    private function verifyAuth(string $token): array
    {
        $auth = new JwtAuth();

        $userData = $auth->decryptToken($token);

        if (!isset($userData["exp"]) || !isset($userData["email"]) || !isset($userData["password"]) || !isset($userData["name"])) {
            return [];
        }

        if ($userData["exp"] <= date("Y-m-d H:i:s")) {
            return [];
        }

        unset($userData["password"]);

        return $userData;
    }
}
