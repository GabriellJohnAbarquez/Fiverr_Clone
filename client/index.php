<?php 
require_once 'classloader.php';

// Allow client or admin acting as client
if (!$userObj->isLoggedIn() || !in_array($_SESSION['role'], ['client','fiverr_administrator'])) {
    header("Location: login.php");
    exit();
}

?>
<!doctype html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" crossorigin="anonymous">
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
    <div class="display-4 text-center">
      Hello there and welcome
      <span class="text-success"><?php echo htmlspecialchars($_SESSION['username']); ?></span> 
      to client panel!
    </div>
    <div class="text-center">
      <?php  
        if (isset($_SESSION['message']) && isset($_SESSION['status'])) {
          if ($_SESSION['status'] == "200") {
            echo "<h1 style='color: green;'>{$_SESSION['message']}</h1>";
          } else {
            echo "<h1 style='color: red;'>{$_SESSION['message']}</h1>"; 
          }
          unset($_SESSION['message']);
          unset($_SESSION['status']);
        }
      ?>
    </div>
    <div class="row justify-content-center">
      <div class="col-md-12">
        <?php $getProposals = $proposalObj->getProposals(); ?>
        <?php foreach ($getProposals as $proposal) { ?>
        <div class="card shadow mt-4 mb-4">
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <h2>
                  <a href="other_profile_view.php?user_id=<?php echo (int)$proposal['user_id']; ?>">
                    <?php echo htmlspecialchars($proposal['username']); ?>
                  </a>
                </h2>
                <img src="<?php echo '../images/' . htmlspecialchars($proposal['image']); ?>" class="img-fluid" alt="Proposal Image">
                <p class="mt-4 mb-4"><?php echo htmlspecialchars($proposal['description']); ?></p>
                <h4><i><?php echo number_format($proposal['min_price']) . " - " . number_format($proposal['max_price']); ?> PHP</i></h4>
              </div>
              <div class="col-md-6">
                <div class="card" style="height: 600px;">
                  <div class="card-header"><h2>All Offers</h2></div>
                  <div class="card-body overflow-auto">
                    <?php $getOffersByProposalID = $offerObj->getOffersByProposalID($proposal['proposal_id']); ?>
                    <?php foreach ($getOffersByProposalID as $offer) { ?>
                    <div class="offer">
                      <h4>
                        <?php echo htmlspecialchars($offer['username']); ?> 
                        <span class="text-primary">( <?php echo htmlspecialchars($offer['contact_number']); ?> )</span>
                      </h4>
                      <small><i><?php echo htmlspecialchars($offer['offer_date_added']); ?></i></small>
                      <p><?php echo htmlspecialchars($offer['description']); ?></p>

                      <?php if ($offer['user_id'] == $_SESSION['user_id']) { ?>
                        <form action="core/handleForms.php" method="POST">
                          <div class="form-group">
                            <input type="hidden" name="offer_id" value="<?php echo (int)$offer['offer_id']; ?>">
                            <input type="submit" class="btn btn-danger" value="Delete" name="deleteOfferBtn">
                          </div>
                        </form>

                        <form action="core/handleForms.php" method="POST" class="updateOfferForm d-none">
                          <div class="form-group">
                            <label>Description</label>
                            <input type="text" class="form-control" name="description" value="<?php echo htmlspecialchars($offer['description']); ?>">
                            <input type="hidden" name="offer_id" value="<?php echo (int)$offer['offer_id']; ?>">
                            <input type="submit" class="btn btn-primary form-control" name="updateOfferBtn">
                          </div>
                        </form>
                      <?php } ?>
                      <hr>
                    </div>
                    <?php } ?>
                  </div>
                  <div class="card-footer">
                    <form action="core/handleForms.php" method="POST">
                      <div class="form-group">
                        <label>Description</label>
                        <input type="text" class="form-control" name="description">
                        <input type="hidden" name="proposal_id" value="<?php echo (int)$proposal['proposal_id']; ?>">
                        <input type="submit" class="btn btn-primary float-right mt-4" name="insertOfferBtn"> 
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
  <script>
     $('.offer').on('dblclick', function () {
        var updateOfferForm = $(this).find('.updateOfferForm');
        updateOfferForm.toggleClass('d-none');
      });
  </script>
</body>
</html>
