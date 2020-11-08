<?php
include 'User.php';
session_start();

if (!isset($_SESSION['email'])) {
    header('location: index.php');
}

$email = $_SESSION['email'];

$user = new User();
$row = mysqli_fetch_assoc($user->Get("SELECT * FROM user WHERE email='$email'"));

$changeerror = "";
$error = 0;
$oldpasserror = $newpasserror = $rnewpasserror = "";
$newpass = "";

if (isset($_POST['change'])) {
    if (empty($_POST['oldpass'])) {
        $oldpasserror = "Old password field Required";
        $error = 1;
    } else {
        if ($row['password']!=$_POST['oldpass']) {
            $oldpasserror = "Old password does not Match";
            $error = 1;
        }
    }
    if (empty($_POST['newpass'])) {
        $newpasserror = "New password field Required";
        $error = 1;
    } else {
        $newpass = $_POST['newpass'];
    }
    if (empty($_POST['rnewpass'])) {
        $rnewpasserror = "Re-enter new password";
        $error = 1;
    } else {
        if ($newpass!=$_POST['rnewpass']) {
            $rnewpasserror = "Re-type Password does not Match New Password";
            $error = 1;
        }
    }
    if ($error!=1) {
        $user = new User();
        $sql = "UPDATE user SET password='$newpass' WHERE email='$email'";
        if ($user->Save($sql) == 200) {
            $_SESSION['change'] = "Password Change Successfully";
            header('location: home.php');
        } else {
            $changeerror = "Something Went Wrong";
        }
    }
}

?>
<html>
    <head>
        <title>Change Password</title>
    </head>
    <body>
        <center>
            <p style="color: red;"><?= $changeerror ?></p>
            <form method="post">
                <table>
                    <tr>
                        <td>Old Password</td>
                        <td><input type="password" name="oldpass" placeholder="Old Password"></td>
                        <td style="color: red;"><?= $oldpasserror ?></td>
                    </tr>
                    <tr>
                        <td>New Password</td>
                        <td><input type="password" name="newpass" placeholder="New Password"></td>
                        <td style="color: red;"><?= $newpasserror ?></td>
                    </tr>
                    <tr>
                        <td>Re-type New Password</td>
                        <td><input type="password" name="rnewpass" placeholder="Re-type New Password"></td>
                        <td style="color: red;"><?= $rnewpasserror ?></td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <input type="submit" value="Change" name="change">
                        </td>
                    </tr>
                </table>
            </form>
        </center>
    </body>
</html>
