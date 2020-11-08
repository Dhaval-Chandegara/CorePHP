<?php
include 'User.php';
session_start();

if (!isset($_SESSION['email'])) {
    header('location: index.php');
}

$email = $_SESSION['email'];

$user = new User();
$row = mysqli_fetch_assoc($user->Get("SELECT * FROM user WHERE email='$email'"));
?>
<html>
    <head>
        <title>Home</title>
        <style>
        .avatar {
            border-radius: 50%;
        }
        </style>
    </head>
    <body>
        <img class="avatar" src="<?= $row['avatar'] ?>" alt="avatar" width="50" height="50">
        <?php   
        if (isset($_SESSION['change'])) {
            echo "<h4 style='color: green;'>".$_SESSION['change']."</h4>";
            unset($_SESSION['change']);
        }
        if (isset($_SESSION['updateprofile'])) {
            echo "<h4 style='color: green;'>".$_SESSION['updateprofile']."</h4>";
            unset($_SESSION['updateprofile']);
        }
        ?>
        <h2>Welcome <?= $row['name'] ?></h2>
        <div>Email:-  <?= $email ?> | Mobile:- <?= $row['mobile'] ?> | Hobbies:- <?= $row['hobby'] ?> </div> <br>
        <a href="editprofile.php">Edit Profile</a>&nbsp;&nbsp;|&nbsp;&nbsp;
        <a href="changePassword.php">Change Password</a>&nbsp;&nbsp;|&nbsp;&nbsp;
        <a href="logout.php">Logout</a>
    </body>
</html>