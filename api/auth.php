<?php
require_once '../config/convex.php';
session_start();

$convex = new ConvexDB();

// Handle both POST and GET 'action'
$action = $_REQUEST['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'register') {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $role = $_POST['role'] ?? 'user';

        if (empty($name) || empty($email) || empty($password)) {
            header("Location: ../register.php?error=All fields are required");
            exit();
        }

        // Domain restriction check
        if (!preg_match('/@kiit\.ac\.in$/i', $email)) {
            header("Location: ../register.php?error=Only @kiit.ac.in emails are allowed");
            exit();
        }

        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $result = $convex->createUser($name, $email, $passwordHash, $role);

        if (isset($result['error'])) {
            header("Location: ../register.php?error=" . urlencode($result['error']));
        } else {
            header("Location: ../login.php?success=Account created successfully. Please login.");
        }
        exit();
    }

    if ($action === 'login') {
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        if (empty($email) || empty($password)) {
            header("Location: ../login.php?error=Email and password are required");
            exit();
        }

        // Domain restriction check
        if (!preg_match('/@kiit\.ac\.in$/i', $email)) {
            header("Location: ../login.php?error=Only @kiit.ac.in emails are allowed");
            exit();
        }

        $user = $convex->loginUser($email);

        if (isset($user['error'])) {
            header("Location: ../login.php?error=" . urlencode($user['error']));
        } elseif (!$user) {
            header("Location: ../login.php?error=Account not detected in system");
        } else {
            if (password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['_id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'] ?? 'user';
                
                header("Location: ../index.php");
            } else {
                header("Location: ../login.php?error=Invalid credentials");
            }
        }
        exit();
    }
}

// Handle Logout via GET action
if ($action === 'logout') {
    session_destroy();
    header("Location: ../index.php");
    exit();
}

// Fallback for old logout link
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ../index.php");
    exit();
}
?>
