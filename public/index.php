<?php

require_once __DIR__ . '/../config/env.php';
require_once __DIR__ . '/../routes/api.php';
require_once __DIR__ . "/../routes/routes.php";
require_once __DIR__ . "/../app/Services/DatabaseService.php";
require_once __DIR__ . "/../app/Services/ResponseHttpService.php";
require_once __DIR__ . "/../app/Models/UserModel.php";
require_once __DIR__ . "/../app/Services/JwtAuth.php";
require_once __DIR__ . "/../app/Controllers/MigrationController.php";
require_once __DIR__ . "/../app/Controllers/UserController.php";
require_once __DIR__ . "/../app/Controllers/ExpenseController.php";
require_once __DIR__ . "/../app/Models/ExpenseModel.php";

date_default_timezone_set('America/Sao_Paulo');

use Config\Env;
use Routes\Api;

Env::loadEnv();

$route = new Api(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), $_SERVER["REQUEST_METHOD"]);

$route->index();
