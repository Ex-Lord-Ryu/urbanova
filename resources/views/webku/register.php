<?php
session_start();
require 'config.php';
$message = '';

// Process registration form submission
if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Basic validation
    if (empty($username) || empty($password)) {
        $message = 'Username and password are required.';
    } else {
        try {
            // Create PDO instance
            $pdo = new PDO($dsn, $db_user, $db_pass, $pdo_options);

            // Check if username already exists
            $stmt = $pdo->prepare('SELECT COUNT(*) FROM tb_login WHERE username = ?');
            $stmt->execute([$username]);
            if ($stmt->fetchColumn()) {
                $message = 'Username already taken.';
            } else {
                // Hash password and insert new user
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $insert = $pdo->prepare('INSERT INTO tb_login (username, password) VALUES (?, ?)');
                $insert->execute([$username, $hashedPassword]);

                // Redirect to login with success message
                header('Location: index.php?registered=1');
                exit;
            }
        } catch (PDOException $e) {
            $message = 'Database error: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Urbanova - Register</title>
  <link rel="stylesheet" href="styles.css">
  <meta name="description" content="Create a new Urbanova account with username and password.">
</head>
<body>
  <section class="register-container">
    <h1>Create an Account</h1>
    <?php if ($message): ?>
      <div class="error"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <form action="register.php" method="POST" class="register-form">
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
      </div>
      <button type="submit" name="register" class="btn">Register</button>
    </form>
    <p>Already have an account? <a href="index.php">Login here</a>.</p>
  </section>
</body>
</html>
