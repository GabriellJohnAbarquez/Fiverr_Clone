<?php  

require_once 'Database.php';
/**
 * Class for handling User-related operations.
 * Inherits CRUD methods from the Database class.
 */
class User extends Database {

    public function startSession() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function usernameExists($username) {
        $sql = "SELECT COUNT(*) as username_count FROM fiverr_clone_users WHERE username = ?";
        $count = $this->executeQuerySingle($sql, [$username]);
        return $count['username_count'] > 0;
    }

    /**
     * Registers a new user (defaults to client role).
     */
    public function registerUser($username, $email, $password, $contact_number, $role = 'client') {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO fiverr_clone_users (username, email, password, role, contact_number) VALUES (?, ?, ?, ?, ?)";
        try {
            $this->executeNonQuery($sql, [$username, $email, $hashed_password, $role, $contact_number]);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Logs in a user by verifying credentials.
     */
    public function loginUser($email, $password) {
        $sql = "SELECT * FROM fiverr_clone_users WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user; // login successful
        } else {
            return false; // login failed
        }
    } // <-- closing brace added here

    public function isLoggedIn() {
        $this->startSession();
        return isset($_SESSION['user_id']);
    }

    // role checks
    public function isClient() {
        $this->startSession();
        return isset($_SESSION['role']) && $_SESSION['role'] === 'client';
    }

    public function isFreelancer() {
        $this->startSession();
        return isset($_SESSION['role']) && $_SESSION['role'] === 'freelancer';
    }

    public function isAdmin() {
        $this->startSession();
        return isset($_SESSION['role']) && $_SESSION['role'] === 'fiverr_administrator';
    }

    public function logout() {
        $this->startSession();
        session_unset();
        session_destroy();
    }

    public function getUsers($id = null) {
        if ($id) {
            $sql = "SELECT * FROM fiverr_clone_users WHERE user_id = ?";
            return $this->executeQuerySingle($sql, [$id]);
        }
        $sql = "SELECT * FROM fiverr_clone_users";
        return $this->executeQuery($sql);
    }

    public function updateUser($contact_number, $bio_description, $user_id, $display_picture="") {
        if (empty($display_picture)) {
            $sql = "UPDATE fiverr_clone_users SET contact_number = ?, bio_description = ? WHERE user_id = ?";
            return $this->executeNonQuery($sql, [$contact_number, $bio_description, $user_id]);
        }
        // You may want to handle updating display_picture if provided
    }

    public function deleteUser($id) {
        $sql = "DELETE FROM fiverr_clone_users WHERE user_id = ?";
        return $this->executeNonQuery($sql, [$id]);
    }
}
?>
