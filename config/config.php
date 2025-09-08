<?php
class Database {
    // IONOS database details
    private $host = "db5018579352.hosting-data.io";
    private $username = "crusertel_user";
    private $password = "Antoniocruz62";
    private $database = "crusertel_db";
    private $connection;
    
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
                
                $this->connection->set_charset("utf8mb4");
            } catch (Exception $e) {
                error_log("Database connection error: " . $e->getMessage());
                return null;
            }
        }
        return $this->connection;
    }
    
    public function __destruct() {
        if ($this->connection !== null) {
            $this->connection->close();
        }
    }
}
?>