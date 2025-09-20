<?php  
/**
 * Class for handling Offer-related operations.
 * Inherits CRUD methods from the Database class.
 */
class Offer extends Database {
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function createOffer($user_id, $description, $proposal_id) {
        try {
            $sql = "INSERT INTO offers (user_id, description, proposal_id) VALUES (?, ?, ?)";
            return $this->executeNonQuery($sql, [$user_id, $description, $proposal_id]);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                echo "<script>alert('Offer already submitted. You cannot submit another one for this proposal.'); window.history.back();</script>";
            } else {
                echo "<script>alert('An error occurred while submitting your offer. Please try again later.'); window.history.back();</script>";
            }
            return false;
        }
    }

    public function getOffers($offer_id = null) {
        if ($offer_id) {
            $sql = "SELECT * FROM offers WHERE offer_id = ?";
            return $this->executeQuerySingle($sql, [$offer_id]);
        }
        $sql = "SELECT * FROM offers 
                JOIN fiverr_clone_users ON offers.user_id = fiverr_clone_users.user_id 
                ORDER BY offers.date_added DESC";
        return $this->executeQuery($sql);
    }

    public function getOffersByProposalID($proposal_id) {
        $stmt = $this->pdo->prepare("SELECT o.*, u.username, u.contact_number FROM offers o JOIN fiverr_clone_users u ON o.client_id = u.user_id WHERE o.proposal_id = ? ORDER BY o.offer_date_added DESC");
        $stmt->execute([$proposal_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateOffer($description, $offer_id) {
        $sql = "UPDATE offers SET description = ? WHERE offer_id = ?";
        return $this->executeNonQuery($sql, [$description, $offer_id]);
    }

    public function deleteOffer($id) {
        $sql = "DELETE FROM offers WHERE offer_id = ?";
        return $this->executeNonQuery($sql, [$id]);
    }

    public function addOffer($client_id, $proposal_id, $description, $contact_number) {
        if ($this->hasSubmittedOffer($client_id, $proposal_id)) {
            return false;
        }
        $stmt = $this->pdo->prepare("INSERT INTO offers (client_id, proposal_id, description, contact_number, offer_date_added) VALUES (?, ?, ?, ?, NOW())");
        return $stmt->execute([$client_id, $proposal_id, $description, $contact_number]);
    }

    public function hasSubmittedOffer($client_id, $proposal_id) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM offers WHERE client_id = ? AND proposal_id = ?");
        $stmt->execute([$client_id, $proposal_id]);
        return $stmt->fetchColumn() > 0;
    }
}
?>
