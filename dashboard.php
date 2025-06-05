<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user'])) header("Location: login.php");

$users = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="d-flex justify-content-between mb-3">
        <h2>👥 All Registered Users</h2>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
    <div class="row">
        <?php while($row = $users->fetch_assoc()): ?>
        <div class="col-md-3">
            <div class="card mb-4 shadow">
                <img src="uploads/<?= $row['image'] ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title"><?= $row['name'] ?></h5>
                    <p class="card-text text-muted"><?= $row['email'] ?></p>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>
</body>
</html>
