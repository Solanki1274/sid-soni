<?php
class SessionManager {
    public function isLoggedIn() {
        // Logic to check if the user is logged in
        return isset($_SESSION['user']);
    }

    public function redirectToLogin($message) {
        // Redirect to the login page with a message
        header("Location: login.php?message=" . urlencode($message));
        exit;
    }

    public function getCurrentUser() {
        // Example logic to get current user
        return $_SESSION['user'] ?? null;
    }

    public function setUserSession($user) {
        // Set session data for the user
        $_SESSION['user'] = $user;
    }
}
class CustomSessionHandler {
    private $db;
    private $sessionTable = 'sessions';
    
    public function __construct($db) {
        $this->db = $db;
        $this->initSessionTable();
    }
    
    private function initSessionTable() {
        // Create sessions table if it doesn't exist
        $query = "CREATE TABLE IF NOT EXISTS {$this->sessionTable} (
            session_id VARCHAR(255) PRIMARY KEY,
            user_id INT NOT NULL,
            ip_address VARCHAR(45),
            user_agent VARCHAR(255),
            last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )";
        
        $this->db->query($query);
    }
    
    public function startSession($userId) {
        session_start();
        $sessionId = session_id();
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        
        // Clear any existing sessions for this user
        $this->clearUserSessions($userId);
        
        // Store new session
        $query = "INSERT INTO {$this->sessionTable} 
                 (session_id, user_id, ip_address, user_agent) 
                 VALUES (?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("siss", $sessionId, $userId, $ipAddress, $userAgent);
        $stmt->execute();
        
        $_SESSION['user_id'] = $userId;
        $_SESSION['last_activity'] = time();
    }
    
    private function clearUserSessions($userId) {
        $query = "DELETE FROM {$this->sessionTable} WHERE user_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
    }
    
    public function validateSession() {
        if (!isset($_SESSION['user_id'])) {
            return false;
        }
        
        $sessionId = session_id();
        $userId = $_SESSION['user_id'];
        
        // Check if session exists and is valid
        $query = "SELECT * FROM {$this->sessionTable} 
                 WHERE session_id = ? AND user_id = ?";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("si", $sessionId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            $this->destroySession();
            return false;
        }
        
        // Update last activity
        $_SESSION['last_activity'] = time();
        return true;
    }
    
    public function destroySession() {
        $sessionId = session_id();
        
        // Remove from database
        $query = "DELETE FROM {$this->sessionTable} WHERE session_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $sessionId);
        $stmt->execute();
        
        // Clear PHP session
        session_unset();
        session_destroy();
    }
    
    public function getUserData() {
        if (!isset($_SESSION['user_id'])) {
            return null;
        }
        
        $userId = $_SESSION['user_id'];
        $query = "SELECT id, username, email, full_name, role FROM users WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}

// Example usage:
class Auth {
    private $db;
    private $sessionHandler;
    
    public function __construct($db) {
        $this->db = $db;
        $this->sessionHandler = new CustomSessionHandler($db);
    }
    
    public function login($email, $password) {
        $query = "SELECT id, password FROM users WHERE email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        
        if ($result && password_verify($password, $result['password'])) {
            $this->sessionHandler->startSession($result['id']);
            return true;
        }
        return false;
    }
    
    public function logout() {
        $this->sessionHandler->destroySession();
    }
    
    public function isLoggedIn() {
        return $this->sessionHandler->validateSession();
    }
    
    public function getCurrentUser() {
        return $this->sessionHandler->getUserData();
    }
}
