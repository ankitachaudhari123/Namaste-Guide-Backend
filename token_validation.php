<?php
require 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require './db/db.php';

$key = 'your_secret_key_here';

$headers = apache_request_headers();
$refreshToken = str_replace("Bearer ", "", $headers['Authorization']);

try {
    $decoded = JWT::decode($refreshToken, new Key($key, 'HS256'));

    $userId = $decoded->user_id;

    // Optional: Fetch stored refresh_token from DB and compare for security
    $newRefreshPayload = [
        "user_id" => $userId,
        "exp" => time() + (10 * 60) // new 10 min
    ];

    $newRefreshToken = JWT::encode($newRefreshPayload, $key, 'HS256');

    // Update DB
    $stmt = $connection->prepare("UPDATE user_details SET refresh_token = ? WHERE user_id = ?");
    $stmt->bind_param("si", $newRefreshToken, $userId);
    $stmt->execute();

    echo json_encode([
        "refresh_token" => $newRefreshToken,
        "refresh_token_expiration" => time() + (10 * 60)
    ]);
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(["message" => "Refresh token expired. Please login again"]);
}
?>
