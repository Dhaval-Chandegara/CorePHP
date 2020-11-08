<?php
include 'User.php';
session_start();

if (!isset($_SESSION['email'])) {
    header('location: index.php');
}

$email = $_SESSION['email'];

$user = new User();
$row = mysqli_fetch_assoc($user->Get("SELECT * FROM user WHERE email='$email'"));

$hobbys = '';
$hobbies = ['cricket', 'music', 'driving'];
$temp = explode(',', $row['hobby']);
foreach ($hobbies as $key) {
    $checked = "";
    if (in_array($key, $temp)) {
        $checked = "checked";
    }
    $hobbys .= '<input type="checkbox" name="hobby[]" value="'.$key.'" '.$checked.'>'.ucfirst($key);
}


$signuperror = "";
$error = 0;
$nameerror = $cityerror = $hobbyerror = $doberror = $adderror = $avatarerror = "";
$name = $city = $hobby = $dob = $add = $avatar = "";
$file = null;
$oldavatar = "";

if (isset($_POST['update'])) {
    if (empty($_POST['name'])) {
        $nameerror = "Name field Required";
        $error = 1;
    } else {
        $name = $_POST['name'];
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

    if ($_FILES['avatar']['error'] == UPLOAD_ERR_NO_FILE) {
        $avatar = $row['avatar'];
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
            if (file_exists($row['avatar']))
                unlink($row['avatar']);
            move_uploaded_file($file['tmp_name'], $avatar);
        }
        $sql = "UPDATE user SET name='$name',address='$add',city='$city',hobby='$hobby',dob='$dob',avatar='$avatar' WHERE email='$email'";
        if ($User->Save($sql)) {
            $_SESSION['updateprofile'] = "Profile Update Successfully";
            header("location:home.php"); 
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
                            <td><input type="text" name="name" placeholder="Enter Name" value="<?= $row['name'] ?>"></td>
                            <td class="error"><?= $nameerror ?></td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td><input type="email" name="email" placeholder="Enter Email"  value="<?= $row['email'] ?>" disabled/></td>
                        </tr>
                        <tr>
                            <td>Mobile</td>
                            <td> <input type="number" name="mobile" placeholder="Enter Mobile No." value="<?= $row['mobile'] ?>" disabled> </td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td> <textarea name="address" cols="30" rows="5" noresize><?= $row['address'] ?></textarea> </td>
                            <td class="error"> <?= $adderror; ?> </td>
                        </tr>
                        <tr>
                        	<td>Select City</td>
                        	<td>
                        		<select name="city">
	                        		<option value="">Select City</option>
	                        		<option value="surat" <?= $row['city'] == "surat" ? "selected" : "" ?> >Surat</option>
	                        		<option value="rajkot" <?= $row['city'] == "rajkot" ? "selected" : "" ?>>Rajkot</option>
                        		</select>
                        	</td>
                        	<td class="error"><?= $cityerror ?></td>
                        </tr>
                        <tr>
                        	<td>Hobby</td>
                        	<td>
                        		<?= $hobbys ?>
                        	</td>
                        	<td class="error"><?= $hobbyerror ?></td>
                        </tr>
                        <tr>
                        	<td>Date of Birth</td>
                        	<td> <input type="date" name="dob"  value="<?= $row['dob'] ?>"> </td>
                        	<td class="error"><?= $doberror; ?></td>
                        </tr>
                        <tr>
                            <td>Select Profile Image</td>
                            <td> 
                                <?php if(file_exists($row['avatar'])): ?>
                                    <img src="<?= $row['avatar'] ?>" alt="avatar" width="80" height="80"> <br>
                                <?php else: ?>
                                    <div style="margin: 10px;color: red;">Image not Found</div>
                                <?php endif; ?>
                                <input type="file" accept="image/*" name="avatar">
                            </td>
                            <td class="error"> <?= $avatarerror ?> </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <input type="submit" value="Update" name="update" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
            <a href="index.php">Already Have An Account?</a>
        </center>
    </body>
</html>

