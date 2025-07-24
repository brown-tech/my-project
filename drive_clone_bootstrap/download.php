<?php include 'config.php';
if (!isset($_GET['token'])) die("Invalid link.");
$token = $_GET['token'];
$result = $conn->query("SELECT * FROM files WHERE share_token='$token'");
$file = $result->fetch_assoc();
if (!$file) die("File not found.");
if ($file['expires_at'] && strtotime($file['expires_at']) < time()) die("This file link has expired.");

$filepath = $file['file_path'];
if ($file['permission'] === 'download') {
    header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
} else {
    header('Content-Disposition: inline');
}
header('Content-Type: application/octet-stream');
readfile($filepath);
?>