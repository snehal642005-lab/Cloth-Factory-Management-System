<?php  
session_start();
include "../db.php";

if(!isset($_SESSION['user_id'])){
    header("Location: ulogin.php");
    exit();
}

$user_id   = (int)$_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

$total_orders = 0;
$total_bills  = 0;


$oq = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total 
     FROM orders 
     WHERE user_id = $user_id"
);
if($oq){
    $total_orders = mysqli_fetch_assoc($oq)['total'];
}


$bq = mysqli_query(
    $conn,
    "SELECT COUNT(b.id) AS total
     FROM bill b
     INNER JOIN orders o ON b.order_id = o.id
     WHERE o.user_id = $user_id"
);
if($bq){
    $total_bills = mysqli_fetch_assoc($bq)['total'];
}
?>

<!DOCTYPE html>
<html>
<head>
<title>User Dashboard | Ambika Garment</title>

<style>
*{ box-sizing:border-box; font-family:Segoe UI, Arial; }
body{ margin:0; background:#f1f3f6; }
.layout{ display:flex; min-height:100vh; }

/* ===== SIMPLE SIDEBAR (AS YOU GAVE) ===== */
.sidebar{
    width:240px;
    background:#1c2b6f;
    color:#fff;
    padding:20px;
}
.sidebar h2{
    text-align:center;
    margin-bottom:20px;
}
.sidebar a{
    color:#fff;
    text-decoration:none;
    display:block;
    padding:10px;
    border-radius:6px;
    margin-bottom:6px;
}
.sidebar a:hover,.sidebar .active{
    background:rgba(255,255,255,.18);
}

.main{
    flex:1;
    padding:30px;
}
.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
}
.card{
    background:#fff;
    padding:25px;
    border-radius:12px;
    box-shadow:0 6px 15px rgba(0,0,0,.1);
    text-align:center;
}
.card h3{
    margin:0;
    font-size:16px;
    color:#64748b;
}
.card p{
    font-size:32px;
    margin-top:10px;
    color:#243c8f;
    font-weight:700;
}
.topbar{
    background:#fff;
    padding:15px 20px;
    border-radius:10px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 4px 10px rgba(0,0,0,.08);
    margin-bottom:25px;
}
</style>
</head>

<body>

<div class="layout">

<!-- SIDEBAR -->
<div class="sidebar">
    <h2>Ambika Garment</h2>
    <h4>mobile No:9890758596 Address:MICD Sunil Nager,Solapur</h4>
    <a href="udashboard.php">🏠 Dashboard</a>
    <a href="udesign.php">🎨 Design</a>
    <a href="usize.php">📏 Size</a>
    <a href="uorders.php">🛒 Place Order</a>
    <a href="ustatus.php">📊 Track Order</a>
    <a href="ubill.php">🧾 Bill</a>
    <a href="ulogout.php">🔓 Logout</a>

</div>

<!-- MAIN CONTENT -->
<div class="main">

    <div class="topbar">
        <div>Welcome, <strong><?= $user_name ?></strong></div>
        <div>Cloth Factory Management System</div>
    </div>

    <div class="cards">
        <div class="card">
            <h3>Total Orders</h3>
            <p><?= $total_orders ?></p>
        </div>

        <div class="card">
            <h3>Total Bills</h3>
            <p><?= $total_bills ?></p>
        </div>

        <div class="card">
            <h3>Order Status</h3>
            <p>Active</p>
        </div>
    </div>

</div>
</div>

</body>
</html>
