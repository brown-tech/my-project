<?php
include 'config.php';
if (!isset($_SESSION['user_id'])) header("Location: index.php");

$user_id = $_SESSION['user_id'];
$role = $_SESSION['user_role'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])) {
    $maxSize = 5 * 1024 * 1024;
    if ($_FILES["file"]["size"] > $maxSize) {
        echo "<div class='alert alert-danger'>File size exceeds 5MB.</div>";
        exit();
    }

    $name = $_FILES["file"]["name"];
    $path = "files/" . time() . "_" . $name;
    move_uploaded_file($_FILES["file"]["tmp_name"], $path);
    $token = bin2hex(random_bytes(16));
    $permission = $_POST['permission'];
    $expires_at = $_POST['expires_at'] ? "'{$_POST['expires_at']}'" : 'NULL';

    $conn->query("INSERT INTO files (user_id, file_name, file_path, share_token, permission, expires_at) 
                  VALUES ('$user_id', '$name', '$path', '$token', '$permission', $expires_at)");
}

$files = ($role === 'admin') ?
    $conn->query("SELECT * FROM files") :
    $conn->query("SELECT * FROM files WHERE user_id='$user_id'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Drive Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Welcome to Drive Clone</h2>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>

    <div class="card shadow p-4 mb-4">
        <h4>Upload a File</h4>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="file" class="form-label">Select File</label>
                <input type="file" name="file" class="form-control" required onchange="checkSize(this)">
            </div>
            <div class="mb-3">
                <label class="form-label">Permission</label>
                <select name="permission" class="form-select">
                    <option value="view">View Only</option>
                    <option value="download">Download</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Expire At (optional)</label>
                <input type="datetime-local" name="expires_at" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary w-100">Upload</button>
        </form>
    </div>

    <div class="card shadow p-4">
        <h4>Your Files</h4>
        <table class="table table-striped table-hover mt-3">
            <thead class="table-dark">
                <tr>
                    <th>File Name</th>
                    <th>Share Link</th>
                    <th>Permission</th>
                    <th>Preview</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $files->fetch_assoc()) {
                $ext = pathinfo($row['file_name'], PATHINFO_EXTENSION); ?>
                <tr>
                    <td><?= htmlspecialchars($row['file_name']) ?></td>
                    <td>
    <div class="input-group">
        <input type="text" class="form-control" id="link<?= $row['id'] ?>" 
               value="<?= "http://localhost/drive_clone_bootstrap/download.php?token=" . $row['share_token'] ?>" readonly>
        <button class="btn btn-outline-secondary" type="button" onclick="copyLink('link<?= $row['id'] ?>')">Copy</button>
    </div>
</td>

                    <td><span class="badge bg-info text-dark"><?= $row['permission'] ?></span></td>
                    <td>
                        <?php if (in_array($ext, ['jpg', 'png', 'jpeg', 'pdf'])): ?>
                            <a target="_blank" class="btn btn-sm btn-outline-primary" href="<?= $row['file_path'] ?>">Preview</a>
                        <?php else: ?>N/A<?php endif; ?>
                    </td>
                    <td>
                        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">Delete</a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function checkSize(input) {
    if (input.files[0].size > 5 * 1024 * 1024) {
        alert("Max file size is 5MB!");
        input.value = "";
    }
}
function copyLink(id) {
    const copyText = document.getElementById(id);
    copyText.select();
    copyText.setSelectionRange(0, 99999); // for mobile
    document.execCommand("copy");
    alert("Link copied to clipboard!");
}
</script>
</body>
</html>
