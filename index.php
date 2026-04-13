<?php
session_start();
include "db.php";

$msg = "";
if (isset($_POST['login'])) {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass  = $_POST['password'];

    $admin_q = mysqli_query($conn,
        "SELECT * FROM admin WHERE email_id='$email' LIMIT 1"
    );

    if(mysqli_num_rows($admin_q) == 1){

        $admin = mysqli_fetch_assoc($admin_q);

        if($pass == $admin['password']){
            $_SESSION['admin_id']   = $admin['id'];
            $_SESSION['admin_name'] = $admin['username'];

            header("Location: admin/dashboard.php");
            exit;
        }
    }
    $user_q = mysqli_query($conn,
        "SELECT * FROM user WHERE email='$email' LIMIT 1"
    );

    if(mysqli_num_rows($user_q) == 1){

        $user = mysqli_fetch_assoc($user_q);

        if(password_verify($pass, $user['password'])){

            $_SESSION['user_id']    = $user['id'];
            $_SESSION['user_name']  = $user['name'];
            $_SESSION['user_email'] = $user['email'];

            header("Location: user/udashboard.php");
            exit;
        }
    }

    $msg = "Invalid Email or Password";
}

?>


<!DOCTYPE html>
<html>
<head>
    <title>Ambika Garment </title>

    <style>
        *{
            box-sizing:border-box;
            font-family:'Segoe UI', sans-serif;
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
        .signup{
            text-align:center;
            margin-top:15px;
        }
        .signup a{
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
        <input type="text" name="text" placeholder="Enter Name" required>
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Password" required>

        <button type="submit" name="login">Login</button>
    </form>

    <div class="msg"><?= $msg ?></div>

    <div class="signup">
        Don’t have an account?
        <a href="signup.php">Create Account</a>
    </div>
</div>

</body>
</html>
