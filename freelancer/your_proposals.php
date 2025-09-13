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
    <div class="container-fluid">
      <div class="display-4 text-center">Double click to edit!</div>
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
        <div class="col-md-6">
          <?php $getProposalsByUserID = $proposalObj->getProposalsByUserID($_SESSION['user_id']); ?>
          <?php foreach ($getProposalsByUserID as $proposal) { ?>
          <div class="card proposalCard shadow mt-4 mb-4">
            <div class="card-body">
              <h2><a href="#"><?php echo $proposal['username']; ?></a></h2>
              <img src="<?php echo "../images/".$proposal['image']; ?>" class="img-fluid" alt="">
              <p class="mt-4"><i><?php echo $proposal['proposals_date_added']; ?></i></p>
              <p class="mt-2"><?php echo $proposal['description']; ?></p>
              <h4><i><?php echo number_format($proposal['min_price']) . " - " . number_format($proposal['max_price']);?></i></h4>
              <form action="core/handleForms.php" method="POST">
                <div class="form-group">
                  <input type="hidden" name="proposal_id" value="<?php echo $proposal['proposal_id']; ?>">
                  <input type="hidden" name="image" value="<?php echo $proposal['image']; ?>">
                  <input type="submit" name="deleteProposalBtn" class="btn btn-danger float-right" value="Delete">
                </div>
              </form>
                <form action="core/handleForms.php" method="POST" class="updateProposalForm d-none">
                  <div class="row mt-4">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="#">Minimum Price</label>
                        <input type="number" class="form-control" name="min_price" value="<?php echo $proposal['min_price']; ?>">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="#">Maximum Price</label>
                        <input type="number" class="form-control" name="max_price" value="<?php echo $proposal['max_price']; ?>">
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="#">Description</label>
                        <input type="hidden" name="proposal_id" value="<?php echo $proposal['proposal_id']; ?>">
                        <textarea name="description" class="form-control"><?php echo $proposal['description']; ?></textarea>
                        <input type="submit" class="btn btn-primary form-control mt-2" name="updateProposalBtn">
                      </div>
                    </div>
                  </div>
                </form>
            </div>
          </div>
          <?php } ?>
        </div>
      </div>
    </div>
    <script>
       $('.proposalCard').on('dblclick', function (event) {
          var updateProposalForm = $(this).find('.updateProposalForm');
          updateProposalForm.toggleClass('d-none');
        });
    </script>
  </body>
</html>