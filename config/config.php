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

// IONOS Production paths
define('BASE_URL', '/');
define('ASSETS_URL', BASE_URL . 'assets/');
define('IMG_URL', ASSETS_URL . 'img/');

// Image category paths
define('IMG_BACKGROUND_URL', IMG_URL . 'background/');
define('IMG_PEOPLE_URL', IMG_URL . 'people/');
define('IMG_TARIFAS_URL', IMG_URL . 'tarifas/');
define('IMG_LOGO_URL', IMG_URL . 'logo/');

/**
 * Helper functions for image URLs
 */
function getImageUrl($filename, $subfolder = '') {
    if (!empty($subfolder)) {
        return IMG_URL . $subfolder . '/' . $filename;
    }
    return IMG_URL . $filename;
}

function getBackgroundImageUrl($filename) {
    return IMG_BACKGROUND_URL . $filename;
}

function getPeopleImageUrl($filename) {
    return IMG_PEOPLE_URL . $filename;
}

function getTarifaImageUrl($filename) {
    return IMG_TARIFAS_URL . $filename;
}

function getLogoImageUrl($filename) {
    return IMG_LOGO_URL . $filename;
}
?>