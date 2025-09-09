<?php
class Database {
    // FIXED: Updated database name to match actual database
    private $host;
    private $username;
    private $password;
    private $database;
    private $connection;
    
    public function __construct() {
        // Use environment variables if available, otherwise fallback to actual values
        $this->host = $_ENV['DB_HOST'] ?? "db5018579352.hosting-data.io";
        $this->username = $_ENV['DB_USERNAME'] ?? "crusertel_user";
        $this->password = $_ENV['DB_PASSWORD'] ?? "Antoniocruz62";
        // CRITICAL FIX: Changed from 'crusertel_db' to actual database name
        $this->database = $_ENV['DB_NAME'] ?? "dbs14739637";
    }
    
    public function getConnection() {
        if ($this->connection === null) {
            try {
                $this->connection = new mysqli(
                    $this->host, 
                    $this->username, 
                    $this->password, 
                    $this->database
                );
                
                if ($this->connection->connect_error) {
                    throw new Exception("Connection failed: " . $this->connection->connect_error);
                }
                
                // Set charset to handle special characters properly
                $this->connection->set_charset("utf8mb4");
                
                // Set timezone
                $this->connection->query("SET time_zone = '+00:00'");
                
            } catch (Exception $e) {
                error_log("Database connection error: " . $e->getMessage());
                return null;
            }
        }
        return $this->connection;
    }
    
    /**
     * Test database connection
     */
    public function testConnection() {
        $conn = $this->getConnection();
        if ($conn && $conn->ping()) {
            return true;
        }
        return false;
    }
    
    /**
     * Get database version
     */
    public function getVersion() {
        $conn = $this->getConnection();
        if ($conn) {
            return $conn->server_info;
        }
        return null;
    }
    
    /**
     * Close connection
     */
    public function closeConnection() {
        if ($this->connection !== null) {
            $this->connection->close();
            $this->connection = null;
        }
    }
    
    public function __destruct() {
        $this->closeConnection();
    }
}

// Global configuration constants
define('SITE_NAME', 'Crusertel');
define('SITE_EMAIL', 'info@crusertel.es');
define('SITE_PHONE', '958 01 64 11');
define('SITE_ADDRESS', 'Calle Arabial 45 local 18');
define('BASE_URL', '/dashboard/CrusertelWebsite/');
define('ASSETS_URL', BASE_URL . 'assets/');
?>