<?php
require_once __DIR__ . '/../classes/Category.php';
require_once __DIR__ . '/../core/dbconfig.php';

$categoryObj = new Category($pdo);

$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
$subcategories = $categoryObj->getSubcategoriesByCategory($category_id);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Browse Category</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="container mt-4">

  <h2>Subcategories</h2>
  <?php if (empty($subcategories)): ?>
    <p>No subcategories found.</p>
  <?php else: ?>
    <ul class="list-group">
      <?php foreach ($subcategories as $sub): ?>
        <li class="list-group-item">
          <?= htmlspecialchars($sub['name']) ?>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>

</body>
</html>
