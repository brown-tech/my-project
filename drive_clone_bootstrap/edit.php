<?php include 'config.php';
$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $perm = $_POST['permission'];
    $conn->query("UPDATE files SET permission='$perm' WHERE id=$id");
    header("Location: dashboard.php");
}

$current = $conn->query("SELECT * FROM files WHERE id=$id")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit File Permission</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow p-4">
                <h4 class="mb-3 text-center">Edit File Permission</h4>
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Permission</label>
                        <select name="permission" class="form-select" required>
                            <option value="view" <?= $current['permission'] == 'view' ? 'selected' : '' ?>>View Only</option>
                            <option value="download" <?= $current['permission'] == 'download' ? 'selected' : '' ?>>Download</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Update Permission</button>
                    <a href="dashboard.php" class="btn btn-secondary mt-3 w-100">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
