<?php
require_once '../config/convex.php';
header('Content-Type: application/json');

$convex = new ConvexDB();
$status = $_GET['status'] ?? 'lost';
$search = $_GET['search'] ?? '';

$items = $convex->getItems($status);

if (isset($items['error'])) {
    echo json_encode(['error' => $items['error']]);
    exit();
}

if (!empty($search)) {
    $items = array_filter($items, function($item) use ($search) {
        return is_array($item) && (
            stripos($item['item_name'] ?? '', $search) !== false || 
            stripos($item['location'] ?? '', $search) !== false ||
            stripos($item['category'] ?? '', $search) !== false
        );
    });
}

// Format date and return
$results = array_values(array_map(function($item) {
    return [
        '_id' => $item['_id'],
        'item_name' => $item['item_name'],
        'status' => $item['status'],
        'location' => $item['location'],
        'category' => $item['category'] ?? 'Miscellaneous',
        'image_path' => $item['image_path'],
        'created_at_formatted' => date('M d', ($item['created_at'] ?? time()*1000) / 1000)
    ];
}, $items));

echo json_encode(['items' => $results]);
?>
