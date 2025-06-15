<?php

namespace Routes;

class Routes
{
    protected $routes = [
        [
            "http_route" => "/migrate",
            "http_method" => "GET",
            "method" => "migrate",
            "dir" => "/../app/Controllers/MigrationController.php",
            "import" => "MigrationController",
            "auth" => false,
            "auth_method" => "",
        ],
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

    public function getRoutes(): array
    {
        return $this->routes;
    }
}
