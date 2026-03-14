<?php
session_start();
require_once '../config/convex.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$claimId = $_GET['id'] ?? null;
if ($claimId) {
    $convex = new ConvexDB();
    $result = $convex->rejectClaim($claimId);
    
    if (isset($result['error'])) {
        header("Location: claims.php?error=" . urlencode($result['error']));
    } else {
        header("Location: claims.php?success=Claim rejected successfully.");
    }
} else {
    header("Location: claims.php");
}
exit();
?>
