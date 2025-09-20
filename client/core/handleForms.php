<?php  
require_once '../classloader.php';

// ---------------------- REGISTER NEW USER ----------------------
if (isset($_POST['insertNewUserBtn'])) {
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $contact_number = htmlspecialchars(trim($_POST['contact_number']));
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (!empty($username) && !empty($email) && !empty($password) && !empty($confirm_password)) {

        if ($password == $confirm_password) {

            if (!$userObj->usernameExists($username)) {

                if ($userObj->registerUser($username, $email, $password, $contact_number)) {
                    header("Location: ../login.php");
                } else {
                    $_SESSION['message'] = "An error occurred with the query!";
                    $_SESSION['status'] = '400';
                    header("Location: ../register.php");
                }
            } else {
                $_SESSION['message'] = $username . " username is already taken";
                $_SESSION['status'] = '400';
                header("Location: ../register.php");
            }
        } else {
            $_SESSION['message'] = "Please make sure both passwords are equal";
            $_SESSION['status'] = '400';
            header("Location: ../register.php");
        }
    } else {
        $_SESSION['message'] = "Please make sure there are no empty input fields";
        $_SESSION['status'] = '400';
        header("Location: ../register.php");
    }
}

// ---------------------- LOGIN USER ----------------------
if (isset($_POST['loginUserBtn'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {

        $userData = $userObj->loginUser($email, $password); // Should return user info array or false
        if ($userData) {
            // Set session variables
            $_SESSION['user_id'] = $userData['user_id'];
            $_SESSION['username'] = $userData['username'];
            $_SESSION['role'] = $userData['role']; // e.g., 'client' or 'freelancer'

            // Redirect based on role
            if ($_SESSION['role'] === 'client') {
				header("Location: /Fiverr_Clone/client/index.php");
				exit();
			} elseif ($_SESSION['role'] === 'freelancer') {
				header("Location: /Fiverr_Clone/freelancer/index.php");
				exit();
			} else {
				$_SESSION['message'] = "Invalid user role. Contact admin.";
				$_SESSION['status'] = "400";
				header("Location: /Fiverr_Clone/client/login.php");
				exit();
			}
        } else {
            $_SESSION['message'] = "Username/password invalid";
            $_SESSION['status'] = "400";
            header("Location: ../login.php");
            exit();
        }
    } else {
        $_SESSION['message'] = "Please make sure there are no empty input fields";
        $_SESSION['status'] = '400';
        header("Location: ../login.php");
        exit();
    }
}

// ---------------------- LOGOUT USER ----------------------
if (isset($_GET['logoutUserBtn'])) {
    // Ensure session and userObj exist before logging out
    $userObj->logout();

    // Redirect to the correct page after logout
    header("Location: ../../index.php");
    exit();
}

// ---------------------- UPDATE USER ----------------------
if (isset($_POST['updateUserBtn'])) {
    $contact_number = htmlspecialchars($_POST['contact_number']);
    $bio_description = htmlspecialchars($_POST['bio_description']);
    
    if ($userObj->updateUser($contact_number, $bio_description, $_SESSION['user_id'])) {
        header("Location: ../profile.php");
    }
}

// ---------------------- INSERT OFFER ----------------------
if (isset($_POST['insertOfferBtn'])) {
    $user_id = $_SESSION['user_id'];
    $proposal_id = $_POST['proposal_id'];
    $description = htmlspecialchars($_POST['description']);

    // Check if user already submitted an offer for this proposal
    if ($offerObj->hasUserOffered($user_id, $proposal_id)) {
        $_SESSION['message'] = "You have already submitted an offer for this proposal.";
        $_SESSION['status'] = "400";
        header("Location: ../index.php");
        exit();
    }

    if ($offerObj->createOffer($user_id, $description, $proposal_id)) {
        $_SESSION['message'] = "Offer submitted successfully!";
        $_SESSION['status'] = "200";
        header("Location: ../index.php");
        exit();
    } else {
        $_SESSION['message'] = "Failed to submit the offer. Please try again.";
        $_SESSION['status'] = "400";
        header("Location: ../index.php");
        exit();
    }
}

// ---------------------- UPDATE OFFER ----------------------
if (isset($_POST['updateOfferBtn'])) {
    $description = htmlspecialchars($_POST['description']);
    $offer_id = $_POST['offer_id'];
    
    if ($offerObj->updateOffer($description, $offer_id)) {
        $_SESSION['message'] = "Offer updated successfully!";
        $_SESSION['status'] = '200';
        header("Location: ../index.php");
    }
}

// ---------------------- DELETE OFFER ----------------------
if (isset($_POST['deleteOfferBtn'])) {
    $offer_id = $_POST['offer_id'];
    
    if ($offerObj->deleteOffer($offer_id)) {
        $_SESSION['message'] = "Offer deleted successfully!";
        $_SESSION['status'] = '200';
        header("Location: ../index.php");
    }
}
