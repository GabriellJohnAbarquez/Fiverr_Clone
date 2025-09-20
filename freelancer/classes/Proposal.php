<?php  
/**
 * Class for handling Proposal-related operations for Freelancers.
 * Inherits CRUD methods from the Database class.
 */
class Proposal extends Database {
    protected $pdo; // must be protected, not private

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Inserts a new proposal
     * @param int $user_id The ID of the user creating the proposal.
     * @param string $description The Proposal description.
     * @param float $min_price The minimum price.
     * @param float $max_price The maximum price.
     * @param string $image The Proposal image path.
     * @param int $category_id Category ID
     * @param int $subcategory_id Subcategory ID
     * @return bool
     */
    public function insertProposal($user_id, $description, $min_price, $max_price, $image, $category_id, $subcategory_id) {
        $stmt = $this->pdo->prepare("INSERT INTO proposals (user_id, description, min_price, max_price, image, category_id, subcategory_id, proposals_date_added) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
        return $stmt->execute([$user_id, $description, $min_price, $max_price, $image, $category_id, $subcategory_id]);
    }

    /**
     * Get all proposals
     * @return array
     */
    public function getProposals() {
        // Get all proposals
        $stmt = $this->pdo->prepare("SELECT p.*, u.username 
                                    FROM proposals p 
                                    JOIN fiverr_clone_users u ON p.user_id = u.user_id 
                                    ORDER BY p.date_added DESC"); // change here
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get proposals by user ID
     * @param int $user_id
     * @return array
     */
    public function getProposalsByUserID($user_id) {
        // Get proposals by user ID
        $stmt = $this->pdo->prepare("SELECT p.*, u.username 
                                    FROM proposals p 
                                    JOIN fiverr_clone_users u ON p.user_id = u.user_id 
                                    WHERE p.user_id = ? 
                                    ORDER BY p.date_added DESC"); // change here
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Updates a Proposal.
     * @param string $description The new description.
     * @param float $min_price The new minimum price.
     * @param float $max_price The new maximum price.
     * @param int $proposal_id The Proposal ID.
     * @param string $image Optional new image path.
     * @return int The number of affected rows.
     */
    public function updateProposal($description, $min_price, $max_price, $proposal_id, $image="") {
        if (!empty($image)) {
            $sql = "UPDATE proposals 
                    SET description = ?, image = ?, min_price = ?, max_price = ? 
                    WHERE proposal_id = ?";
            return $this->executeNonQuery($sql, [$description, $image, $min_price, $max_price, $proposal_id]);
        } else {
            $sql = "UPDATE proposals 
                    SET description = ?, min_price = ?, max_price = ? 
                    WHERE proposal_id = ?";
            return $this->executeNonQuery($sql, [$description, $min_price, $max_price, $proposal_id]);  
        }
    }

    /**
     * Increments the view count for a Proposal.
     * @param int $proposal_id The Proposal ID.
     * @return int The number of affected rows.
     */
    public function addViewCount($proposal_id) {
        $sql = "UPDATE proposals SET view_count = view_count + 1 WHERE proposal_id = ?";
        return $this->executeNonQuery($sql, [$proposal_id]);
    }

    /**
     * Deletes a Proposal.
     * @param int $id The Proposal ID.
     * @return int The number of affected rows.
     */
    public function deleteProposal($id) {
        $sql = "DELETE FROM proposals WHERE proposal_id = ?";
        return $this->executeNonQuery($sql, [$id]);
    }
}
?>
