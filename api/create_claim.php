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
    $userId = $_SESSION['user_id'];
    $userName = $_SESSION['user_name'];
    $phone = trim($_POST['phone']);
    $proofText = trim($_POST['proof_text']);
    
    $proofImage = null;

    // Handle optional proof image upload
    if (isset($_FILES['proof_image']) && $_FILES['proof_image']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "../uploads/";
        $fileName = "claim_" . time() . "_" . basename($_FILES["proof_image"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

        $allowTypes = array('jpg', 'png', 'jpeg');
        if (in_array($fileType, $allowTypes)) {
            if ($_FILES["proof_image"]["size"] < 5000000) {
                if (move_uploaded_file($_FILES["proof_image"]["tmp_name"], $targetFilePath)) {
                    $proofImage = $fileName;
                }
            }
        }
    }

    $result = $convex->createClaim($itemId, $userId, $userName, $phone, $proofText, $proofImage);

    if (isset($result['error'])) {
        header("Location: index.php?error=" . urlencode($result['error']));
    } else {
        header("Location: index.php?success=Claim submitted successfully. Please wait for admin approval.");
    }
    exit();
}
?>
