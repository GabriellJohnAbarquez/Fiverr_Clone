<?php
require_once __DIR__ . '/../classes/Category.php';
require_once __DIR__ . '/../classes/Proposal.php';
require_once __DIR__ . '/../core/dbconfig.php';

$categoryObj = new Category($pdo);
$proposalObj = new Proposal($pdo);

$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
$subcategories = $categoryObj->getSubcategoriesByCategory($category_id);
$proposals = $proposalObj->getProposalsByCategory($category_id);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Browse Category</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="container mt-4">

  <h2>Proposals in Category</h2>
  <?php if (empty($proposals)): ?>
    <p>No proposals found for this category.</p>
  <?php else: ?>
    <div class="list-group">
      <?php foreach ($proposals as $proposal): ?>
        <a href="view_proposal.php?id=<?= $proposal['proposal_id'] ?>" class="list-group-item list-group-item-action">
          <strong><?= htmlspecialchars($proposal['description']) ?></strong><br>
          Price: <?= $proposal['min_price'] ?> - <?= $proposal['max_price'] ?>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

</body>
</html>
