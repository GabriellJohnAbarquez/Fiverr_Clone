<?php
class Category extends Database {
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Add new category
    public function addCategory($name) {
        $stmt = $this->pdo->prepare("INSERT INTO categories (name) VALUES (?)");
        return $stmt->execute([$name]);
    }

    // Add subcategory
    public function addSubcategory($category_id, $name) {
        $stmt = $this->pdo->prepare("INSERT INTO subcategories (category_id, name) VALUES (?, ?)");
        return $stmt->execute([$category_id, $name]);
    }

    // Get all categories
    public function getCategories() {
        $stmt = $this->pdo->prepare("SELECT * FROM categories ORDER BY name ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get subcategories by category
    public function getSubcategories($category_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM subcategories WHERE category_id = ? ORDER BY name ASC");
        $stmt->execute([$category_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
