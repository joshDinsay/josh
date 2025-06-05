<?php
session_start();
include 'db.php';

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $img_name = $_FILES['profile']['name'];
    $tmp = $_FILES['profile']['tmp_name'];
    $target = "uploads/" . basename($img_name);
    move_uploaded_file($tmp, $target);

    $sql = "INSERT INTO users (name, email, password, image) VALUES ('$name', '$email', '$pass', '$img_name')";
    $conn->query($sql);
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow w-50 mx-auto">
        <div class="card-body">
            <h3 class="card-title mb-4 text-center">📝 Register</h3>
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label>Name</label>
                    <input name="name" class="form-control" required />
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input name="email" type="email" class="form-control" required />
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input name="password" type="password" class="form-control" required />
                </div>
                <div class="mb-3">
                    <label>Profile Picture</label>
                    <input name="profile" type="file" class="form-control" required />
                </div>
                <button name="register" class="btn btn-primary w-100">Register</button>
                <p class="mt-3 text-center">Already have an account? <a href="login.php">Login</a></p>
            </form>
        </div>
    </div>
</div>
</body>
</html>
