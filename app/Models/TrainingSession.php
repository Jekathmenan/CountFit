<?php


namespace CountFit\Models;

//require_once __DIR__ . '/DBConnection.php';

use CountFit\Database\DBConnection;
use PDO;
use PDOException;

class TrainingSession
{
    private static ?PDO $pdo = null;
    public ?int $tsId;
    public ?string $tsName;
    public ?string $tsDesc;

    // Initialize static PDO from singleton DBConnection
    public static function initDB(): void
    {
        self::$pdo = DBConnection::getInstance();
    }

    // constructor
    public function __construct(?int $tsId = null, ?string $tsName = null, ?string $tsDesc = null)
    {
        TrainingSession::initDB();

        // Initializing Properties
        $this->tsId = $tsId;
        $this->tsName = $tsName;
        $this->tsDesc = $tsDesc;
    }

    /**
     * Handles TrainingSession-Creation logic
     * 
     * Creates TrainingSession and connects it to user
     * 
     */
    public function createTrainingSession()
    {
        // Check if Training Session exists
        $userBound = $this->bindThisTrainingSession(name: $this->tsName);

        if (!$userBound) {
            // Store Training Session in DB, if it exists
            $this->save();
        }

        // Connect TS To User
        if (!$this->isConnectedToUser()) {
            echo 'TrainingSession is not connected to user. </br>';
            $this->connectToUser();
        }
    }

    /**
     * 
     * Stores current properties to TrainingSession table
     * 
     */
    public function save(): bool
    {
        if ($this->tsName === null) {
            // Log Error
            return false;
        }

        if (self::$pdo === null) {
            self::$pdo = DBConnection::getInstance();
        }

        try {
            $stmt = self::$pdo->prepare("INSERT INTO trainingsession (tsName, tsDesc) VALUES (:tsName, :tsDesc)");
            $stmt->execute([
                ':tsName' => $this->tsName,
                ':tsDesc' => $this->tsDesc
            ]);

            echo 'Saving user to DB ' . $this->tsName . ' ' . $this->tsId . '</br>';
            $lastId = self::$pdo->lastInsertId();
            $this->tsId = (int) $lastId;

            return  true;
        } catch (PDOException $ex) {
            // Implement a singleton Class Logger and log the errors there.
            if (error_reporting() & E_ALL) {
                echo 'Error saving user to DB ' . $ex->getMessage() . '</br>';
            }

            return false;
        }
    }

    /**
     * 
     * Helper function to connect TrainingSession to User --> Inserts userId and tsId into connector table users2trainingsession.
     * 
     */
    private function connectToUser()
    {
        if (self::$pdo === null) {
            self::$pdo = DBConnection::getInstance();
        }

        try {

            $stmt = self::$pdo->prepare("INSERT INTO users2trainingsession (usersId, tsId) VALUES (:usersId, :tsId)");
            $stmt->execute([
                ':usersId' => $_SESSION['userId'],
                ':tsId' => $this->tsId
            ]);
        } catch (PDOException $ex) {
            if (error_reporting() & E_ALL) {
                echo 'Error connecting Trainingsession to user ' . $ex->getMessage() . '</br>';
            }
            // Implement a singleton Class Logger and log the errors there.
        }
    }

    /**
     * 
     * Helper function to check if TrainingSession is connected to user
     */
    private function isConnectedToUser(): bool
    {
        if (self::$pdo === null) {
            self::$pdo = DBConnection::getInstance();
        }

        try {
            $stmt = self::$pdo->prepare("SELECT count(*) FROM users2trainingsession WHERE usersID = :usersId and tsID = :tsId");
            $stmt->execute([
                ':usersId' => $_SESSION['userId'],
                ':tsId' => $this->tsId
            ]);


            $count = $stmt->fetchColumn();
            return ($count > 0);
        } catch (PDOException $ex) {
            if (error_reporting() & E_ALL) {
                echo 'Some error occured while checking if TrainingSession is connected to User: ' . $ex->getMessage() . '</br>';
            }
            throw $ex;
        }
    }

    /**
     * 
     * Helper function to bind current trainingSessionToSession
     * 
     */
    private function bindThisTrainingSession(string $name): bool
    {
        $ts = self::getTrainingSessionByName(name: $name);

        if ($ts == null) {
            return false;
        }

        $this->tsId = $ts->tsId;
        return !($ts === null);
    }

    /**
     * 
     * Returns TrainingSession by name
     * 
     */
    public static function getTrainingSessionById(int $id): ?TrainingSession
    {
        $ts = self::getAllTrainingSessions();

        if ($ts === null) {
            return null;
        }
        // get first TrainingSession with given Name
        $tsByName = array_filter($ts, function ($ts) use ($id) {
            return $ts->tsName === strtoupper($id);
        });

        return reset($tsByName) ?: null;
    }

    /**
     * @return TrainingSession
     * Returns TrainingSession by name
     * 
     */
    public static function getTrainingSessionByName(string $name): ?TrainingSession
    {
        if (self::$pdo === null) {
            self::$pdo = DBConnection::getInstance();
        }

        $ts = self::getAllTrainingSessions();

        if ($ts === null) {
            return null;
        }

        // get first TrainingSession with given Name
        $tsByName = array_filter($ts, function ($ts) use ($name) {
            return $ts->tsName === strtoupper($name);
        });

        return reset($tsByName) ?: null;
    }

    /**
     * @return TrainingSession[]
     */
    public static function getAllTrainingSessions(): ?array
    {
        if (self::$pdo === null) {
            self::$pdo = DBConnection::getInstance();
            //throw new \Exception('PDO connection not initialized. Call TrainingSession::initPDO() first.');
        }
        try {
            $stmt = self::$pdo->prepare("SELECT tsID, tsName, tsDesc FROM trainingsession");
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $trainingSessions = [];
            foreach ($results as $row) {
                $trainingSessions[] = new TrainingSession(tsId: $row['tsID'], tsName: $row['tsName'], tsDesc: $row['tsDesc']);
            }

            return $trainingSessions;
        } catch (PDOException $ex) {
            if (error_reporting() & E_ALL) {
                echo 'Encountered error while reading all TrainingSessions ' . $ex->getMessage();
            }

            return null;
        }
    }

    /**
     * @return TrainingSession[]
     */
    public static function getCurrentUsersTrainingSessions(): ?array
    {
        if (self::$pdo === null) {
            // establish Database Connection
            self::$pdo = DBConnection::getInstance();
        }

        try {
            $stmt = self::$pdo->prepare("SELECT ts.tsID, ts.tsName, ts.tsDesc FROM trainingsession ts LEFT JOIN users2trainingsession uts ON ts.tsID = uts.tsId AND uts.usersID = :usersId");
            $stmt->execute([
                ':usersId' => $_SESSION['userId']
            ]);

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // echo ' Current Users TrainingSessions' . count($results);
            $trainingSessions = [];
            foreach ($results as $row) {
                $trainingSessions[] = new TrainingSession(tsId: $row['tsID'], tsName: $row['tsName'], tsDesc: $row['tsDesc']);
            }

            return $trainingSessions;
        } catch (PDOException $ex) {
            if (error_reporting() & E_ALL) {
                echo 'Encountered error while reading all TrainingSessions ' . $ex->getMessage();
            }

            return null;
        }
    }
}
