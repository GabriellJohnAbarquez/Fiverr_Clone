<?php
require_once __DIR__ . '/classloader.php';

if (!$userObj->isLoggedIn()) {
    header("Location: login.php");
    exit();
}

if ($userObj->isAdmin()) {
    header("Location: ../client/index.php");
    exit();
} 

require_once __DIR__ . '/classes/Category.php';
$categoryObj = new Category($databaseObj->getPDO());
$categories = $categoryObj->getCategories();
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <style> body { font-family: "Arial"; } </style>
</head>
<body>
  <?php include 'includes/navbar.php'; ?>
  <div class="container-fluid">
    <div class="display-4 text-center">Hello <span class="text-success"><?= $_SESSION['username'] ?></span>. Add Proposal Here!</div>
    <div class="row">
      <div class="col-md-5">
        <div class="card mt-4 mb-4">
          <div class="card-body">
            <form action="core/handleForms.php" method="POST" enctype="multipart/form-data">
              <div class="card-body">
                <?php  
                if (isset($_SESSION['message']) && isset($_SESSION['status'])) {
                  $color = ($_SESSION['status'] == "200") ? "green" : "red";
                  echo "<h1 style='color: $color'>{$_SESSION['message']}</h1>";
                }
                unset($_SESSION['message']);
                unset($_SESSION['status']);
                ?>
                <h1 class="mb-4 mt-4">Add Proposal Here!</h1>
                <div class="form-group">
                  <label>Description</label>
                  <input type="text" class="form-control" name="description" required>
                </div>
                <div class="form-group">
                  <label>Minimum Price</label>
                  <input type="number" class="form-control" name="min_price" required>
                </div>
                <div class="form-group">
                  <label>Max Price</label>
                  <input type="number" class="form-control" name="max_price" required>
                </div>
                <div class="form-group">
                  <label>Image</label>
                  <input type="file" class="form-control" name="image" required>
                </div>
                <div class="form-group">
                  <label>Category</label>
                  <select name="category_id" id="categorySelect" class="form-control" required>
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $cat): ?>
                      <option value="<?= $cat['category_id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="form-group">
                  <label>Subcategory</label>
                  <select name="subcategory_id" id="subcategorySelect" class="form-control" required>
                    <option value="">Select Subcategory</option>
                  </select>
                </div>
                <input type="submit" class="btn btn-primary float-right mt-2" name="insertNewProposalBtn">
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="col-md-7">
        <?php $getProposals = $proposalObj->getProposals(); ?>
        <?php foreach ($getProposals as $proposal) { ?>
          <div class="card shadow mt-4 mb-4">
            <div class="card-body">
              <h2><a href="other_profile_view.php?user_id=<?= $proposal['user_id'] ?>"><?= $proposal['username'] ?></a></h2>
              <img src="../images/<?= $proposal['image'] ?>" class="img-fluid">
              <p class="mt-4"><i><?= $proposal['proposals_date_added'] ?></i></p>
              <p class="mt-2"><?= $proposal['description'] ?></p>
              <h4><i><?= number_format($proposal['min_price']) ?> - <?= number_format($proposal['max_price']) ?> PHP</i></h4>
            </div>
          </div>
        <?php } ?>
      </div>
    </div>
  </div>
  <!-- Your existing form and proposal display code here -->
  <script>
    $('#categorySelect').on('change', function(){
      let catId = $(this).val();
      $('#subcategorySelect').html('<option>Loading...</option>');
      $.get('browse_category.php', { category_id: catId }, function(data){
        let subcats = $(data).find('ul.list-group li').map(function(){ 
          return '<option value="'+$(this).data('id')+'">'+$(this).text()+'</option>'; 
        }).get().join('');
        $('#subcategorySelect').html('<option value="">Select Subcategory</option>'+subcats);
      });
    });
  </script>
  <!-- ADD THESE SCRIPTS FOR BOOTSTRAP DROPDOWN & COLLAPSE -->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
