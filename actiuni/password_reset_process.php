<?php
include('../config.php');

header("Content-Type: application/json");
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Metodă invalidă.']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$email = $data['email'] ?? null;

if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'success' => false,
        'message' => 'Email invalid.'
    ]);
    exit;
}

try {
    // Get userID and username
    $stmt = $conexiune->prepare("SELECT userID, username FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Delete previous tokens
        $delete = $conexiune->prepare("DELETE FROM resetare_parola WHERE userID = ?");
        $delete->execute([$user['userID']]);

        // Create new token
        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Insert into password_resets
        $insert = $conexiune->prepare("INSERT INTO resetare_parola (userID, token, dataExpirarii) VALUES (?, ?, ?)");
        $insert->execute([$user['userID'], $token, $expiry]);

        // Create link
        $resetLink = "http://localhost/LearnSphere/frontend/reset_password.php?token=$token";

        // Send email
        $subject = "Resetare parolă";
        $message = "Salut " . htmlspecialchars($user['username']) . ",\n\n";
        $message .= "Apasă pe link-ul de mai jos pentru a reseta parola:\n$resetLink\n\n";
        $message .= "Acest link este valabil timp de 1 oră.\n\n";
        $headers = "From: no-reply@yourdomain.com\r\n";

        mail($email, $subject, $message, $headers);
    }

    echo json_encode([
        'success' => true,
        'message' => 'Dacă adresa există, am trimis un email de resetare.'
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Eroare server: ' . $e->getMessage()
    ]);
}
