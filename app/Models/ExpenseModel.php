<?php

namespace App\Models;

use App\Services\DatabaseService;

class ExpenseModel
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

    public function createExpense(int $userId, array $data): bool
    {
        $sql = "
            INSERT INTO
                expenses (title, amount, user_id, created_at, updated_at)
            VALUES (
                '" . $this->dbService->escape($data["title"]) . "',
                '" . $this->dbService->escape($data['amount']) . "',
                '" . $this->dbService->escape($userId) . "',
                '" . date("Y-m-d H:i:s") . "',
                '" . date("Y-m-d H:i:s") . "'
        )";

        return $this->dbService->query($sql);
    }

    public function updateExpense(int $userId, array $data): bool
    {
        $sql = "
            UPDATE
                expenses
            SET
        ";

        if (isset($data["amount"])) {
            $sql .= "
                amount = '" . $this->dbService->escape($data["amount"]) . "',
            ";
        }

        if (isset($data["title"])) {
            $sql .= "
                title = '" . $this->dbService->escape($data["title"]) . "',
            ";
        }

        $sql .= "
                updated_at = '" . date("Y-m-d H:i:s") . "'
            WHERE
                user_id = '" . $userId . "'
            AND
                id = '" . $this->dbService->escape($data["id"]) . "'
        ";

        return $this->dbService->query($sql);
    }

    public function deleteExpense(int $userId, int $id): bool
    {
        $sql = "
            DELETE FROM
                expenses
            WHERE
                user_id = '" . $userId . "'
            AND
                id = '" . $this->dbService->escape($id) . "'
        ";

        return $this->dbService->query($sql);
    }
}
