<?php
include 'config.php';

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: admin.php");
    exit();
}

$id = intval($_GET['id']);
$error = $success = "";

$stmt = $conn->prepare("SELECT name, email, role FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows === 0) {
    $stmt->close();
    header("Location: admin.php");
    exit();
}
$stmt->bind_result($name, $email, $role);
$stmt->fetch();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $role = $_POST["role"];

    if (empty($name) || empty($email) || empty($role)) {
        $error = "All fields required.";
    } else {
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
        $stmt->bind_param("sssi", $name, $email, $role, $id);
        if ($stmt->execute()) {
            $success = "User updated.";
        } else {
            $error = "Update failed.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<form method="post">
    <h2>Edit User</h2>
    <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>
    <?php if ($success) echo "<p style='color:green;'>$success</p>"; ?>
    <label>Name:</label>
    <input type="text" name="name" value="<?= htmlspecialchars($name) ?>">
    <label>Email:</label>
    <input type="email" name="email" value="<?= htmlspecialchars($email) ?>">
    <label>Role:</label>
    <select name="role">
        <option value="user" <?= $role === 'user' ? 'selected' : '' ?>>User</option>
        <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>Admin</option>
    </select>
    <input type="submit" value="Update User">
    <p><a href="admin.php">Back</a></p>
</form>
</body>
</html>
