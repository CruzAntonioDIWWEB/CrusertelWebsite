<?php
namespace Models;

require_once __DIR__ . '/../config/config.php';

class Contact {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    public function save($name, $email, $phone, $subject, $message) {
        if (!$this->db) {
            return false;
        }
        
        $sql = "INSERT INTO contact_submissions (name, email, phone, subject, message, ip_address, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        
        if ($stmt === false) {
            error_log("Error preparing contact statement: " . $this->db->error);
            return false;
        }
        
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        $stmt->bind_param("sssssss", $name, $email, $phone, $subject, $message, $ip, $user_agent);
        
        $result = $stmt->execute();
        
        if (!$result) {
            error_log("Error executing contact statement: " . $stmt->error);
        }
        
        $stmt->close();
        return $result;
    }
    
    public function getAll($status = null) {
        if (!$this->db) {
            return [];
        }
        
        if ($status) {
            $sql = "SELECT * FROM contact_submissions WHERE status = ? ORDER BY created_at DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("s", $status);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $sql = "SELECT * FROM contact_submissions ORDER BY created_at DESC";
            $result = $this->db->query($sql);
        }
        
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        
        return [];
    }
    
    public function markAsRead($id) {
        if (!$this->db) {
            return false;
        }
        
        $sql = "UPDATE contact_submissions SET status = 'read' WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        
        return $stmt->execute();
    }
}
?>