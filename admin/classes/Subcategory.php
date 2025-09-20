<?php
require_once 'Database.php';

class Subcategory extends Database {

    public function addSubcategory($category_id, $name) {
        $sql = "INSERT INTO subcategories (category_id, name) VALUES (?, ?)";
        try {
            return $this->executeNonQuery($sql, [$category_id, $name]);
        } catch (\PDOException $e) {
            error_log("Failed to add subcategory: " . $e->getMessage());
            return false;
        }
    }

    public function getSubcategories($category_id = null) {
        if ($category_id) {
            $sql = "SELECT * FROM subcategories WHERE category_id = ? ORDER BY subcategory_id DESC";
            return $this->executeQuery($sql, [$category_id]);
        }
        $sql = "SELECT * FROM subcategories ORDER BY subcategory_id DESC";
        return $this->executeQuery($sql);
    }

    public function deleteSubcategory($id) {
        $sql = "DELETE FROM subcategories WHERE subcategory_id = ?";
        return $this->executeNonQuery($sql, [$id]);
    }
}
?>
