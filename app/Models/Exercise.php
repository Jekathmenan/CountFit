<?php


namespace CountFit\Models;

//require_once __DIR__ . '/DBConnection.php';

use CountFit\Database\DBConnection;
use CountFit\Models\BaseModel;
use CountFit\Models\Model;
use PDO;
use PDOException;

class Exercise extends BaseModel implements Model
{
    private static ?PDO $pdo = null;
    private ?int $exerciseId;
    private ?string $exerciseName;

    public function __construct(?int $id = -1, ?string $name = null)
    {
        if ($id != -1) {
            $this->exerciseId = $id;
            $this->exerciseName = $name;
        } elseif ($id == -1 && $name != null) {

            $this->exerciseName = $name;
            $this->connect();
            echo 'after connecting: exercise Id is: ' . $this->exerciseId;
        } else {
            $this->exerciseId = -1;
        }
    }

    /**
     * 
     * Creates new Exercise if not available
     * Binds created or existing exercise to bodypart
     * 
     */
    public function create(): bool
    {
        $connected = $this->exerciseId != -1 ?  false : true;
        if ($this->exerciseId == -1) {
            // Try finding Exercise by Id 
            $connected = $this->connect();
        }

        if (!$connected) {
            // Exercise not found --> create new exercise dataset in db
            $this->save();
        }

        // Connecto Exercise with bodypart
        return $this->connectToBodypart();
    }

    /**
     * Connects this exercise to current bodypart (from Session)
     */
    private function connectToBodypart(): bool
    {
        if (self::$pdo === null) {
            self::$pdo = DBConnection::getInstance();
        }

        try {
            // echo 'Exercise name: ' . $this->exerciseName . '; Exercise Id: ' .  $this->exerciseId . ' Bodypart ID ' . $_SESSION['currentBpId'] .  ' Userid ' . $_SESSION['userId']  . '</br>';
            $stmt = self::$pdo->prepare("INSERT INTO bodyparts2exercise (bodypartID, exerciseId, usersId) VALUES (:bodypartId, :exerciseId, :usersId)");
            $stmt->execute([
                ':bodypartId' => $_SESSION['currentBpId'],
                ':exerciseId' => $this->exerciseId,
                ':usersId' => $_SESSION['userId']
            ]);



            return true;
        } catch (PDOException $ex) {
            if (error_reporting() & E_ALL) {
                echo 'Error connecting Exercise to Bodypart ' . $ex->getMessage() . '</br>';
            }
            return false;
        }
    }

    /**
     * Stores current Exercise
     */
    public function save(): bool
    {
        $ret = false;

        if ($this->exerciseName === null) {
            return false;
        }

        if (self::$pdo === null) {
            self::$pdo = DBConnection::getInstance();
        }

        try {
            $stmt = self::$pdo->prepare("INSERT INTO Exercises (exerciseName) VALUES (:exerciseName)");
            $stmt->execute([
                ':exerciseName' => $this->exerciseName
            ]);

            $lastId = self::$pdo->lastInsertId();
            $this->exerciseId = (int) $lastId;

            $ret = true;
        } catch (PDOException $ex) {
            if (error_reporting() & E_ALL) {
                echo 'Error saving bodypart to DB ' . $ex->getMessage() . '</br>';
            }
        }

        return $ret;
    }

    /**
     * @return Exercises 
     */
    public function getAll()
    {
        if (self::$pdo === null) {
            self::$pdo = DBConnection::getInstance();
        }

        try {
            $stmt = self::$pdo->prepare("SELECT e.* FROM exercises e LEFT JOIN bodyparts2exercise bpe ON e.exerciseId = bpe.exerciseId AND bpe.usersId = :usersId;");
            $stmt->execute([
                'usersId' => $_SESSION['userId'],
            ]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $exercises = [];

            foreach ($results as $row) {
                $exercises[] = new Exercise(id: $row['exerciseID'], name: $row['exerciseName']);
            }

            return $exercises;
        } catch (PDOException $ex) {
            if (error_reporting() & E_ALL) {
                echo 'Encountered error while reading all Exercises ' . $ex->getMessage();
            }

            return null;
        }
    }

    /**
     * Tries to find cu
     */
    public function connect(): bool
    {
        // Search Exercise by name
        $exercises = Exercise::getByName($this->exerciseName);

        if (count($exercises) >= 1) {
            // Connect first exercise to this object
            $e = $exercises[0];
            $this->exerciseId  = $e->getId();
            $this->exerciseName  = $e->getName();
            echo 'Exercise can be connected </br>';

            return true;
        }

        $this->exerciseId = 1;
        echo 'Exercise can not be connected </br>';
        return false;
    }

    public static function getAllRelevant()
    {
        if (self::$pdo === null) {
            self::$pdo = DBConnection::getInstance();
        }

        try {
            $exercises = [];
            $stmt = self::$pdo->prepare("SELECT e.* FROM exercises e LEFT JOIN bodyparts2exercise bpe ON e.exerciseId = bpe.exerciseId AND bpe.usersId = :usersId WHERE bpe.bodypartId = :bpId;");
            $stmt->execute([
                ':usersId' => $_SESSION['userId'],
                ':bpId' => $_SESSION['currentBpId']
            ]);

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);


            foreach ($results as $row) {
                $exercises[] = new Exercise(id: $row['exerciseId'], name: $row['exerciseName']);
            }

            return $exercises;
        } catch (PDOException $ex) {
            if (error_reporting() & E_ALL) {
                echo 'Encountered error while reading all Exercises ' . $ex->getMessage();
            }

            return null;
        }
    }

    /**
     * @return Exercise connected to the current user
     * Search Exercise by Name
     */
    public static function getById(int $id)
    {
        if (self::$pdo === null) {
            self::$pdo = DBConnection::getInstance();
        }

        try {
            $stmt = self::$pdo->prepare("SELECT e.* FROM exercises e LEFT JOIN bodyparts2exercise bpe ON e.exerciseId = bpe.exerciseId AND bpe.usersId = :usersId WHERE e.exerciseId = :exerciseId;");
            $stmt->execute([
                ':usersId' => $_SESSION['userId'],
                ':exerciseId' => $id
            ]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            // $exercises = [];

            if ($result !== false) {
                return new Exercise(id: $result['exerciseId'], name: $result['exerciseName']);
            }

            /*foreach ($results as $row) {
                $exercises[] = new Exercise(id: $row['exerciseID'], name: $row['exerciseName']);
            }*/
        } catch (PDOException $ex) {
            if (error_reporting() & E_ALL) {
                echo 'Encountered error while reading all Exercises ' . $ex->getMessage();
            }

            return null;
        }
    }

    /**
     * @return Exercise connected to the current user
     * Search Exercise by Name
     */
    public static function getByName(string $name): array
    {

        if (self::$pdo === null) {
            self::$pdo = DBConnection::getInstance();
        }

        $exercises = [];
        try {
            $stmt = self::$pdo->prepare("SELECT e.* FROM exercises e LEFT JOIN bodyparts2exercise bpe ON e.exerciseId = bpe.exerciseId AND bpe.usersId = :usersId WHERE e.exerciseName = :exerciseName;");
            $stmt->execute([
                ':usersId' => $_SESSION['userId'],
                ':exerciseName' => $name
            ]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $exercises = [];

            foreach ($results as $row) {
                $exercises[] =  new Exercise(id: $row['exerciseId'], name: $row['exerciseName']);
            }

            return $exercises;
        } catch (PDOException $ex) {
            if (error_reporting() & E_ALL) {
                echo 'Encountered error while reading all Exercises ' . $ex->getMessage();
            }
        }
        return $exercises;
    }

    /**
     * Adds a set to this exercise
     */
    public function addSet(?float $weight = -1, ?int $reps = -1)
    {
        if ($weight == -1 || $reps == -1) {
            if (error_reporting() & E_ALL) {
                echo 'Either weight or rep invalid! </br>';
            }
            return false;
        }

        if (self::$pdo === null) {
            self::$pdo = DBConnection::getInstance();
        }

        // saveSet
        try {
            $date = date("d.m.Y");

            echo 'Date: ' . $date . ' Weight: ' . $weight . ' Reps: ' . $reps . ' User: ' . $_SESSION['userId'] . 'Exercise Id: ' . $this->exerciseId . '</br>';
            $stmt = self::$pdo->prepare("INSERT INTO sets (setDate, setWeight, setReps, usersId, exerciseId) VALUES (:date, :weight, :reps, :usersId, :exId);");
            $stmt->execute([
                ':date' => $date,
                ':weight' => $weight,
                ':reps' => $reps,
                ':usersId' => $_SESSION['userId'],
                ':exId' => $this->exerciseId
            ]);


            return  true;
        } catch (PDOException $ex) {
            if (error_reporting() & E_ALL) {
                echo 'Error saving bodypart to DB ' . $ex->getMessage() . '</br>';
            }
        }
    }

    /**
     * Getter for exerciseID
     */
    public function getId(): int
    {
        return $this->exerciseId;
    }

    /**
     * Getter for exerciseName
     */
    public function getName(): string
    {
        return $this->exerciseName;
    }

    /**
     * Setter for exerciseName
     */
    public function setName(string $name)
    {
        // validate input String 
        $this->validateSafeString($name);

        // Set exerciseName
        $this->exerciseName = $name;
    }
}
