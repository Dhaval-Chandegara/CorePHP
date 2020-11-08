<!DOCTYPE html>
<?php
include 'User.php';
session_start();

if (isset($_SESSION['email'])) {
    header('location: home.php');
}

$signinerror = "";
$emailerror = $passworderror = "";
$email = $password = "";
$error = 0;

if (isset($_POST['signin'])) {
    if (empty($_POST['email'])) {
        $emailerror = "Email field Required";
        $error = 1;
    } else {
        $email = $_POST['email'];
    }
    if (empty($_POST['password'])) {
        $passworderror = "password field Required";
        $error = 1;
    } else {
        $password = $_POST['password'];
    }
    if ($error != 1) {
        $user = new User();
        $sql = "SELECT * FROM user WHERE email='$email' AND password='$password'";
        $result = $user->Get($sql);
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['email'] = $row['email'];
            header('location: home.php');
        } else {
            $signinerror = "Access Denied.";
        }
    }
}

?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Sign In</title>
    </head>
    <body>
        <center>
            <div>
                <?php
                if (isset($_SESSION['logout'])) {
                    echo '<h4 style="color:green;">'.$_SESSION['logout'].'</h4>';
                    unset($_SESSION['logout']);
                }
                if (isset($_SESSION['signup'])) {
                    echo '<h4 style="color:green;">'.$_SESSION['signup'].'</h4>';
                    unset($_SESSION['signup']);
                }
                ?>
            </div>
            <div>
                <p style="color: red;"><?= $signinerror ?></p>
            </div>
            <form method="post">
                <table border="0" cellpadding="5">
                    <tbody>
                        <tr>
                            <td>Email</td>
                            <td><input type="email" name="email" placeholder="Enter Email" /></td>
                            <td style="color: red;"><?= $emailerror; ?></td>
                        </tr>
                        <tr>
                            <td>Password</td>
                            <td><input type="password" name="password" placeholder="Enter Password"/></td>
                            <td style="color: red;"><?= $passworderror; ?></td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <input type="submit" value="Sign In" name="signin" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
            <a href="signup.php">Don't Have an Account?</a>
        </center>
    </body>
</html>
