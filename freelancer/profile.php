<?php require_once 'classloader.php'; ?>
<?php 
if (!$userObj->isLoggedIn()) {
  header("Location: login.php");
}

if ($userObj->isAdmin()) {
  header("Location: ../client/index.php");
}  
?>
<!doctype html>
  <html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <style>
      body {
        font-family: "Arial";
      }
    </style>
  </head>
  <body>
    <?php include 'includes/navbar.php'; ?>
    <?php $userInfo = $userObj->getUsers($_SESSION['user_id']); ?>
    <div class="container-fluid">
      <div class="display-4 text-center">Hello there and welcome! </div>
      <div class="text-center">
        <?php  
          if (isset($_SESSION['message']) && isset($_SESSION['status'])) {

            if ($_SESSION['status'] == "200") {
              echo "<h1 style='color: green;'>{$_SESSION['message']}</h1>";
            }

            else {
              echo "<h1 style='color: red;'>{$_SESSION['message']}</h1>"; 
            }

          }
          unset($_SESSION['message']);
          unset($_SESSION['status']);
        ?>
      </div>
      <div class="row justify-content-center">
        <div class="col-md-12">
          <div class="card shadow mt-4 mb-4">
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png" class="img-fluid mt-4 mb-4" alt="">
                  <h3>Username: <?php echo $userInfo['username']; ?></h3>
                  <h3>Email: <?php echo $userInfo['email']; ?></h3>
                  <h3>Phone Number: <?php echo $userInfo['contact_number']; ?></h3>
                </div>
                <div class="col-md-6">
                  <form action="core/handleForms.php" method="POST">
                    <div class="card-body">
                      <div class="form-group">
                        <label for="exampleInputEmail1">Username</label>
                        <input type="text" class="form-control" name="username" value="<?php echo $userInfo['username']; ?>"disabled>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail1">Email</label>
                        <input type="email" class="form-control" name="email" value="<?php echo $userInfo['email']; ?>" disabled>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail1">Contact Number</label>
                        <input type="text" class="form-control" name="contact_number" value="<?php echo $userInfo['contact_number']; ?>" required>
                      </div>
                      <div class="form-group">
                        <label for="#">Bio</label>
                        <textarea name="bio_description" class="form-control"><?php echo $userInfo['bio_description']; ?></textarea>
                      </div>
                      <div class="form-group">
                        <label for="#">Display Picture</label>
                        <input type="file" class="form-control" name="display_picture">
                        <input type="submit" class="mt-4 btn btn-primary float-right" name="updateUserBtn" required>
                      </div>  
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>