<?php
require_once 'Database.php';

/**
 * Admin Proposal class.
 * Handles all Proposal-related operations for the admin.
 */
class Proposal extends Database {

    /**
     * Get all proposals, optionally filtered by user ID.
     * Admin can view all proposals.
     */
    public function getProposals($id = null) {
        if ($id) {
            $sql = "SELECT Proposals.*, fiverr_clone_users.*,
                           Proposals.date_added AS proposals_date_added
                    FROM Proposals
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

    public function getProposalsByUserID($user_id) {
        $sql = "SELECT Proposals.*, fiverr_clone_users.*,
                       Proposals.date_added AS proposals_date_added
                FROM Proposals
                JOIN fiverr_clone_users ON Proposals.user_id = fiverr_clone_users.user_id
                WHERE Proposals.user_id = ?
                ORDER BY Proposals.date_added DESC";
        return $this->executeQuery($sql, [$user_id]);
    }

    public function deleteProposal($id) {
        $sql = "DELETE FROM Proposals WHERE Proposal_id = ?";
        return $this->executeNonQuery($sql, [$id]);
    }

    // Admin might also want to approve or flag proposals
    public function approveProposal($proposal_id) {
        $sql = "UPDATE Proposals SET status = 'approved' WHERE Proposal_id = ?";
        return $this->executeNonQuery($sql, [$proposal_id]);
    }

    public function flagProposal($proposal_id) {
        $sql = "UPDATE Proposals SET status = 'flagged' WHERE Proposal_id = ?";
        return $this->executeNonQuery($sql, [$proposal_id]);
    }
}
?>
