<?php

session_start();
var_dump($_POST);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../config/db_con.php';
require '../req/student.php';
require '../req/teacher.php';
require '../req/admin.php';

// Instance of the db
$db = new DataB();
// Instance of the PDO
$pdo = $db->getPdo();

// Instance of student, teacher, or admin based on the identity
$identity = $_POST['identity'];
switch ($identity) {
    case 'student':
        $userClass = new Student($pdo);
        break;
    case 'teacher':
        $userClass = new Teacher($pdo);
        break;
    case 'administrator':
        $userClass = new Admin($pdo);
        break;
    default:
        die('Invalid user');
}

// Retrieve user_id from the session
$newPassword =  $_POST['newPass'] ?? null;
$oldPassword = $_POST['oldPass'] ?? null;
$confirmPassword = $_POST['confirmPass'] ?? null;
$user_id = $_POST['oldPass'] ?? null;
if ($user_id === null) {
    echo 'User ID is missing';
    exit;
}

// Handle profile picture upload
$profilePic = null;
if (isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] === UPLOAD_ERR_OK) {
    $profilePic = $_FILES['profilePicture']['name'];
    $dir = "../server/uploadpics/";
    $file = $dir . basename($profilePic);
    move_uploaded_file($_FILES["profilePicture"]["tmp_name"], $file);
}

if ($newPassword === $confirmPassword) {
    // Update it in the database
    $userClass->updateProfile($user_id, $newPassword);

    echo 'Profile updated successfully!';
} else {
    echo 'Passwords do not match';
}
