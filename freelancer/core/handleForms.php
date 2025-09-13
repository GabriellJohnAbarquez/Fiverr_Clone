<?php  
require_once '../classloader.php';

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
				}

				else {
					$_SESSION['message'] = "An error occured with the query!";
					$_SESSION['status'] = '400';
					header("Location: ../register.php");
				}
			}

			else {
				$_SESSION['message'] = $username . " as username is already taken";
				$_SESSION['status'] = '400';
				header("Location: ../register.php");
			}
		}
		else {
			$_SESSION['message'] = "Please make sure both passwords are equal";
			$_SESSION['status'] = '400';
			header("Location: ../register.php");
		}
	}
	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../register.php");
	}
}

if (isset($_POST['loginUserBtn'])) {
	$email = trim($_POST['email']);
	$password = trim($_POST['password']);

	if (!empty($email) && !empty($password)) {

		if ($userObj->loginUser($email, $password)) {
			header("Location: ../index.php");
		}
		else {
			$_SESSION['message'] = "Username/password invalid";
			$_SESSION['status'] = "400";
			header("Location: ../login.php");
		}
	}

	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../login.php");
	}

}

if (isset($_GET['logoutUserBtn'])) {
	$userObj->logout();
	header("Location: ../index.php");
}

if (isset($_POST['updateUserBtn'])) {
	$contact_number = htmlspecialchars($_POST['contact_number']);
	$bio_description = htmlspecialchars($_POST['bio_description']);
	if ($userObj->updateUser($contact_number, $bio_description, $_SESSION['user_id'])) {
		$_SESSION['status'] = "200";
		$_SESSION['message'] = "Profile updated successfully!";
		header("Location: ../profile.php");
	}
}

if (isset($_POST['insertNewProposalBtn'])) {
	$user_id = $_SESSION['user_id'];
	$description = htmlspecialchars($_POST['description']);
	$min_price = htmlspecialchars($_POST['min_price']);
	$max_price = htmlspecialchars($_POST['max_price']);

	// Get file name
	$fileName = $_FILES['image']['name'];

	// Get temporary file name
	$tempFileName = $_FILES['image']['tmp_name'];

	// Get file extension
	$fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

	// Generate random characters for image name
	$uniqueID = sha1(md5(rand(1,9999999)));

	// Combine image name and file extension
	$imageName = $uniqueID.".".$fileExtension;

	// Specify path
	$folder = "../../images/".$imageName;

	// Move file to the specified path 
	if (move_uploaded_file($tempFileName, $folder)) {
		if ($proposalObj->createProposal($user_id, $description, $imageName, $min_price, $max_price)) {
			$_SESSION['status'] = "200";
			$_SESSION['message'] = "Proposal saved successfully!";
			header("Location: ../index.php");
		}
	}
}

if (isset($_POST['updateProposalBtn'])) {
	$min_price = $_POST['min_price'];
	$max_price = $_POST['max_price'];
	$proposal_id = $_POST['proposal_id'];
	$description = htmlspecialchars($_POST['description']);
	if ($proposalObj->updateProposal($description, $min_price, $max_price, $proposal_id)) {
		$_SESSION['status'] = "200";
		$_SESSION['message'] = "Proposal updated successfully!";
		header("Location: ../your_proposals.php");
	}
}

if (isset($_POST['deleteProposalBtn'])) {
	$proposal_id = $_POST['proposal_id'];
	$image = $_POST['image'];

	if ($proposalObj->deleteProposal($proposal_id)) {
		// Delete file inside images folder
		unlink("../../images/".$image);
		
		$_SESSION['status'] = "200";
		$_SESSION['message'] = "Proposal deleted successfully!";
		header("Location: ../your_proposals.php");
	}
}
