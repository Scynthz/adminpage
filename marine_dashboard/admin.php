<?php
include 'config.php';

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] !== 'admin') {
    header("Location: login.php");
    exit();
}

$sql = "SELECT id, name, email, role FROM users ORDER BY id ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="container">
    <div class="sidebar">
        <h3>Admin</h3>
        <a href="admin.php">Dashboard</a>
        <a href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </div>
    <div class="main-content">
        <h2>User Management</h2>
        <table>
            <tr>
                <th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row["id"] ?></td>
                    <td><?= htmlspecialchars($row["name"]) ?></td>
                    <td><?= htmlspecialchars($row["email"]) ?></td>
                    <td><?= $row["role"] ?></td>
                    <td>
                        <a href="edit_user.php?id=<?= $row["id"] ?>">Edit</a> |
                        <a href="delete_user.php?id=<?= $row["id"] ?>" onclick="return confirm('Delete user?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>
</body>
</html>
