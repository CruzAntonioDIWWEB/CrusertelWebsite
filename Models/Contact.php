<?php
namespace Models;

require_once __DIR__ . '/../config/config.php';

class Contact {
    private $db;
    
    public function __construct() {
        $database = new \Database();
        $this->db = $database->getConnection();
    }
    
    public function save($name, $email, $phone, $subject, $message) {
        if (!$this->db) {
            error_log("Database connection failed in Contact::save()");
            return false;
        }
        
        $sql = "INSERT INTO contact_submissions (name, email, phone, subject, message, ip_address, user_agent, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        
        if ($stmt === false) {
            error_log("Error preparing contact statement: " . $this->db->error);
            return false;
        }
        
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        // FIXED: Use correct status value from database enum
        $status = 'new';
        
        $stmt->bind_param("ssssssss", $name, $email, $phone, $subject, $message, $ip, $user_agent, $status);
        
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
            $stmt->close();
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
        
        $sql = "UPDATE contact_submissions SET status = 'read', updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }
    
    public function markAsResponded($id) {
        if (!$this->db) {
            return false;
        }
        
        $sql = "UPDATE contact_submissions SET status = 'responded', updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }
    
    public function archive($id) {
        if (!$this->db) {
            return false;
        }
        
        $sql = "UPDATE contact_submissions SET status = 'archived', updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }
    
    public function getById($id) {
        if (!$this->db) {
            return null;
        }
        
        $sql = "SELECT * FROM contact_submissions WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $contact = $result->fetch_assoc();
        $stmt->close();
        
        return $contact;
    }
}
?>