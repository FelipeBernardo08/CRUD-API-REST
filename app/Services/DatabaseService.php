<?php

namespace App\Services;

use mysqli;

class DatabaseService
{
    private $db;
    private $database;

    public function __construct(string $host, string $user, string $pass, string $database)
    {
        $this->database = $database;
        $this->db = new mysqli($host, $user, $pass, $database);
    }

    public function query(string $sql): mixed
    {

        return $this->db->query($sql);
    }


    public function escape(string $data): string
    {
        return $this->db->escape_string($data);
    }

    public function migration(): void
    {
        $sqlCreateDatabase = "
            DROP DATABASE IF EXISTS `" . $this->database . "`;
            CREATE DATABASE `" . $this->database . "`;
        ";

        sleep(3);

        if ($this->db->multi_query($sqlCreateDatabase)) {

            while ($this->db->more_results() && $this->db->next_result()) {
            }

            $this->db->select_db($this->database);

            $sqlCreateTables = "
                CREATE TABLE users (
                    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(255) NOT NULL,
                    email VARCHAR(255) NOT NULL UNIQUE,
                    password VARCHAR(255) NOT NULL,
                    created_at VARCHAR(255) NOT NULL,
                    updated_at VARCHAR(255) NOT NULL
                );
        
                CREATE TABLE expenses (
                    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    title VARCHAR(255) NOT NULL,
                    user_id INT NOT NULL,
                    amount FLOAT,
                    created_at VARCHAR(255) NOT NULL,
                    updated_at VARCHAR(255) NOT NULL,
                    FOREIGN KEY (user_id) REFERENCES users(id)
                );
            ";

            $this->db->multi_query($sqlCreateTables);
        }
    }
}
