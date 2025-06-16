<?php

namespace Routes;

use App\Services\JwtAuth;
use Routes\Routes;
use App\Services\ResponseHttpService;

class Api
{
    private $requestUri;
    private $requestMethod;
    private $routes;
    private $responseHttpService;

    protected $routeRules = [];

    public function __construct(string $uri, string $method)
    {
        $this->requestUri = $uri;
        $this->requestMethod = $method;
        $this->routes = new Routes();
        $this->responseHttpService = new ResponseHttpService();
    }

    public function index(): void
    {
        try {
            $this->routeRules = $this->routes->getRoutes();
            $route = $this->findRoute($this->requestUri);

            if (empty($route)) {
                $msg = "Endpoint não encontrado!";
                $this->responseHttpService->notFound($msg);
                return;
            }

            if (strtolower($route["http_method"]) != strtolower($this->requestMethod)) {
                $msg = "Método HTTP incorreto. Rota aceita: " . $route["http_method"] . ".";
                $this->responseHttpService->badRequest($msg);
                return;
            }

            $className = "App\\Controllers\\" . $route["import"];

            if (!class_exists($className)) {
                $msg = "Classe não encontrada!";
                $this->responseHttpService->badGateway($msg);
                return;
            }

            $authUser = [];

            if ($route["auth"]) {
                $headers = getallheaders();
                if (!isset($headers["Authorization"])) {
                    $msg = "Token não fornecido!";
                    $this->responseHttpService->unauthorized($msg);
                    return;
                }

                $token = $headers["Authorization"];

                $authUser = $this->verifyAuth($token);

                if (empty($authUser)) {
                    $msg = "Token inválido ou expirado!";
                    $this->responseHttpService->unauthorized($msg);
                    return;
                }
            }

            $controller = new $className;

            $method = $route["method"];

            $body = $this->getBody();

            $body["authUser"] = $authUser;

            $result = $controller->$method($body);

            if (empty($result)) {
                $msg = "Erro inesperado, tente novamente mais tarde!";
                $this->responseHttpService->badGateway($msg);
                return;
            }

            if (!$result["success"]) {
                $this->responseHttpService->notFound($result["error"]);
                return;
            }

            $this->responseHttpService->responseOk($result["data"]);
            return;
        } catch (\Throwable $th) {
            $this->responseHttpService->badGateway($th->getMessage());
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
