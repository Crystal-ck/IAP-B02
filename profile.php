<?php
session_start();
require_once __DIR__ . "/Database.php";

use App\Database;

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$pdo = Database::getConnection();
$user_id = $_SESSION['user_id'];

// Fetch user details
$stmt = $pdo->prepare("SELECT id, username, email FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Handle update form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"] ?? $user["username"];
    $email = $_POST["email"] ?? $user["email"];
    $password = $_POST["password"] ?? null;

    if ($password) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $update = $pdo->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?");
        $update->execute([$username, $email, $hashed, $user_id]);
    } else {
        $update = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
        $update->execute([$username, $email, $user_id]);
    }

    $_SESSION["message"] = "Profile updated successfully!";
    header("Location: profile.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <link rel="stylesheet" href="style2.css">
</head>
<body>
    <div class="wrapper">
        <h1>Your Profile</h1>

        <?php if (isset($_SESSION["message"])): ?>
            <p style="color: lime;"><?php echo $_SESSION["message"]; unset($_SESSION["message"]); ?></p>
        <?php endif; ?>

        <form method="POST">
            <div class="input-box">
                <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="input-box">
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="input-box">
                <input type="password" name="password" placeholder="New Password (leave blank to keep)">
            </div>
            <button type="submit" class="btn">Update Profile</button>
        </form>

        <p><a href="dashboard.html">← Back to Dashboard</a></p>
        <p><a href="logout.php" style="color:red;">Logout</a></p>
    </div>
</body>
</html>
