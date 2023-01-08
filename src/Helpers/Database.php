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
    //If there is no instance create new and return
    //If db instance exist return existing one
    public static function getInstance(): ?Database
    {
        if (!self::$instance) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    //Get db connection
    public function getConnection(): PDO
    {
        return $this->connection;
    }

    /**
     * @throws Exception
     */
    //Prevent from db object cloning
    final public function __clone()
    {
        throw new Exception('Feature disabled.');
    }

    /**
     * @throws Exception
     */
    //Disable wakeup method, protection against restoring lost connections to the database
    final public function __wakeup()
    {
        throw new Exception('Feature disabled.');
    }
}