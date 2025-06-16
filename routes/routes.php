<?php

namespace Routes;

class Routes
{
    protected $routes = [
        [
            "http_route" => "/migrate",
            "http_method" => "GET",
            "method" => "migrate",
            "import" => "MigrationController",
            "auth" => false,
            "auth_method" => "",
        ],
        [
            "http_route" => "/user/create",
            "http_method" => "POST",
            "method" => "createUser",
            "import" => "UserController",
            "auth" => false,
            "auth_method" => "",
        ],
        [
            "http_route" => "/user/login",
            "http_method" => "POST",
            "method" => "login",
            "import" => "UserController",
            "auth" => false,
            "auth_method" => "",
        ],
        [
            "http_route" => "/user/read",
            "http_method" => "GET",
            "method" => "readUser",
            "import" => "UserController",
            "auth" => true,
            "auth_method" => "jwt",
        ],
        [
            "http_route" => "/user/read-complete",
            "http_method" => "GET",
            "method" => "readUserWithExpense",
            "import" => "UserController",
            "auth" => true,
            "auth_method" => "jwt",
        ],
        [
            "http_route" => "/user/update",
            "http_method" => "PUT",
            "method" => "updateUser",
            "import" => "UserController",
            "auth" => true,
            "auth_method" => "jwt",
        ],
        [
            "http_route" => "/expense/create",
            "http_method" => "POST",
            "method" => "createExpense",
            "import" => "ExpenseController",
            "auth" => true,
            "auth_method" => "jwt",
        ],
        [
            "http_route" => "/expense/create",
            "http_method" => "POST",
            "method" => "createExpense",
            "import" => "ExpenseController",
            "auth" => true,
            "auth_method" => "jwt",
        ],
        [
            "http_route" => "/expense/update",
            "http_method" => "PUT",
            "method" => "updateExpense",
            "import" => "ExpenseController",
            "auth" => true,
            "auth_method" => "jwt",
        ],
        [
            "http_route" => "/expense/delete",
            "http_method" => "DELETE",
            "method" => "deleteExpense",
            "import" => "ExpenseController",
            "auth" => true,
            "auth_method" => "jwt",
        ],

    ];

    public function getRoutes(): array
    {
        return $this->routes;
    }
}
