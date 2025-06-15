<?php

namespace App\Controllers;

use App\Services\DatabaseService;

class MigrationController
{
    private $dbService;

    public function __construct()
    {
        $host = getenv("DB_HOST");
        $user = getenv("DB_USERNAME");
        $pass = getenv("DB_PASSWORD");
        $database = getenv("DB_DATABASE");

        $this->dbService = new DatabaseService($host, $user, $pass, $database);
    }

    public function migrate(): array
    {
        $this->dbService->migration();
        return ["success" => true, "data" => ["msg" => "Migration concluída com sucesso!"]];
    }
}
