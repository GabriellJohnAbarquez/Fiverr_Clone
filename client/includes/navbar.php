<?php
require_once __DIR__ . '/../classes/Category.php';
$categoryObj = new Category($pdo);
$categories = $categoryObj->getCategories();
?>

<nav class="navbar navbar-expand-lg navbar-dark p-4" style="background-color: #023E8A;">
  <a class="navbar-brand" href="index.php">Client Panel</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" 
          aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">

      <!-- Browse Categories Dropdown -->
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="browseCategories" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Browse Categories
        </a>
        <div class="dropdown-menu" aria-labelledby="browseCategories">
          <?php foreach ($categories as $cat): ?>
            <a class="dropdown-item" href="browse_category.php?category_id=<?= $cat['category_id'] ?>">
              <?= htmlspecialchars($cat['name']) ?>
            </a>
          <?php endforeach; ?>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="project_offers_submitted.php">Project Offers Submitted</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="profile.php">Profile</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="core/handleForms.php?logoutUserBtn=1">Logout</a>
      </li>
    </ul>
  </div>
</nav>
