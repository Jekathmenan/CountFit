<?php

namespace CountFit\Database;

use PDO;
use PDOException;

/**
 * Class should be singleton to avoid multiple connections to Database.
 */
class DBConnection
{
    // TODO: Move these connection data to a config and config_local file.
    private static ?string $DB_HOST = "localhost";
    private static ?string $DB_NAME = "countfit";
    private static ?string $DB_USER = "root";
    private static ?string $DB_PASSWORD = "";
    private static ?PDO $pdo = null;

    // Make constructor private to prevent direct instantiation
    private function __construct() {}

    /**
     * 
     * Establish Database connection
     * 
     */
    public static function getInstance(): PDO
    {
        try {
            self::$pdo = new PDO("mysql:host=" . self::$DB_HOST . ";dbname=" . self::$DB_NAME . ";charset=utf8mb4", self::$DB_USER, self::$DB_PASSWORD);
        } catch (PDOException $ex) {

            if (error_reporting() & E_ALL) {
                echo 'Database connection failed ' . $ex->getMessage() . '</br>';
            }

            die();
        }
        return self::$pdo;
    }

    // Prevent cloning and unserialization of the singleton
    private function __clone() {}
    private function __wakeup() {}
}
