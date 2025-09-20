<?php
require_once '../classloader.php';
session_start();

// ---------------------- REGISTER ADMIN ----------------------
if (isset($_POST['registerAdminBtn'])) {
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $contact_number = htmlspecialchars(trim($_POST['contact_number']));
    
    if (!empty($username) && !empty($email) && !empty($password) && !empty($confirm_password)) {
        if ($password === $confirm_password) {
            if (!$userObj->usernameExists($username)) {
                if ($userObj->registerUser($username, $email, $password, $contact_number, 'fiverr_administrator')) {
                    // ✅ Redirect to admin login after successful registration
                    $_SESSION['message'] = "Admin account created successfully!";
                    $_SESSION['status'] = "200";
                    header("Location: ../login.php"); 
                    exit();
                } else {
                    $_SESSION['message'] = "An error occurred during registration!";
                    $_SESSION['status'] = "400";
                }
            } else {
                $_SESSION['message'] = "Username is already taken!";
                $_SESSION['status'] = "400";
            }
        } else {
            $_SESSION['message'] = "Passwords do not match!";
            $_SESSION['status'] = "400";
        }
    } else {
        $_SESSION['message'] = "Please fill in all fields!";
        $_SESSION['status'] = "400";
    }
    header("Location: ../register.php"); // redirect back to registration if failed
    exit();
}

// ---------------------- LOGIN ADMIN ----------------------
if (isset($_POST['loginAdminBtn'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        $userData = $userObj->loginUser($email, $password);

        if ($userData && $userData['role'] === 'fiverr_administrator') {
            // Set session variables
            $_SESSION['user_id'] = $userData['user_id'];
            $_SESSION['username'] = $userData['username'];
            $_SESSION['role'] = $userData['role'];

            header("Location: ../index.php");
            exit();
        } else {
            $_SESSION['message'] = "Invalid email or password for admin.";
            $_SESSION['status'] = "400";
            header("Location: ../login.php");
            exit();
        }
    } else {
        $_SESSION['message'] = "Please fill all fields.";
        $_SESSION['status'] = "400";
        header("Location: ../login.php");
        exit();
    }
}

// ---------------------- LOGOUT ADMIN ----------------------
if (isset($_GET['logoutAdminBtn'])) {
    $userObj->logout();
    header("Location: ../../index.php");
    exit();
}

// ---------------------- ACCESS CONTROL ----------------------
// Only allow fiverr_administrator for category/subcategory actions
if (!$userObj->isLoggedIn() || $_SESSION['role'] !== 'fiverr_administrator') {
    header("Location: ../login.php");
    exit();
}

// ---------------------- ADD CATEGORY ----------------------
if (isset($_POST['addCategoryBtn'])) {
    $name = htmlspecialchars(trim($_POST['category_name']));
    if ($categoryObj->addCategory($name)) { 
        $_SESSION['message'] = "Category added successfully!";
        $_SESSION['status'] = "200";
    } else {
        $_SESSION['message'] = "Failed to add category.";
        $_SESSION['status'] = "400";
    }
    header("Location: ../index.php");
    exit();
}

// ---------------------- ADD SUBCATEGORY ----------------------
if (isset($_POST['addSubcategoryBtn'])) {
    $category_id = (int)$_POST['category_id'];
    $name = htmlspecialchars(trim($_POST['subcategory_name']));
    if ($subcategoryObj->addSubcategory($category_id, $name)) {
        $_SESSION['message'] = "Subcategory added successfully!";
        $_SESSION['status'] = "200";
    } else {
        $_SESSION['message'] = "Failed to add subcategory.";
        $_SESSION['status'] = "400";
    }
    header("Location: ../index.php");
    exit();
}

// ---------------------- DELETE CATEGORY ----------------------
if (isset($_GET['deleteCategory'])) {
    $category_id = (int)$_GET['deleteCategory'];
    if ($categoryObj->deleteCategory($category_id)) {
        $_SESSION['message'] = "Category deleted successfully!";
        $_SESSION['status'] = "200";
    } else {
        $_SESSION['message'] = "Failed to delete category.";
        $_SESSION['status'] = "400";
    }
    header("Location: ../index.php");
    exit();
}

// ---------------------- DELETE SUBCATEGORY ----------------------
if (isset($_GET['deleteSubcategory'])) {
    $id = (int)$_GET['deleteSubcategory'];
    if ($subcategoryObj->deleteSubcategory($id)) {
        $_SESSION['message'] = "Subcategory deleted!";
        $_SESSION['status'] = "200";
    } else {
        $_SESSION['message'] = "Failed to delete subcategory!";
        $_SESSION['status'] = "400";
    }
    header("Location: ../index.php");
    exit();
}

