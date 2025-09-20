<?php
require_once 'classloader.php'; // make sure this is client/classloader.php

// Redirect if already logged in
if ($userObj->isLoggedIn() && !$userObj->isAdmin()) {
    header("Location: index.php"); // client panel
    exit();
} elseif ($userObj->isAdmin()) {
    header("Location: ../freelancer/index.php"); // redirect admin to freelancer panel
    exit();
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <style>
    body {
      font-family: "Arial";
      background-image: url("https://images.unsplash.com/photo-1501504905252-473c47e087f8?q=80&w=1074&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D");
    }
  </style>
  <title>Client Login</title>
</head>
<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8 p-5">
        <div class="card shadow">
          <div class="card-header">
            <h2>Welcome to the Client Panel! Login Now!</h2>
          </div>
          <form action="core/handleForms.php" method="POST">
            <div class="card-body">
              <?php  
              if (isset($_SESSION['message']) && isset($_SESSION['status'])) {
                  $color = ($_SESSION['status'] == "200") ? "green" : "red";
                  echo "<h5 style='color: $color'>{$_SESSION['message']}</h5>";
                  unset($_SESSION['message']);
                  unset($_SESSION['status']);
              }
              ?>
              <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" name="email" required>
              </div>
              <div class="form-group">
                <label>Password</label>
                <input type="password" class="form-control" name="password" required>
              </div>
              <input type="submit" class="btn btn-primary float-right mt-2" name="loginUserBtn" value="Login">
              <div class="form-group mt-3">
                <p>Don't have an account? <a href="register.php">Register here!</a></p>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
