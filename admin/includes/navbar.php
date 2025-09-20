<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Optional: load categories if you need them in the navbar
require_once __DIR__ . '/../classes/Category.php';
$categoryObj = new Category(); // No $pdo needed
$categories = $categoryObj->getCategories();
?>

<nav class="navbar navbar-expand-lg navbar-dark p-3" style="background-color: #023E8A;">
    <a class="navbar-brand" href="index.php">Admin Panel</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item"><a class="nav-link" href="index.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="../client/index.php">Act as Client</a></li>
            <li class="nav-item"><a class="nav-link" href="../freelancer/index.php">Act as Freelancer</a></li>
            <li class="nav-item"><a class="nav-link" href="core/handleForms.php?logoutAdminBtn=1">Logout</a></li>
        </ul>
    </div>
</nav>
