<?php
session_start();
require 'config.php';
$message = '';

// Process login form submission
if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    try {
        // Create PDO instance using configuration from config.php
        $pdo = new PDO($dsn, $db_user, $db_pass, $pdo_options);

        // Prepare and execute statement
        $stmt = $pdo->prepare('SELECT id, username, password FROM tb_login WHERE username = ?');
        $stmt->execute([$username]);
        $userData = $stmt->fetch();

        // Verify password
        if ($userData && password_verify($password, $userData['password'])) {
            // Successful login
            $_SESSION['user_id'] = $userData['id'];
            $_SESSION['username'] = $userData['username'];
            header('Location: home.php');
            exit;
        } else {
            $message = 'Invalid username or password.';
        }
    } catch (PDOException $e) {
        $message = 'Database error: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Urbanova - Login</title>
  <link rel="stylesheet" href="styles.css">
  <meta name="description" content="Login to access your Urbanova account and continue shopping.">
</head>
<body>
  <section class="login-container">
    <h1>Login to Urbanova</h1>
    <?php if ($message): ?>
      <div class="error"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <form action="index.php" method="POST" class="login-form">
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
      </div>
      <button type="submit" name="login" class="btn">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a>.</p>
  </section>
</body>
</html>
