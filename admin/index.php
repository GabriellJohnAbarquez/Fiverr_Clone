<?php
require_once 'classloader.php';

// Access control
if (!$userObj->isLoggedIn() || $_SESSION['role'] !== 'fiverr_administrator') {
    header("Location: login.php");
    exit();
}

// Fetch categories and proposals
$categories = $categoryObj->getCategories();
$proposals  = $proposalObj->getProposals();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<style>
body { font-family: Arial; }
</style>
</head>
<body>
<?php include 'includes/navbar.php'; ?>

<div class="container mt-5">

  <h2 class="text-center mb-4">Admin Dashboard</h2>

  <!-- Display Messages -->
  <?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-<?= $_SESSION['status'] == 200 ? 'success' : 'danger' ?> text-center">
        <?= $_SESSION['message'] ?>
    </div>
    <?php unset($_SESSION['message'], $_SESSION['status']); ?>
  <?php endif; ?>

  <div class="row mb-5">
    <!-- Add Category -->
    <div class="col-md-6">
      <div class="card shadow p-3">
        <h4>Add Category</h4>
        <form action="core/handleForms.php" method="POST">
          <input type="text" class="form-control mb-2" name="category_name" placeholder="Category Name" required>
          <input type="submit" class="btn btn-primary" name="addCategoryBtn" value="Add Category">
        </form>
      </div>
    </div>

    <!-- Add Subcategory -->
    <div class="col-md-6">
      <div class="card shadow p-3">
        <h4>Add Subcategory</h4>
        <form action="core/handleForms.php" method="POST">
          <select class="form-control mb-2" name="category_id" required>
            <option value="">Select Category</option>
            <?php foreach($categories as $cat): ?>
              <option value="<?= $cat['category_id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
            <?php endforeach; ?>
          </select>
          <input type="text" class="form-control mb-2" name="subcategory_name" placeholder="Subcategory Name" required>
          <input type="submit" class="btn btn-primary" name="addSubcategoryBtn" value="Add Subcategory">
        </form>
      </div>
    </div>
  </div>

  <!-- Categories List -->
  <h3>Categories</h3>
  <?php if (!empty($categories)): ?>
      <ul>
          <?php foreach($categories as $cat): ?>
              <li>
                  <?= htmlspecialchars($cat['name']) ?>
                  <a href="core/handleForms.php?deleteCategory=<?= $cat['category_id'] ?>" 
                     onclick="return confirm('Delete this category?')">Delete</a>
              </li>
          <?php endforeach; ?>
      </ul>
  <?php else: ?>
      <p>No categories found.</p>
  <?php endif; ?>

  <!-- Proposals -->
  <h3 class="mt-5">Proposals Submitted</h3>
  <div class="row">
    <?php foreach($proposals as $p): ?>
      <div class="col-md-6 mb-3">
        <div class="card shadow p-3">
          <h5><?= htmlspecialchars($p['username']) ?></h5>
          <p><?= htmlspecialchars($p['description']) ?></p>
          <p><?= number_format($p['min_price']) ?> - <?= number_format($p['max_price']) ?> PHP</p>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="text-center mt-4">
    <a href="../client/index.php" class="btn btn-success">Act as Client</a>
  </div>

</div>
</body>
</html>
