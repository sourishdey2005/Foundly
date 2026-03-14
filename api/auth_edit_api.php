<?php
require_once '../config/convex.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$convex = new ConvexDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemId = $_POST['item_id'];
    $itemName = trim($_POST['item_name']);
    $description = trim($_POST['description']);
    $location = trim($_POST['location']);
    $category = $_POST['category'] ?? 'Miscellaneous';
    $dateLost = $_POST['date_lost'];
    
    // Check if new image is uploaded
    $fileName = null;
    if (!empty($_FILES["item_image"]["name"])) {
        $targetDir = "../uploads/";
        $fileName = time() . "_" . basename($_FILES["item_image"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

        $allowTypes = array('jpg', 'png', 'jpeg');
        if (in_array($fileType, $allowTypes)) {
            if ($_FILES["item_image"]["size"] < 5000000) {
                if (!move_uploaded_file($_FILES["item_image"]["tmp_name"], $targetFilePath)) {
                    header("Location: edit_item.php?id=$itemId&error=File upload failed");
                    exit();
                }
            } else {
                header("Location: edit_item.php?id=$itemId&error=File too large");
                exit();
            }
        } else {
            header("Location: edit_item.php?id=$itemId&error=Invalid file type");
            exit();
        }
    }

    // Update in Convex
    $result = $convex->updateItem($itemId, $itemName, $description, $location, $dateLost, $category, $fileName);
    
    if (isset($result['error'])) {
        header("Location: edit_item.php?id=$itemId&error=" . urlencode($result['error']));
    } else {
        header("Location: my_reports.php?success=Item updated successfully");
    }
    exit();
}
?>
