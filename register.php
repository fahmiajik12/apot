<?php
// register.php

session_start();

if (isset($_SESSION['username'])) {
  header("Location: index.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  require_once('config.php');

  $username = $_POST['username'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $role = $_POST['role'];

  $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
  $stmt->bindParam(':username', $username);
  $stmt->bindParam(':password', $password);
  $stmt->bindParam(':role', $role);

  if ($stmt->execute()) {
    $_SESSION['username'] = $username;
    $_SESSION['role'] = $role;

    header("Location: index.php");
    exit();
  } else {
    $register_error = "Failed to register";
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Apotek Register</title>
  <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
  <div class="container">
    <h1>Apotek Register</h1>

    <?php if (isset($register_error)) : ?>
      <p style="color: red;"><?php echo $register_error; ?></p>
    <?php endif; ?>

    <form method="POST">
      <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
      </div>

      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
      </div>

      <div class="form-group">
        <label for="role">Role:</label>
        <select id="role" name="role" required>
          <option value="user">User</option>
          <option value="admin">Admin</option>
        </select>
      </div>

      <div class="form-group">
        <input type="submit" value="Register">
      </div>
    </form>
  </div>
</body>

</html>
