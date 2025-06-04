<?php
include 'config.php';

$email = $password = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (empty($email) || empty($password)) {
        $error = "Please enter both email and password.";
    } else {
        $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $name, $hashed_password, $role);
            $stmt->fetch();
            if (password_verify($password, $hashed_password)) {
                $_SESSION["user_id"] = $id;
                $_SESSION["user_name"] = $name;
                $_SESSION["user_role"] = $role;

                header("Location: " . ($role === 'admin' ? "admin.php" : "profile.php"));
                exit();
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "No account found with that email.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header><h1>ACARTIA</h1></header>
<form method="post">
    <h2>Login</h2>
    <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>
    <label>Email:</label>
    <input type="email" name="email" value="<?= htmlspecialchars($email) ?>">
    <label>Password:</label>
    <input type="password" name="password">
    <input type="submit" value="Login">
    <p>Don't have an account? <a href="register.php">Register here</a></p>
</form>
</body>
</html>
