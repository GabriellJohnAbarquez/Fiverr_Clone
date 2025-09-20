<?php
require_once 'Database.php';

/**
 * Admin Offer class.
 * Handles all Offer-related operations for the admin.
 */
class Offer extends Database {

    /**
     * Get all offers, optionally filtered by user ID.
     */
    public function getOffers($id = null) {
        if ($id) {
            $sql = "SELECT Offers.*, fiverr_clone_users.*,
                           Offers.date_added AS offers_date_added
                    FROM Offers
                    JOIN fiverr_clone_users ON Offers.user_id = fiverr_clone_users.user_id
                    WHERE offer_id = ?";
            return $this->executeQuerySingle($sql, [$id]);
        }
        $sql = "SELECT Offers.*, fiverr_clone_users.*,
                       Offers.date_added AS offers_date_added
                FROM Offers
                JOIN fiverr_clone_users ON Offers.user_id = fiverr_clone_users.user_id
                ORDER BY Offers.date_added DESC";
        return $this->executeQuery($sql);
    }

    public function getOffersByUserID($user_id) {
        $sql = "SELECT Offers.*, fiverr_clone_users.*,
                       Offers.date_added AS offers_date_added
                FROM Offers
                JOIN fiverr_clone_users ON Offers.user_id = fiverr_clone_users.user_id
                WHERE Offers.user_id = ?
                ORDER BY Offers.date_added DESC";
        return $this->executeQuery($sql, [$user_id]);
    }

    public function deleteOffer($id) {
        $sql = "DELETE FROM Offers WHERE offer_id = ?";
        return $this->executeNonQuery($sql, [$id]);
    }

    // Admin might approve or reject offers
    public function approveOffer($offer_id) {
        $sql = "UPDATE Offers SET status = 'approved' WHERE offer_id = ?";
        return $this->executeNonQuery($sql, [$offer_id]);
    }

    public function rejectOffer($offer_id) {
        $sql = "UPDATE Offers SET status = 'rejected' WHERE offer_id = ?";
        return $this->executeNonQuery($sql, [$offer_id]);
    }
}
?>
