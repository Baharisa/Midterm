<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Load environment
$envFile = file_exists(__DIR__ . '/../.env.local') ? '.env.local' : '.env';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../', $envFile);
$dotenv->load();

class Database {
    private $host;
    private $port;
    private $dbname;
    private $username;
    private $password;

    public $conn;

    public function __construct() {
        $this->host = $_ENV['DB_HOST'] ?? 'localhost';
        $this->port = $_ENV['DB_PORT'] ?? '5432'; // fallback for Render
        $this->dbname = $_ENV['DB_NAME'] ?? 'quotesdb1';
        $this->username = $_ENV['DB_USER'] ?? 'postgres';
        $this->password = $_ENV['DB_PASS'] ?? '';
    }

    public function getConnection() {
        $this->conn = null;

        try {
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname}";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo json_encode([
                "message" => "Database connection error",
                "error" => $e->getMessage()
            ]);
            exit;
        }

        return $this->conn;
    }
}
