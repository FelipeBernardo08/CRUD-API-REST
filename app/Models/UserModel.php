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
                '" . $this->dbService->escape($data["name"]) . "',
                '" . $this->dbService->escape($data["email"]) . "',
                '" . password_hash($this->dbService->escape($data["password"]), PASSWORD_BCRYPT) . "',
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
                email = '" . $this->dbService->escape($email) . "'
        ";

        $response = $this->dbService->query($sql);

        if (!$response->num_rows) {
            return [];
        }

        $row = $response->fetch_assoc();

        return $row;
    }

    public function getUserWithExpenseByEmail(string $email)
    {
        $sql = "
            SELECT
                u.id as user_id,
                u.name,
                ex.title,
                ex.amount,
                ex.id as expense_id
            FROM
                users as u
            LEFT JOIN
                expenses as ex
            ON
                u.id = ex.user_id
            WHERE
                email = '" . $this->dbService->escape($email) . "'
        ";

        $response = $this->dbService->query($sql);

        if (!$response->num_rows) {
            return [];
        }

        $result = [];
        while ($row = $response->fetch_assoc()) {
            $result[] = $row;
        }

        $finalData = [];
        $finalData["user_id"] = (int) $result[0]["user_id"];
        $finalData["name"] = $result[0]["name"];
        $finalData["expenses"] = [];
        $finalData["expenses_total"] = 0;

        foreach ($result as $res) {
            $finalData["expenses"][] = [
                "expense_id" => (int) $res["expense_id"],
                "title" => $res["title"],
                "amount" => (float) $res["amount"],
            ];

            $finalData["expenses_total"] += $res["amount"];
        }

        return $finalData;
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
                name = '" . $this->dbService->escape($dataUpdate["name"]) . "',
            ";
        }

        if (isset($dataUpdate["password"])) {
            $sql .= "
                password = '" . $this->dbService->escape($dataUpdate["password"]) . "',
            ";
        }

        $sql .= "
                updated_at = '" . date("Y-m-d H:i:s") . "'
            WHERE
                id = " . $userId . "
        ";

        return $this->dbService->query($sql);
    }
}
