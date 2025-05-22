<?php
include('../config.php');

header('Content-Type: application/json');

$userID = $_SESSION['userID'] ?? null;
$materialID = $_POST['materialID'] ?? null;
$reaction = $_POST['reaction'] ?? null;

if (!$userID || !$materialID || !in_array($reaction, ['like', 'dislike'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit;
}

// Clean up both tables
if ($reaction === 'like') {
    $conexiune->prepare("DELETE FROM dislikes WHERE userID = ? AND materialID = ?")->execute([$userID, $materialID]);
    $stmt = $conexiune->prepare("SELECT 1 FROM likes WHERE userID = ? AND materialID = ?");
    $stmt->execute([$userID, $materialID]);
    if ($stmt->rowCount() === 0) {
        $conexiune->prepare("INSERT INTO likes (userID, materialID, dataPostarii) VALUES (?, ?, NOW())")
            ->execute([$userID, $materialID]);
    } else {
        $conexiune->prepare("DELETE FROM likes WHERE userID = ? AND materialID = ?")->execute([$userID, $materialID]);
    }
} else {
    $conexiune->prepare("DELETE FROM likes WHERE userID = ? AND materialID = ?")->execute([$userID, $materialID]);
    $stmt = $conexiune->prepare("SELECT 1 FROM dislikes WHERE userID = ? AND materialID = ?");
    $stmt->execute([$userID, $materialID]);
    if ($stmt->rowCount() === 0) {
        $conexiune->prepare("INSERT INTO dislikes (userID, materialID, dataPostarii) VALUES (?, ?, NOW())")
            ->execute([$userID, $materialID]);
    } else {
        $conexiune->prepare("DELETE FROM dislikes WHERE userID = ? AND materialID = ?")->execute([$userID, $materialID]);
    }
}

// Get updated counts
$stmt = $conexiune->prepare("SELECT COUNT(*) FROM likes WHERE materialID = ?");
$stmt->execute([$materialID]);
$likeCount = $stmt->fetchColumn();

$stmt = $conexiune->prepare("SELECT COUNT(*) FROM dislikes WHERE materialID = ?");
$stmt->execute([$materialID]);
$dislikeCount = $stmt->fetchColumn();

// Output JSON
echo json_encode([
    'success' => true,
    'likes' => $likeCount,
    'dislikes' => $dislikeCount
]);
