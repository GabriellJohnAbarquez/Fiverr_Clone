<?php
require_once __DIR__ . '/../classloader.php';

// Use the correct PDO object from the Database class
$categoryObj = new Category($databaseObj->getPDO()); 
$categories = $categoryObj->getCategories();
?>

<nav class="navbar navbar-expand-lg navbar-dark p-4" style="background-color: #023E8A;">
  <a class="navbar-brand" href="index.php">Fiver Clone</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <?php
        foreach ($categories as $cat) {
            // Correct subcategory call
            $subcategories = $categoryObj->getSubcategories($cat['category_id']);
            if (!empty($subcategories)) {
                echo '<li class="nav-item dropdown">';
                echo '<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown'.$cat['category_id'].'" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                echo htmlspecialchars($cat['name']);
                echo '</a>';
                echo '<div class="dropdown-menu" aria-labelledby="navbarDropdown'.$cat['category_id'].'">';
                foreach ($subcategories as $sub) {
                    echo '<a class="dropdown-item" href="proposals.php?subcategory_id='.$sub['subcategory_id'].'">'.htmlspecialchars($sub['name']).'</a>';
                }
                echo '</div></li>';
            } else {
                echo '<li class="nav-item"><a class="nav-link" href="proposals.php?category_id='.$cat['category_id'].'">'.htmlspecialchars($cat['name']).'</a></li>';
            }
        }
        ?>
        <!-- Logout link -->
        <li class="nav-item">
            <a class="nav-link" href="core/handleForms.php?logoutUserBtn=1">Logout</a>
        </li>
      </ul>
  </div>
</nav>
