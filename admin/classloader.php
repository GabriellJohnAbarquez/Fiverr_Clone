<?php
// admin/classloader.php

require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/User.php';
require_once __DIR__ . '/classes/Category.php';
require_once __DIR__ . '/classes/Subcategory.php';
require_once __DIR__ . '/classes/Proposal.php';
require_once __DIR__ . '/classes/Offer.php';

// Initialize objects (no $pdo needed)
$userObj        = new User();
$categoryObj    = new Category();
$subcategoryObj = new Subcategory();
$proposalObj    = new Proposal();
$offerObj       = new Offer();

session_start();
