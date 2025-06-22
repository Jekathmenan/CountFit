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

    /**
     * 
     * Helper function to bind current trainingSessionToSession
     * 
     */
    private function bindThisBodyPart(string $name): bool
    {
        $bp = self::getBodypartByName(name: $name);

        if ($bp == null) {
            return false;
        }

        $this->bodypartId = $bp->bodypartId;
        return !($bp === null);
    }

    /**
     * 
     * Returns Bodypart by name
     * 
     */
    public static function getBodypartById(int $id): ?Bodypart
    {
        $bodypart = self::getAllBodyparts();

        if ($bodypart === null) {
            return null;
        }
        // get first Bodypart with given Name
        $bpById = array_filter($bodypart, function ($bp) use ($id) {
            return $bp->bodypartId === $id;
        });

        return reset($bpById) ?: null;
    }

    /**
     * @return Bodypart
     * Returns Bodypart by name
     * 
     */
    public static function getBodypartByName(string $name): ?Bodypart
    {
        if (self::$pdo === null) {
            self::$pdo = DBConnection::getInstance();
        }

        $bodyparts = self::getAllBodyparts();

        if ($bodyparts === null) {
            return null;
        }

        // get first Bodypart with given Name
        // Maybe move this to db, by implementing this filter via sql-filter?
        $bpByName = array_filter($bodyparts, function ($bp) use ($name) {
            return $bp->bodypartName === strtoupper($name);
        });

        return reset($bpByName) ?: null;
    }

    /**
     * @return Bodypart[]
     */
    public static function getAllBodyparts(): ?array
    {
        if (self::$pdo === null) {
            self::$pdo = DBConnection::getInstance();
        }
        try {
            $stmt = self::$pdo->prepare("SELECT bodypartID, bodypartName, bodypartDesc FROM bodyparts");
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $bodyparts = [];

            foreach ($results as $row) {
                $bodyparts[] = new Bodypart(bodypartId: $row['bodypartID'], bodypartName: $row['bodypartName'], bodypartDesc: $row['bodypartDesc']);
            }

            return $bodyparts;
        } catch (PDOException $ex) {
            if (error_reporting() & E_ALL) {
                echo 'Encountered error while reading all Bodyparts ' . $ex->getMessage();
            }

            return null;
        }
    }

    /**
     * @return TrainingSession[]
     */
    public static function getCurrentUsersBodyparts(): ?array
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

    public static function getAllRelevant(): ?array
    {
        if (self::$pdo === null) {
            self::$pdo = DBConnection::getInstance();
        }

        try {
            $stmt = self::$pdo->prepare("SELECT bp.* FROM bodyparts bp LEFT JOIN trainingsession2bodypart tbp ON bp.bodypartId = tbp.bodypartId WHERE usersId = :usersId AND tsId = :tsId;");
            $stmt->execute([
                ':usersId' => $_SESSION['userId'],
                ':tsId' => $_SESSION['ts_Id']
            ]);

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $bodyparts = [];
            foreach ($results as $row) {
                $bodyparts[] = new Bodypart(bodypartId: $row['bodypartId'], bodypartName: $row['bodypartName'], bodypartDesc: $row['bodypartDesc']);
            }

            return $bodyparts;
        } catch (PDOException $ex) {
            if (error_reporting() & E_ALL) {
                echo 'Encountered error while reading all Exercises ' . $ex->getMessage();
            }

            return null;
        }
    }

    /**
     * @return Bodypart connected to the current user
     * Search Exercise by Name
     */
    public static function getById(int $id)
    {
        if (self::$pdo === null) {
            self::$pdo = DBConnection::getInstance();
        }

        try {
            $stmt = self::$pdo->prepare("SELECT b.* FROM bodyparts b LEFT JOIN trainingsession2bodypart tbp ON b.bodypartId = tbp.bodypartId AND tbp.usersId = :usersId WHERE b.bodypartId = :bodypartId;");
            $stmt->execute([
                ':usersId' => $_SESSION['userId'],
                ':bodypartId' => $id
            ]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            // $exercises = [];

            if ($result !== false) {
                return new Bodypart(bodypartId: $result['bodypartId'], bodypartName: $result['bodypartName']);
            }

            /*foreach ($results as $row) {
                $exercises[] = new Exercise(id: $row['exerciseID'], name: $row['exerciseName']);
            }*/
        } catch (PDOException $ex) {
            if (error_reporting() & E_ALL) {
                echo 'Encountered error while reading Bodypart by Id ' . $ex->getMessage();
            }

            return null;
        }
    }

    /**
     * @return Bodypart[]
     */
    public static function getBodypartsForTrainingSession(): ?array
    {
        if (self::$pdo === null) {
            // establish Database Connection
            self::$pdo = DBConnection::getInstance();
        }

        try {
            $stmt = self::$pdo->prepare("SELECT bp.bodypartID, bp.bodypartName, bp.bodypartDesc FROM bodypart bp LEFT JOIN trainingsession2bodypart tbp ON bp.bodypartID = tbp.bodypartId AND bp.tsId = :tsId AND tbp.usersID = :usersId");
            $stmt->execute([
                ':usersId' => $_SESSION['userId'],
                ':tsId' => $_SESSION['ts_Id'],
                ':bodypartId' => self::$bodypartId
            ]);

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // echo ' Current Users TrainingSessions' . count($results);
            $bodyparts = [];
            foreach ($results as $row) {
                $bodyparts[] = new Bodypart(bodypartId: $row['bodypartID'], bodypartName: $row['bodypartName'], bodypartDesc: $row['bodypartDesc']);
            }

            return $bodyparts;
        } catch (PDOException $ex) {
            if (error_reporting() & E_ALL) {
                echo 'Encountered error while reading all TrainingSessions ' . $ex->getMessage();
            }

            return null;
        }
    }
}
