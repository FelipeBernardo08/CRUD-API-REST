<?php

require_once __DIR__ . '/../config/env.php';
require_once __DIR__ . '/../routes/api.php';

date_default_timezone_set('America/Sao_Paulo');

use Config\Env;
use Routes\Api;

Env::loadEnv();

$route = new Api(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), $_SERVER['REQUEST_METHOD']);

$route->index();
