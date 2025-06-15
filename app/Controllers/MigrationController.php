<?php

namespace App\Controllers;

require_once __DIR__ . "/../Services/DatabaseService.php";
require_once __DIR__ . "/../Services/ResponseHttpService.php";

use App\Services\DatabaseService;
use App\Services\ResponseHttpService;

class MigrationController
{
    private $dbService;
    private $responseHttpService;

    public function __construct()
    {
        $host = getenv("DB_HOST");
        $user = getenv("DB_USERNAME");
        $pass = getenv("DB_PASSWORD");
        $database = getenv("DB_DATABASE");

        $this->dbService = new DatabaseService($host, $user, $pass, $database);
        $this->responseHttpService = new ResponseHttpService();
    }

    public function migrate(): array
    {
        $this->dbService->migration();
        return ["success" => true, "data" => ["msg" => "Migration concluída com sucesso!"]];
    }
}
