<?php
/**
 * Convex Database Integration Configuration
 * This file handles all HTTP requests to the Convex Backend.
 */

class ConvexDB {
    private $deploymentUrl; 
    private $apiKey = ""; // Optional, if using private deployment

    public function __construct() {
        // Use environment variable if set (for Vercel), fallback to local dev url
        $this->deploymentUrl = getenv('CONVEX_URL') ?: "https://amicable-pig-971.convex.cloud";
    }

    /**
     * Call a Convex query or mutation via HTTP API
     * @param string $functionName format: 'filename:functionName'
     * @param array $args
     * @return mixed
     */
    public function call($functionName, $args = []) {
        $url = "{$this->deploymentUrl}/api/run/{$functionName}";
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['args' => $args]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            // 'Authorization: Bearer ' . $this->apiKey // If auth is needed
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 200 && $httpCode < 300) {
            $data = json_decode($response, true);
            return $data['value'] ?? $data;
        }

        return ['error' => "Neural Link Error ($httpCode). Target: $url. Response: $response"];
    }

    // --- User Functions ---

    public function createUser($name, $email, $passwordHash, $role) {
        return $this->call('users/createUser', [
            'name' => $name,
            'email' => $email,
            'password_hash' => $passwordHash,
            'role' => $role
        ]);
    }

    public function loginUser($email) {
        return $this->call('users/loginUser', ['email' => $email]);
    }

    // --- Item Functions ---

    public function createItem($userId, $name, $desc, $loc, $date, $category, $image) {
        return $this->call('items/createItem', [
            'user_id' => $userId,
            'item_name' => $name,
            'description' => $desc,
            'location' => $loc,
            'date_lost' => $date,
            'category' => $category,
            'image_path' => $image
        ]);
    }

    public function updateItem($itemId, $name, $desc, $loc, $date, $category, $image = null) {
        $args = [
            'id' => $itemId,
            'item_name' => $name,
            'description' => $desc,
            'location' => $loc,
            'date_lost' => $date,
            'category' => $category
        ];
        if ($image) {
            $args['image_path'] = $image;
        }
        return $this->call('items/updateItem', $args);
    }

    public function getItems($status = null) {
        return $this->call('items/getItems', ['status' => $status]);
    }

    // --- Claim Functions ---

    public function createClaim($itemId, $claimerId, $name, $phone, $proofText, $proofImage = null) {
        return $this->call('claims/createClaim', [
            'item_id' => $itemId,
            'claimer_id' => $claimerId,
            'claimer_name' => $name,
            'phone' => $phone,
            'proof_text' => $proofText,
            'proof_image' => $proofImage
        ]);
    }

    public function getClaims($status = null) {
        return $this->call('claims/getClaims', ['status' => $status]);
    }

    public function approveClaim($claimId) {
        return $this->call('claims/updateClaimStatus', [
            'id' => $claimId,
            'status' => 'approved'
        ]);
    }

    public function rejectClaim($claimId) {
        return $this->call('claims/updateClaimStatus', [
            'id' => $claimId,
            'status' => 'rejected'
        ]);
    }
}
?>
