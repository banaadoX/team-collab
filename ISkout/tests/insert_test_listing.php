<?php
// Test helper: inserts a test listing and writes a placeholder image.
require_once __DIR__ . '/../includes/db.php';

// Base64 1x1 PNG (transparent)
$imgBase64 = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR4nGNgYAAAAAMAASsJTYQAAAAASUVORK5CYII=';
$uploadDir = __DIR__ . '/../uploads/listings/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
$filename = 'test_ai_' . time() . '.png';
file_put_contents($uploadDir . $filename, base64_decode($imgBase64));
$image_path = 'uploads/listings/' . $filename;

$name = 'AI Test Listing ' . date('YmdHis');
$location = 'Test Location';
$description = 'Inserted by automated test script.';
$zone = 'outside';
$open_time = '08:00:00';
$close_time = '20:00:00';

$stmt = $conn->prepare('INSERT INTO listings (name, location, description, zone, open_time, close_time, image_path) VALUES (?, ?, ?, ?, ?, ?, ?)');
if (!$stmt) {
    echo json_encode(['status' => 'error', 'error' => $conn->error]);
    exit;
}
$stmt->bind_param('sssssss', $name, $location, $description, $zone, $open_time, $close_time, $image_path);
$stmt->execute();
$insertId = $stmt->insert_id;
$stmt->close();

if ($insertId) {
    $cat = 'supplies';
    $ins = $conn->prepare('INSERT INTO listing_categories (listing_id, category) VALUES (?, ?)');
    $ins->bind_param('is', $insertId, $cat);
    $ins->execute();
    $ins->close();

    $tag = 'TestTag';
    $ins2 = $conn->prepare('INSERT INTO listing_tags (listing_id, tag) VALUES (?, ?)');
    $ins2->bind_param('is', $insertId, $tag);
    $ins2->execute();
    $ins2->close();

    echo json_encode(['status' => 'success', 'id' => $insertId, 'image' => $image_path]);
} else {
    echo json_encode(['status' => 'error', 'error' => 'insert failed']);
}
