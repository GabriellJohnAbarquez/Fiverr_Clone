<?php  
require_once 'classes/Database.php';
require_once 'classes/Offer.php';
require_once 'classes/Proposal.php';
require_once 'classes/User.php';
require_once 'classes/Category.php';

$databaseObj = new Database();
$pdo = $databaseObj->getPDO(); // get PDO instance

$offerObj = new Offer($pdo);
$proposalObj = new Proposal($pdo);
$userObj = new User($pdo);
$categoryObj = new Category($pdo);

$userObj->startSession();

?>
