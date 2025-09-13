<?php  
/**
 * Class for handling Proposal-related operations.
 * Inherits CRUD methods from the Database class.
 */
class Proposal extends Database {
    /**
     * Creates a new Proposal.
     * @param string $title The Proposal title.
     * @param string $content The Proposal content.
     * @param int $author_id The ID of the author.
     * @return int The ID of the newly created Proposal.
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
            $sql = "SELECT * FROM Proposals JOIN fiverr_clone_users on Proposals.user_id = fiverr_clone_users.user_id WHERE Proposal_id = ?";
            return $this->executeQuerySingle($sql, [$id]);
        }
        $sql = "SELECT Proposals.*, fiverr_clone_users.*, 
                Proposals.date_added AS proposals_date_added
                FROM Proposals JOIN fiverr_clone_users ON 
                Proposals.user_id = fiverr_clone_users.user_id
                ORDER BY Proposals.date_added DESC";
        return $this->executeQuery($sql);
    }


    public function getProposalsByUserID($user_id) {
        $sql = "SELECT Proposals.*, fiverr_clone_users.*, 
                Proposals.date_added AS proposals_date_added
                FROM Proposals JOIN fiverr_clone_users ON 
                Proposals.user_id = fiverr_clone_users.user_id
                WHERE proposals.user_id = ?
                ORDER BY Proposals.date_added DESC";
        return $this->executeQuery($sql, [$user_id]);
    }

    /**
     * Updates an Proposal.
     * @param int $id The Proposal ID to update.
     * @param string $title The new title.
     * @param string $content The new content.
     * @return int The number of affected rows.
     */
    public function updateProposal($description, $min_price, $max_price, $proposal_id, $image="") {
        if (!empty($image)) {
            $sql = "UPDATE Proposals SET description = ?, image = ?, min_price = ?, max_price = ? WHERE Proposal_id = ?";
            return $this->executeNonQuery($sql, [$description, $image, 
                $min_price, $max_price, $proposal_id]);
        }
        else {
            $sql = "UPDATE Proposals SET description = ?, min_price = ?, max_price = ? WHERE Proposal_id = ?";
                return $this->executeNonQuery($sql, [$description, 
                    $min_price, $max_price, $proposal_id]);  
        }
    }

    public function addViewCount($proposal_id) {
        $sql = "UPDATE Proposals SET view_count = view_count + 1 WHERE Proposal_id = ?";
        return $this->executeNonQuery($sql, [$proposal_id]);
    }

    
    /**
     * Deletes an Proposal.
     * @param int $id The Proposal ID to delete.
     * @return int The number of affected rows.
     */
    public function deleteProposal($id) {
        $sql = "DELETE FROM Proposals WHERE Proposal_id = ?";
        return $this->executeNonQuery($sql, [$id]);
    }
}
?>