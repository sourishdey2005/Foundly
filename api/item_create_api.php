<?php
require_once '../config/convex.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$convex = new ConvexDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemName = trim($_POST['item_name']);
    $description = trim($_POST['description']);
    $location = trim($_POST['location']);
    $category = $_POST['category'] ?? 'Miscellaneous';
    $dateLost = $_POST['date_lost'];
    $userId = $_SESSION['user_id'];

    // Image Upload Handling
    $targetDir = "../uploads/";
    $fileName = time() . "_" . basename($_FILES["item_image"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    // Validate image
    $allowTypes = array('jpg', 'png', 'jpeg');
    if (in_array($fileType, $allowTypes)) {
        if ($_FILES["item_image"]["size"] < 5000000) { // 5MB limit
            if (move_uploaded_file($_FILES["item_image"]["tmp_name"], $targetFilePath)) {
                
                // Save to Convex
                $result = $convex->createItem($userId, $itemName, $description, $location, $dateLost, $category, $fileName);
                
                if (isset($result['error'])) {
                    header("Location: report_item.php?error=" . urlencode($result['error']));
                } else {
                    header("Location: index.php?success=Item reported successfully");
                }
            } else {
                header("Location: report_item.php?error=Sorry, there was an error uploading your file.");
            }
        } else {
            header("Location: report_item.php?error=Sorry, your file is too large.");
        }
    } else {
        header("Location: report_item.php?error=Sorry, only JPG, JPEG, & PNG files are allowed.");
    }
    exit();
}
?>
