<?php  
/**
 * Class for handling Offer-related operations.
 * Inherits CRUD methods from the Database class.
 */
class Offer extends Database {

    public function createOffer($user_id, $description, $proposal_id) {
    try {
        $sql = "INSERT INTO offers (user_id, description, proposal_id) VALUES (?, ?, ?)";
        return $this->executeNonQuery($sql, [$user_id, $description, $proposal_id]);
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            // client already made an offer for this proposal
            echo "<script>alert('Offer already submitted. You cannot submit another one for this proposal.'); window.history.back();</script>";
        } else {
            // handle any other database error
            echo "<script>alert('An error occurred while submitting your offer. Please try again later.'); window.history.back();</script>";
        }
        return false; // stop execution
    }
    }

    public function getOffers($offer_id = null) {
        if ($offer_id) {
            $sql = "SELECT * FROM offers WHERE offer_id = ?";
            return $this->executeQuerySingle($sql, [$offer_id]);
        }
        $sql = "SELECT 
                    offers.*, fiverr_clone_users.*, 
                    offers.date_added AS offer_date_added
                FROM offers JOIN fiverr_clone_users ON 
                offers.user_id = fiverr_clone_users.user_id 
                ORDER BY offers.date_added DESC";
        return $this->executeQuery($sql);
    }


    public function getOffersByProposalID($proposal_id) {
        $sql = "SELECT 
                    offers.*, fiverr_clone_users.*, 
                    offers.date_added AS offer_date_added 
                FROM Offers 
                JOIN fiverr_clone_users ON 
                    offers.user_id = fiverr_clone_users.user_id
                WHERE proposal_id = ? 
                ORDER BY Offers.date_added DESC";
        return $this->executeQuery($sql, [$proposal_id]);
    }

    public function updateOffer($description, $offer_id) {
        $sql = "UPDATE Offers SET description = ? WHERE Offer_id = ?";
        return $this->executeNonQuery($sql, [$description, $offer_id]);
    }
    

    /**
     * Deletes an Offer.
     * @param int $id The Offer ID to delete.
     * @return int The number of affected rows.
     */
    public function deleteOffer($id) {
        $sql = "DELETE FROM Offers WHERE Offer_id = ?";
        return $this->executeNonQuery($sql, [$id]);
    }

    public function hasUserOffered($user_id, $proposal_id) {
    $sql = "SELECT COUNT(*) as count FROM offers WHERE user_id = ? AND proposal_id = ?";
    $result = $this->executeQuerySingle($sql, [$user_id, $proposal_id]);
    return $result['count'] > 0;
}

}
?>