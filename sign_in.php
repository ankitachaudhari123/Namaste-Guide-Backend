<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__); // Load .env
$dotenv->load();

require './db/db.php'; // DB connection

$key = $_ENV['JWT_SECRET']; // Secure secret key from .env

// Get POST data
$user_email_id = $_POST['email'] ?? null;
$password = $_POST['password'] ?? null;

if (is_null($user_email_id) || is_null($password)) {
    http_response_code(400);
    echo json_encode("Email or password not provided");
    exit;
}

// Check database connection
if (!$connection) {
    http_response_code(500);
    echo json_encode("Database connection failed: " . mysqli_connect_error());
    exit;
}

try {
    $sql = "SELECT * FROM user_details WHERE user_email_id = ?";
    $stmt = $connection->prepare($sql);

    if (!$stmt) {
        throw new Exception("Database query preparation failed: " . $connection->error);
    }

    $stmt->bind_param("ss", $user_email_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $userId = $user['user_id'];
            $username = $user['username'];

            // ✅ Access Token: 30 days | Refresh Token: 10 mins
            $accessTokenExpiration = time() + (30 * 24 * 60 * 60); // 30 days
            $refreshTokenExpiration = time() + (10 * 60); // 10 minutes

            $accessPayload = [
                "user_id" => $userId,
                "exp" => $accessTokenExpiration
            ];

            $refreshPayload = [
                "user_id" => $userId,
                "exp" => $refreshTokenExpiration
            ];

            $accessToken = JWT::encode($accessPayload, $key, 'HS256');
            $refreshToken = JWT::encode($refreshPayload, $key, 'HS256');

            // Store both tokens in DB
            $stmt = $connection->prepare("UPDATE user_details SET token = ?, refresh_token = ?, token_expiration = ? WHERE user_id = ?");
            if (!$stmt) {
                throw new Exception("Database update query preparation failed: " . $connection->error);
            }

            $stmt->bind_param("ssii", $accessToken, $refreshToken, $accessTokenExpiration, $userId);
            if (!$stmt->execute()) {
                throw new Exception("Database update failed: " . $stmt->error);
            }

            echo json_encode([
                "access_token" => $accessToken,
                "refresh_token" => $refreshToken,
                "access_token_expiration" => $accessTokenExpiration,
                "refresh_token_expiration" => $refreshTokenExpiration,
                "username" => $username
            ]);
        } else {
            http_response_code(401);
            echo json_encode("wrong password");
        }
    } else {
        http_response_code(401);
        echo json_encode("user not found");
    }

    $stmt->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode("Server error: " . $e->getMessage());
}

$connection->close();
?>
