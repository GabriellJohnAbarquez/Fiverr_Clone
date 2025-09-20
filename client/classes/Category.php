<?php
class Category {
    private $conn;

    public function __construct($pdo) {
        $this->conn = $pdo;
    }

    // Get all categories
    public function getCategories() {
        $sql = "SELECT * FROM categories ORDER BY name ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get subcategories by category
    public function getSubcategories($category_id) {
        $sql = "SELECT * FROM subcategories WHERE category_id = ? ORDER BY name ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$category_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Find category by ID
    public function getCategoryById($category_id) {
        $sql = "SELECT * FROM categories WHERE category_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$category_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Find subcategory by ID
    public function getSubcategoryById($subcategory_id) {
        $sql = "SELECT * FROM subcategories WHERE subcategory_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$subcategory_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
