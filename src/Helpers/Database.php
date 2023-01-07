<?php

namespace App\Helpers;

use Exception;
use PDO;
use PDOException;

class Database
{
    private static ?Database $instance = null;
    private PDO $connection;

    private function __construct()
    {
        $config = Config::getConfig();

        try {
            $this->connection = new PDO(
                "mysql:host=" . $config['db']['host'] . ";port=3306;charset=utf8mb4;dbname={$config['db']['database']}",
                $config['db']['user'],
                $config['db']['password']
            );
        } catch (PDOException $e) {
            exit('Błąd podczas łączenia z bazą danych: ' . $e->getCode());
        }
    }

    public static function getInstance(): ?Database
    {
        if (!self::$instance) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

    /**
     * @throws Exception
     */
    final public function __clone()
    {
        throw new Exception('Feature disabled.');
    }

    /**
     * @throws Exception
     */
    final public function __wakeup()
    {
        throw new Exception('Feature disabled.');
    }
}