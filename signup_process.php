<?php
// signup_process.php - handles new user registration

// Database connection
$conn = new mysqli("localhost", "root", "", "smartauth_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sanitize and get form data
$fullname = $conn->real_escape_string($_POST['fullname']);
$email = $conn->real_escape_string($_POST['email']);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

// Upload profile picture
$target_dir = "uploads/";
$profile_pic = $target_dir . basename($_FILES["profile_pic"]["name"]);
move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $profile_pic);

// Insert user into the database
$sql = "INSERT INTO users (fullname, email, password, profile_pic) VALUES ('$fullname', '$email', '$password', '$profile_pic')";
if ($conn->query($sql) === TRUE) {
    header("Location: login.php?success=1");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>

<!-- login.php - login form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5" style="max-width: 400px;">
    <div class="card p-4">
        <h3 class="text-center mb-4"><i class="fas fa-sign-in-alt"></i> Login</h3>
        <form action="login_process.php" method="POST">
            <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
            <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
            <button class="btn btn-success w-100"><i class="fas fa-sign-in-alt"></i> Login</button>
        </form>
        <div class="text-center mt-3">
            Don't have an account? <a href="index.html">Sign Up</a>
        </div>
    </div>
</div>
</body>
</html>

<?php
// login_process.php - handles login logic

session_start();

$conn = new mysqli("localhost", "root", "", "smartauth_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $conn->real_escape_string($_POST['email']);
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE email='$email'";
$result = $conn->query($sql);

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['fullname'] = $user['fullname'];
        $_SESSION['profile_pic'] = $user['profile_pic'];
        header("Location: dashboard.php");
    } else {
        echo "<script>alert('Invalid password'); window.location='login.php';</script>";
    }
} else {
    echo "<script>alert('No account found'); window.location='login.php';</script>";
}
$conn->close();
?>

<!-- dashboard.php - displays user data after login -->
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="card text-center p-4">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['fullname']); ?>!</h2>
        <img src="<?php echo $_SESSION['profile_pic']; ?>" alt="Profile Picture" class="rounded-circle mt-3" style="width: 150px; height: 150px; object-fit: cover;">
        <div class="mt-4">
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
</div>
</body>
</html>
