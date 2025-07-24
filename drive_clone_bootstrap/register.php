<?php include 'config.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $conn->query("INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')");
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - Drive Clone</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card shadow p-4">
        <h3 class="text-center">Register</h3>
        <form method="post">
          <div class="mb-3">
            <label>Username</label>
            <input name="username" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Email</label>
            <input name="email" type="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Password</label>
            <input name="password" type="password" class="form-control" required>
          </div>
          <button class="btn btn-success w-100">Register</button>
          <p class="text-center mt-3">Have an account? <a href="index.php">Login</a></p>
        </form>
      </div>
    </div>
  </div>
</div>
</body>
</html>