<?php

namespace App\Controllers;

use App\Models\ExpenseModel;

class ExpenseController
{
    private $expenseModel;

    public function __construct()
    {
        $this->expenseModel = new ExpenseModel();
    }

    public function createExpense(array $request): array
    {
        $userId = $request["authUser"]["id"];

        if (!isset($request["title"]) || !isset($request["amount"])) {
            return [
                "success" => false,
                "error" => "Payload incorreto!"
            ];
        }

        $responseCreate = $this->expenseModel->createExpense($userId, $request);

        if (!$responseCreate) {
            return [
                "success" => false,
                "data" => [
                    "msg" => "Erro ao criar despesa, tente novamente mais tarde!"
                ]
            ];
        }

        return [
            "success" => true,
            "data" => [
                "msg" => "Despesa criada com sucesso!"
            ]
        ];
    }

    public function updateExpense(array $request): array
    {
        $userId = $request["authUser"]["id"];

        if (!isset($request["amount"]) && !isset($request["title"]) && !isset($request["id"])) {
            return [
                "success" => false,
                "error" => "Payload incorreto!"
            ];
        }

        $respUpdate = $this->expenseModel->updateExpense($userId, $request);

        if (!$respUpdate) {
            return [
                "success" => false,
                "error" => "Erro ao atualizar despesa, tente novamnte mais tarde!"
            ];
        }

        return [
            "success" => true,
            "data" => [
                "msg" => "Despesa atualizada com sucesso!"
            ]
        ];
    }

    public function deleteExpense(array $request): array
    {
        $userId = $request["authUser"]["id"];

        if (!isset($request["id"])) {
            return [
                "success" => false,
                "error" => "Payload incorreto!"
            ];
        }

        $responseDelete = $this->expenseModel->deleteExpense($userId, $request["authUser"]["id"]);

        if (!$responseDelete) {
            return [
                "success" => false,
                "error" => "Erro ao excluir despesa, tente novamente mais tarde!"
            ];
        }

        return [
            "success" => true,
            "data" => [
                "msg" => "Despesa excluida com sucesso!"
            ]
        ];
    }
}
