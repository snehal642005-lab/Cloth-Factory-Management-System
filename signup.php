<?php
ob_start();
session_start();
include "db.php";

$msg = "";

if(isset($_POST['signup'])){

    $name  = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $pass  = $_POST['password'];

    if(!preg_match("/^[6-9][0-9]{9}$/", $phone)){
        $msg = "Phone number must start between 6-9 and must be 10 digits!";
    } else {

        $password = password_hash($pass, PASSWORD_DEFAULT);

        $check = mysqli_query($conn, "SELECT id FROM user WHERE email='$email'");
        if(mysqli_num_rows($check) > 0){
            $msg = "Email already registered!";
        } else {

            $insert = mysqli_query($conn,
                "INSERT INTO user (name,email,phone,password)
                 VALUES ('$name','$email','$phone','$password')"
            );

            if($insert){
                $_SESSION['user_id'] = mysqli_insert_id($conn);
                $_SESSION['user_name'] = $name;
                header("Location:user/udashboard.php");
                exit();
            } else {
                $msg = "Signup failed!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ambika Garment | User Signup</title>

    <style>
        *{
            box-sizing:border-box;
            font-family: 'Segoe UI', sans-serif;
        }
        body{
            margin:0;
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            background:linear-gradient(120deg,#1f4037,#99f2c8);
        }
        .card{
            width:420px;
            background:#fff;
            padding:30px;
            border-radius:15px;
            box-shadow:0 15px 40px rgba(0,0,0,.2);
        }
        h1{
            text-align:center;
            color:#243c8f;
            margin-bottom:5px;
        }
        .sub{
            text-align:center;
            color:#777;
            margin-bottom:25px;
        }
        input{
            width:100%;
            padding:12px;
            margin-bottom:15px;
            border:1px solid #ccc;
            border-radius:8px;
            font-size:15px;
        }
        button{
            width:100%;
            padding:12px;
            background:#243c8f;
            border:none;
            color:#fff;
            font-size:16px;
            border-radius:8px;
            cursor:pointer;
        }
        button:hover{
            background:#1a2f73;
        }
        .msg{
            text-align:center;
            margin-top:10px;
            color:red;
        }
        .login{
            text-align:center;
            margin-top:15px;
        }
        .login a{
            text-decoration:none;
            color:#243c8f;
            font-weight:600;
        }
    </style>
</head>

<body>

<div class="card">
    <h1>Ambika Garment</h1>
    <div class="sub">Cloth Factory Management System</div>

    <form method="POST">
        <input type="text" name="name" placeholder="Full Name" required>

        <input type="email" name="email" placeholder="Email Address" required>

        <input type="text" name="phone"placeholder="Mobile Number"
               maxlength="10" pattern="[0-9]{10}"title="Enter 10 digit mobile number"
               required>

        <input type="password" name="password" placeholder="Password" required>

        <button type="submit" name="signup">Sign Up</button>
    </form>

    <div class="msg"><?= $msg ?></div>

    <div class="login">
        Already have an account?
        <a href="index.php">Login</a>
    </div>
</div>

</body>
</html>
