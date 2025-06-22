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
    // constructor
    public function __construct(?int $bodypartId = null, ?string $bodypartName = null, ?string $bodypartDesc = null)
    {
        Bodypart::initDB();

        // Initializing Properties
        $this->bodypartId = $bodypartId;
        $this->bodypartName = $bodypartName;
        $this->bodypartDesc = $bodypartDesc;
    }
}
