<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'classloader.php';
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <style>body { font-family: Arial; }</style>
</head>
<body>
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow">
        <div class="card-body">
          <h3 class="card-title text-center">Admin Login</h3>
          <?php
          if (isset($_SESSION['message'])) {
            echo "<div class='alert alert-danger'>{$_SESSION['message']}</div>";
            unset($_SESSION['message']);
          }
          ?>
          <form action="core/handleForms.php" method="POST">
            <div class="form-group">
              <input type="email" class="form-control" name="email" placeholder="Email" required>
            </div>
            <div class="form-group">
              <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
            <div class="form-group">
              <input type="submit" class="btn btn-primary btn-block" name="loginAdminBtn" value="Login">
            </div>
          </form>
          <div class="text-center">
            <a href="register.php">Don't have an account? Register here</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
