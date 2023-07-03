<?php
include 'dbconnect.php';

if (isset($_GET['adminName'])){
    $adminName = $_GET['adminName'];
}

if (!isset($_GET['id'])) {
    header('Location: home.php?adminName='.$adminName);
    exit;
}

$id = $_GET['id'];

// Fetch diary entry by ID
$query = $conn->prepare('SELECT * FROM tbl_checkin WHERE checkin_id = ?');
$query->execute([$id]);
$entry = $query->fetch(PDO::FETCH_ASSOC);

// If entry doesn't exist, redirect to index.php
if (!$entry) {
    header('Location: home.php?adminName='.$adminName);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the user has confirmed the deletion
    if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
        // Delete diary entry by ID
        $query = $conn->prepare('DELETE FROM tbl_checkin WHERE checkin_id = ?');
        $query->execute([$id]);

        echo "<script>alert('Delete Success');</script>";
        echo "<script> window.location.replace('home.php?adminName=$adminName')</script>";
    } else {
        echo "<script>alert('Delete Failed');</script>";
        echo "<script> window.location.replace('home.php?adminName=$adminName')</script>";
    }
}
?>