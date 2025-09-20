<?php
require_once 'Database.php';

class Category extends Database {

    public function getCategories() {
        $sql = "SELECT * FROM categories ORDER BY category_id DESC"; // newest first
        return $this->executeQuery($sql);
    }
    public function addCategory($name) {
        $sql = "INSERT INTO categories (name) VALUES (?)";
        return $this->executeNonQuery($sql, [$name]);
    }

    public function deleteCategory($id) {
        $sql = "DELETE FROM categories WHERE category_id = ?";
        return $this->executeNonQuery($sql, [$id]);
    }
}
?>
