<?php


namespace CountFit\Models;

//require_once __DIR__ . '/DBConnection.php';

use CountFit\Database\DBConnection;
use PDO;
use PDOException;

class Bodypart
{
    private static ?PDO $pdo = null;
    public ?int $bodypartId;
    public ?string $bodypartName;
    public ?string $bodypartDesc;

    // Initialize static PDO from singleton DBConnection
    public static function initDB(): void
    {
        self::$pdo = DBConnection::getInstance();
    }

    // constructor
    public function __construct(?int $bodypartId = null, ?string $bodypartName = null, ?string $bodypartDesc = null)
    {
        Bodypart::initDB();

        // Initializing Properties
        $this->bodypartId = $bodypartId;
        $this->bodypartName = $bodypartName;
        $this->bodypartDesc = $bodypartDesc;
    }
    /**
     * 
     * Stores current properties to Bodypart table
     * 
     */
    public function save(): bool
    {
        $ret = false;


        if ($this->bodypartName === null) {
            // Log Error
            return false;
        }

        if (self::$pdo === null) {
            self::$pdo = DBConnection::getInstance();
        }

        try {
            $stmt = self::$pdo->prepare("INSERT INTO bodyparts (bodypartName, bodypartDesc) VALUES (:bodypartName, :bodypartDesc)");
            $stmt->execute([
                ':bodypartName' => $this->bodypartName,
                ':bodypartDesc' => $this->bodypartDesc
            ]);

            echo 'Saving bodyport to DB ' . $this->bodypartName . ' ' . $this->bodypartId . '</br>';
            $lastId = self::$pdo->lastInsertId();
            $this->bodypartId = (int) $lastId;

            $ret = true;
        } catch (PDOException $ex) {
            // Implement a singleton Class Logger and log the errors there.
            if (error_reporting() & E_ALL) {
                echo 'Error saving bodypart to DB ' . $ex->getMessage() . '</br>';
            }
        }

        return $ret;
    }
    /**
     * 
     * Helper function to check if Bodypart is connected to TrainingSession
     */
    private function isConnectedToTrainingSession(): bool
    {
        if (self::$pdo === null) {
            self::$pdo = DBConnection::getInstance();
        }

        try {
            $stmt = self::$pdo->prepare("SELECT count(*) FROM trainingsession2bodypart WHERE usersID = :usersId and tsID = :tsId and bodypartId = :bodypartId");
            $stmt->execute([
                ':usersId' => $_SESSION['userId'],
                ':tsId' => $_SESSION['ts_Id'],
                ':bodypartId' => $this->bodypartId
            ]);


            $count = $stmt->fetchColumn();
            return ($count > 0);
        } catch (PDOException $ex) {
            if (error_reporting() & E_ALL) {
                echo 'Some error occured while checking if Bodypart is connected to TrainingSession: ' . $ex->getMessage() . '</br>';
            }
            throw $ex;
        }
    }
}
