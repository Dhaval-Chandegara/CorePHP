<?php
session_start();
if (isset($_SESSION['email'])) {
    unset($_SESSION['email']);
    $_SESSION['logout'] = "Logout Successfully";
    header('location: index.php');
} else {
    header('location: index.php');
}

?>