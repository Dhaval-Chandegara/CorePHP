<?php
include 'User.php';

session_start();

if (isset($_SESSION['email'])) {
    header('location: home.php');
}

$signuperror = "";
$error = 0;
$nameerror = $emailerror = $passworderror = $cityerror = $hobbyerror = $doberror = $adderror = $mobileerror = $avatarerror = "";
$name = $email = $password = $city = $hobby = $dob = $add = $mobile = $avatar = "";
$file = null;

if (isset($_POST['signup'])) {
    if (empty($_POST['name'])) {
        $nameerror = "Name field Required";
        $error = 1;
    } else {
        $name = $_POST['name'];
    }
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
    if (empty($_POST['city'])) {
        $error = 1;
        $cityerror = "Please Select City";
    } else {
        $city = $_POST['city'];
    }
    if (empty($_POST['hobby'])) {
        $error = 1;
        $hobbyerror = "Please Select Hobby";
    } else {
        $hobby = implode(',',$_POST['hobby']);
    }
    if (empty($_POST['dob'])) {
        $error = 1;
        $doberror = "Please Select Date of Birth";
    } else {
        $dob = $_POST['dob'];
    }
    if (empty($_POST['address'])) {
        $error = 1;
        $adderror = "Address Field Required";
    } else {
        $add = $_POST['address'];
    }
    if (empty($_POST['mobile'])) {
        $error = 1;
        $mobileerror = "Mobile Field Required";
    } else {
        $mobile = $_POST['mobile'];
        if (strlen($mobile) != 10) {
            $error = 1;
            $mobileerror = "Enter Proper Mobile No.";
        }
    }
    if (!isset($_FILES['avatar'])) {
        $error = 1;
        $avatarerror = "Please Select Profile Image";
    } else {
        $file = $_FILES['avatar'];
        $type = pathinfo($file['name'], PATHINFO_EXTENSION);
        if ($type != "png" && $type != "jpeg" && $type != "jpg") {
            $error = 1;
            $avatarerror = "Invalid Image.";
        } else {
            $avatar = "Uploads/".$file['name'];
        }
    }
    
    if ($error != 1) {
        $User = new User();
        if (!file_exists($avatar)) {
            move_uploaded_file($file['tmp_name'], $avatar);   
        }
        $sql = "INSERT INTO user VALUES('','$name','$email','$password',$mobile,'$add','$city','$hobby','$dob','$avatar')";
        if ($User->Save($sql)) {
            $_SESSION['signup'] = "SignUp Successfully";
            header("location:index.php"); 
        } else {
            $signuperror = "Something Went Wrong";
        }
    }
}

?>

<html>
    <head>
        <title>Sign Up</title>
        <style>
        .error{
            color: red;
        }
        </style>
    </head>
    <body>
        <center>
            <div>
                <p class="error"><?= $signuperror ?></p>
            </div>
            <form method="post" enctype="multipart/form-data">
                <table border="0" cellpadding="5">
                    <tbody>
                        <tr>
                            <td>Name</td>
                            <td><input type="text" name="name" placeholder="Enter Name" value="<?= $name ?>"></td>
                            <td class="error"><?= $nameerror ?></td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td><input type="email" name="email" placeholder="Enter Email"  value="<?= $email ?>"/></td>
                            <td class="error"><?= $emailerror ?></td>
                        </tr>
                        <tr>
                            <td>Password</td>
                            <td><input type="password" name="password" placeholder="Enter Password"  value="<?= $password ?>"/></td>
                            <td class="error"><?= $passworderror ?></td>
                        </tr>
                        <tr>
                            <td>Mobile</td>
                            <td> <input type="number" name="mobile" placeholder="Enter Mobile No." value="<?= $mobile ?>"> </td>
                            <td class="error"> <?= $mobileerror ?> </td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td> <textarea name="address" cols="30" rows="5" noresize><?= $add ?></textarea> </td>
                            <td class="error"> <?= $adderror; ?> </td>
                        </tr>
                        <tr>
                        	<td>Select City</td>
                        	<td>
                        		<select name="city">
	                        		<option value="" selected>Select City</option>
	                        		<option value="surat">Surat</option>
	                        		<option value="rajkot">Rajkot</option>
                        		</select>
                        	</td>
                        	<td class="error"><?= $cityerror ?></td>
                        </tr>
                        <tr>
                        	<td>Hobby</td>
                        	<td>
                        		<input type="checkbox" name="hobby[]" value="cricket"> Cricket
                        		<input type="checkbox" name="hobby[]" value="music"> Music
                        		<input type="checkbox" name="hobby[]" value="driving"> Driving
                        	</td>
                        	<td class="error"><?= $hobbyerror ?></td>
                        </tr>
                        <tr>
                        	<td>Date of Birth</td>
                        	<td> <input type="date" name="dob"  value="<?= $dob ?>"> </td>
                        	<td class="error"><?= $doberror; ?></td>
                        </tr>
                        <tr>
                            <td>Select Profile Image</td>
                            <td> <input type="file" accept="image/*" name="avatar"> </td>
                            <td class="error"> <?= $avatarerror ?> </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <input type="submit" value="Sign Up" name="signup" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
            <a href="index.php">Already Have An Account?</a>
        </center>
    </body>
</html>

