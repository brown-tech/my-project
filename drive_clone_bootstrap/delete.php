<?php include 'config.php';
$id = $_GET['id'];
$file = $conn->query("SELECT * FROM files WHERE id=$id")->fetch_assoc();
unlink($file['file_path']);
$conn->query("DELETE FROM files WHERE id=$id");
header("Location: dashboard.php");
?>