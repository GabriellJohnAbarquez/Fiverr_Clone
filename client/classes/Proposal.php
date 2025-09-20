<?php  
/**
 * Class for handling Proposal-related operations for Clients.
 * Inherits CRUD methods from the Database class.
 */
class Proposal extends Database {
    /**
     * Creates a new Proposal.
     * @param int $user_id The ID of the user creating the proposal.
     * @param string $description The Proposal description.
     * @param string $image The Proposal image path.
     * @param float $min_price The minimum price.
     * @param float $max_price The maximum price.
     * @return int The number of affected rows.
     */
    public function createProposal($user_id, $description, $image, $min_price, $max_price) {
        $sql = "INSERT INTO Proposals (user_id, description, image, min_price, max_price) VALUES (?, ?, ?, ?, ?)";
        return $this->executeNonQuery($sql, [$user_id, $description, $image, $min_price, $max_price]);
    }

    /**
     * Retrieves Proposals from the database.
     * @param int|null $id The Proposal ID to retrieve, or null for all Proposals.
     * @return array
     */
    public function getProposals($id = null) {
        if ($id) {
            $sql = "SELECT * FROM Proposals 
                    JOIN fiverr_clone_users ON Proposals.user_id = fiverr_clone_users.user_id 
                    WHERE Proposal_id = ?";
            return $this->executeQuerySingle($sql, [$id]);
        }
        $sql = "SELECT Proposals.*, fiverr_clone_users.*, 
                       Proposals.date_added AS proposals_date_added
                FROM Proposals 
                JOIN fiverr_clone_users ON Proposals.user_id = fiverr_clone_users.user_id
                ORDER BY Proposals.date_added DESC";
        return $this->executeQuery($sql);
    }

    /**
     * Retrieves all proposals belonging to a specific user.
     * @param int $user_id The user ID.
     * @return array
     */
    public function getProposalsByUserID($user_id) {
        $sql = "SELECT Proposals.*, fiverr_clone_users.*, 
                       Proposals.date_added AS proposals_date_added
                FROM Proposals 
                JOIN fiverr_clone_users ON Proposals.user_id = fiverr_clone_users.user_id
                WHERE Proposals.user_id = ?
                ORDER BY Proposals.date_added DESC";
        return $this->executeQuery($sql, [$user_id]);
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
            $sql = "UPDATE Proposals 
                    SET description = ?, image = ?, min_price = ?, max_price = ? 
                    WHERE Proposal_id = ?";
            return $this->executeNonQuery($sql, [$description, $image, $min_price, $max_price, $proposal_id]);
        } else {
            $sql = "UPDATE Proposals 
                    SET description = ?, min_price = ?, max_price = ? 
                    WHERE Proposal_id = ?";
            return $this->executeNonQuery($sql, [$description, $min_price, $max_price, $proposal_id]);  
        }
    }

    /**
     * Increments the view count for a Proposal.
     * @param int $proposal_id The Proposal ID.
     * @return int The number of affected rows.
     */
    public function addViewCount($proposal_id) {
        $sql = "UPDATE Proposals SET view_count = view_count + 1 WHERE Proposal_id = ?";
        return $this->executeNonQuery($sql, [$proposal_id]);
    }

    /**
     * Deletes a Proposal.
     * @param int $id The Proposal ID.
     * @return int The number of affected rows.
     */
    public function deleteProposal($id) {
        $sql = "DELETE FROM Proposals WHERE Proposal_id = ?";
        return $this->executeNonQuery($sql, [$id]);
    }
}
?>
