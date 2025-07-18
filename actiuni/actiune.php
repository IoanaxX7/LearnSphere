<?php
include('../config.php');

header('Content-Type: application/json');

$userID = $_SESSION['userID'] ?? null;
$materialID = $_POST['materialID'] ?? null;
$comentariuID = $_POST['comentariuID'] ?? null;
$intrebareID = $_POST['intrebareID'] ?? null;
$reaction = $_POST['reaction'] ?? null;

if (!$userID || !in_array($reaction, ['like', 'dislike'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit;
}

if ($materialID !== null) {
    $id = (int)$materialID;
    $idField = 'materialID';
} elseif ($comentariuID !== null) {
    $id = (int)$comentariuID;
    $idField = 'comentariuID';
} elseif ($intrebareID !== null) {
    $id = (int)$intrebareID;
    $idField = 'intrebareID';
} else {
    echo json_encode(['success' => false, 'message' => 'No ID provided']);
    exit;
}

// Determine tables (you can customize this if you have different tables for each entity type)
$likesTable = 'likes';
$dislikesTable = 'dislikes';

// Remove opposite reaction first
if ($reaction === 'like') {
    $conexiune->prepare("DELETE FROM $dislikesTable WHERE userID = ? AND $idField = ?")->execute([$userID, $id]);
    $stmt = $conexiune->prepare("SELECT 1 FROM $likesTable WHERE userID = ? AND $idField = ?");
    $stmt->execute([$userID, $id]);
    if ($stmt->rowCount() === 0) {
        $conexiune->prepare("INSERT INTO $likesTable (userID, $idField, dataPostarii) VALUES (?, ?, NOW())")
            ->execute([$userID, $id]);
    } else {
        $conexiune->prepare("DELETE FROM $likesTable WHERE userID = ? AND $idField = ?")->execute([$userID, $id]);
    }
} else {
    $conexiune->prepare("DELETE FROM $likesTable WHERE userID = ? AND $idField = ?")->execute([$userID, $id]);
    $stmt = $conexiune->prepare("SELECT 1 FROM $dislikesTable WHERE userID = ? AND $idField = ?");
    $stmt->execute([$userID, $id]);
    if ($stmt->rowCount() === 0) {
        $conexiune->prepare("INSERT INTO $dislikesTable (userID, $idField, dataPostarii) VALUES (?, ?, NOW())")
            ->execute([$userID, $id]);
    } else {
        $conexiune->prepare("DELETE FROM $dislikesTable WHERE userID = ? AND $idField = ?")->execute([$userID, $id]);
    }
}

// Get updated counts
$stmt = $conexiune->prepare("SELECT COUNT(*) FROM $likesTable WHERE $idField = ?");
$stmt->execute([$id]);
$likeCount = $stmt->fetchColumn();

$stmt = $conexiune->prepare("SELECT COUNT(*) FROM $dislikesTable WHERE $idField = ?");
$stmt->execute([$id]);
$dislikeCount = $stmt->fetchColumn();

echo json_encode([
    'success' => true,
    'likes' => $likeCount,
    'dislikes' => $dislikeCount
]);
