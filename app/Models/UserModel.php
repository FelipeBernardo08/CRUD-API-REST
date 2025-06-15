<?php

namespace App\Models;

use App\Services\DatabaseService;

class UserModel
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

    public function createUser(array $data): bool
    {
        $sql = "
            INSERT INTO
                users (name, email, password, created_at, updated_at)
            VALUES (
            '" . $data["name"] . "',
            '" . $data["email"] . "',
            '" . password_hash($data["password"], PASSWORD_BCRYPT) . "',
            '" . date("Y-m-d H:i:s") . "',
            '" . date("Y-m-d H:i:s") . "'
        )";

        return $this->dbService->query($sql);
    }

    public function getUserByEmail(string $email)
    {
        $sql = "
            SELECT
                *
            FROM
                users
            WHERE
                email = '" . $email . "'
        ";

        $response = $this->dbService->query($sql);

        if (!$response->num_rows) {
            return [];
        }

        $row = $response->fetch_assoc();

        return $row;
    }

    public function updateUser(int $userId, array $dataUpdate): bool
    {
        $sql = "
            UPDATE
                users
            SET
        ";

        if (isset($dataUpdate["name"])) {
            $sql .= "
                name = '" . $dataUpdate["name"] . "'
            ";
        }

        if (isset($dataUpdate["password"])) {
            $sql .= ",
                password = '" . $dataUpdate["password"] . "'
            ";
        }

        $sql .= "
            WHERE
                id = " . $userId . "
        ";

        return $this->dbService->query($sql);
    }
}
