<?php
// session_manager.php

// Start or resume session
session_start();

/**
 * Set user session after successful login
 */
function setUserSession($userData) {
    $_SESSION['id'] = $userData['id'];
    $_SESSION['username'] = $userData['username'];
    $_SESSION['email'] = $userData['email'];
    $_SESSION['role'] = $userData['role'];
    $_SESSION['full_name'] = $userData['full_name'];
    $_SESSION['last_activity'] = time();
}

/**
 * Check if user is logged in and session is valid
 */
function isLoggedIn() {
    // Check if user session exists
    if (!isset($_SESSION['id']) || !isset($_SESSION['role'])) {
        redirectToLogin();
        return false;
    }

    // Check session timeout (30 minutes)
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
        destroySession();
        redirectToLogin("Session expired. Please login again.");
        return false;
    }

    // Update last activity time
    $_SESSION['last_activity'] = time();
    return true;
}

/**
 * Redirect to login page
 */
function redirectToLogin($message = "") {
    if ($message) {
        $message = urlencode($message);
        header("Location: ../Main/login.php?msg=" . $message);
    } else {
        header("Location: ../Main/login.php");
    }
    exit();
}

/**
 * Check user role and redirect if unauthorized
 */
function checkUserRole($requiredRole) {
    if (!isLoggedIn()) {
        redirectToLogin();
        return false;
    }
    
    if ($_SESSION['role'] !== $requiredRole) {
        // Redirect based on role
        if ($_SESSION['role'] === 'admin') {
            header("Location: ../admin/admin_dashboard.php");
        } else {
            header("Location: ../client/client_dashboard.php");
        }
        exit();
    }
    return true;
}

/**
 * Destroy session and cleanup
 */
function destroySession() {
    $_SESSION = array();
    
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time()-3600, '/');
    }
    
    session_destroy();
}

/**
 * Get current user data from session
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['id'],
        'username' => $_SESSION['username'],
        'email' => $_SESSION['email'],
        'role' => $_SESSION['role'],
        'full_name' => $_SESSION['full_name']
    ];
}

