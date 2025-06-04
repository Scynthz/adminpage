<?php
include 'config.php';

$name = $email = $password = $confirm_password = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);

    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Please fill in all fields.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error = "Email already registered.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $hashed_password);
            if ($stmt->execute()) {
                header("Location: login.php");
                exit();
            } else {
                $error = "Registration failed.";
            }
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header><h1>ACARTIA</h1></header>
<form method="post">
    <h2>Register</h2>
    <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>
    <label>Name:</label>
    <input type="text" name="name" value="<?= htmlspecialchars($name) ?>">
    <label>Email:</label>
    <input type="email" name="email" value="<?= htmlspecialchars($email) ?>">
    <label>Password:</label>
    <input type="password" name="password">
    <label>Confirm Password:</label>
    <input type="password" name="confirm_password">
    <input type="submit" value="Register">
    <p>Already registered? <a href="login.php">Login here</a></p>
</form>
</body>
</html>
