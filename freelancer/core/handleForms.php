<?php  
require_once '../classloader.php';

// ---------------------- REGISTER USER ----------------------
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
                    exit();
                } else {
                    $_SESSION['message'] = "An error occured with the query!";
                    $_SESSION['status'] = '400';
                    header("Location: ../register.php");
                    exit();
                }
            } else {
                $_SESSION['message'] = $username . " as username is already taken";
                $_SESSION['status'] = '400';
                header("Location: ../register.php");
                exit();
            }
        } else {
            $_SESSION['message'] = "Please make sure both passwords are equal";
            $_SESSION['status'] = '400';
            header("Location: ../register.php");
            exit();
        }
    } else {
        $_SESSION['message'] = "Please make sure there are no empty input fields";
        $_SESSION['status'] = '400';
        header("Location: ../register.php");
        exit();
    }
}

// ---------------------- LOGIN USER ----------------------
if (isset($_POST['loginUserBtn'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        if ($userObj->loginUser($email, $password)) {
            header("Location: ../index.php");
            exit();
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
    require_once '../classloader.php';
    $userObj->logout();

    // Redirect to root index.php (outside freelancer/)
    header("Location: ../../index.php"); // <-- adjust based on your folder structure
    exit();
}

// ---------------------- UPDATE USER ----------------------
if (isset($_POST['updateUserBtn'])) {
    $contact_number = htmlspecialchars($_POST['contact_number']);
    $bio_description = htmlspecialchars($_POST['bio_description']);
    if ($userObj->updateUser($contact_number, $bio_description, $_SESSION['user_id'])) {
        $_SESSION['status'] = "200";
        $_SESSION['message'] = "Profile updated successfully!";
        header("Location: ../profile.php");
        exit();
    }
}

// ---------------------- INSERT PROPOSAL ----------------------
if (isset($_POST['insertNewProposalBtn'])) {
    $user_id = $_SESSION['user_id'];
    $description = htmlspecialchars($_POST['description']);
    $min_price = htmlspecialchars($_POST['min_price']);
    $max_price = htmlspecialchars($_POST['max_price']);
    $category_id = $_POST['category_id'];
    $subcategory_id = $_POST['subcategory_id'];

    $fileName = $_FILES['image']['name'];
    $tempFileName = $_FILES['image']['tmp_name'];
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
    $uniqueID = sha1(md5(rand(1,9999999)));
    $imageName = $uniqueID.".".$fileExtension;
    $folder = "../../images/".$imageName;

    if (move_uploaded_file($tempFileName, $folder)) {
        if ($proposalObj->insertProposal($user_id, $description, $min_price, $max_price, $imageName, $category_id, $subcategory_id)) {
            $_SESSION['status'] = "200";
            $_SESSION['message'] = "Proposal saved successfully!";
            header("Location: ../index.php");
            exit();
        }
    }
}

// ---------------------- UPDATE PROPOSAL ----------------------
if (isset($_POST['updateProposalBtn'])) {
    $min_price = $_POST['min_price'];
    $max_price = $_POST['max_price'];
    $proposal_id = $_POST['proposal_id'];
    $description = htmlspecialchars($_POST['description']);
    if ($proposalObj->updateProposal($description, $min_price, $max_price, $proposal_id)) {
        $_SESSION['status'] = "200";
        $_SESSION['message'] = "Proposal updated successfully!";
        header("Location: ../your_proposals.php");
        exit();
    }
}

// ---------------------- DELETE PROPOSAL ----------------------
if (isset($_POST['deleteProposalBtn'])) {
    $proposal_id = $_POST['proposal_id'];
    $image = $_POST['image'];

    if ($proposalObj->deleteProposal($proposal_id)) {
        unlink("../../images/".$image);
        $_SESSION['status'] = "200";
        $_SESSION['message'] = "Proposal deleted successfully!";
        header("Location: ../your_proposals.php");
        exit();
    }
}

if (isset($_POST['submitOfferBtn'])) {
    $client_id = $_SESSION['user_id'];
    $proposal_id = $_POST['proposal_id'];
    $description = htmlspecialchars($_POST['description']);
    $contact_number = $_POST['contact_number'];

    // Check if client already submitted an offer
    if ($offerObj->hasSubmittedOffer($client_id, $proposal_id)) {
        $_SESSION['status'] = "400";
        $_SESSION['message'] = "You have already submitted an offer for this proposal.";
        header("Location: ../proposal_details.php?proposal_id=$proposal_id");
        exit();
    }

    // Add offer
    if ($offerObj->addOffer($client_id, $proposal_id, $description, $contact_number)) {
        $_SESSION['status'] = "200";
        $_SESSION['message'] = "Offer submitted successfully!";
        header("Location: ../proposal_details.php?proposal_id=$proposal_id");
        exit();
    } else {
        $_SESSION['status'] = "400";
        $_SESSION['message'] = "Failed to submit offer. Please try again.";
        header("Location: ../proposal_details.php?proposal_id=$proposal_id");
        exit();
    }

	if (isset($_POST['updateOfferBtn'])) {
		$description = htmlspecialchars($_POST['description']);
		$offer_id = $_POST['offer_id'];
		if ($offerObj->updateOffer($description, $offer_id)) {
			$_SESSION['message'] = "Offer updated successfully!";
			$_SESSION['status'] = '200';
			header("Location: ../index.php");
		}
	}
}
?>