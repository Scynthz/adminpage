<?php
include 'config.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

$stmt = $conn->prepare("SELECT name, email, role, profile_pic FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $email, $role, $profile_pic);
$stmt->fetch();
$stmt->close();

if (empty($profile_pic)) {
    $profile_pic = "default_profile.png";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Your Profile</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="container">
    <div class="sidebar">
        <h3><?= htmlspecialchars($name) ?></h3>
        <a href="profile.php">Profile</a>
        <?php if ($role === 'admin'): ?>
            <a href="admin.php">Admin Panel</a>
        <?php endif; ?>
        <a href="logout.php">Logout</a>
    </div>
    <div class="main-content">
        <h2>Your Profile</h2>
        <img src="assets/img/<?= htmlspecialchars($profile_pic) ?>" class="profile-pic" alt="Profile Picture">
        <p><strong>Name:</strong> <?= htmlspecialchars($name) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
        <p><strong>Role:</strong> <?= htmlspecialchars($role) ?></p>
    </div>
</div>
</body>
</html>
