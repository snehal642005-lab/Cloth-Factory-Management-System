<?php 
include "../db.php";

if(isset($_GET['id']) && isset($_GET['status'])){
    $id = (int)$_GET['id'];
    $status = $_GET['status'];

    if($status=="Accepted" || $status=="Rejected"){
        mysqli_query($conn,"UPDATE orders SET status='$status' WHERE id=$id");
        header("Location: orders.php");
        exit;
    }
}

$orders = mysqli_query($conn,"
SELECT o.*, d.design_name, d.design_id AS design_code
FROM orders o
LEFT JOIN designs d ON o.design_id = d.id
ORDER BY o.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Order Management</title>

<style>
body{
    margin:0;
    font-family:Arial;
    background:#f4f6fb;
}

.sidebar{
    width:240px;
    height:100vh;
    position:fixed;
    background:linear-gradient(180deg,#1c2b6f,#121858);
    color:white;
    padding-top:20px;
}
.sidebar h2{text-align:center;margin-bottom:25px}
.menu{list-style:none;padding:0}
.menu-item{margin:6px 12px}
.menu-item a{
    display:block;
    padding:12px 16px;
    color:white;
    text-decoration:none;
    border-radius:10px;
}
.menu-item a:hover ,.menu-item .active{
    background:rgba(255,255,255,.18);
    transform:translateX(6px);
}

.main{
    margin-left:240px;
    padding:30px;
}

.card{
    background:white;
    padding:25px;
    border-radius:12px;
    box-shadow:0 6px 20px rgba(0,0,0,0.06);
}

table{width:100%;border-collapse:collapse}
th,td{border:1px solid #ddd;padding:10px;text-align:center}
th{background:#1c2b6f;color:white}

.badge{
    padding:6px 14px;
    border-radius:20px;
    font-weight:bold;
}
.Pending{background:#ffc107}
.Accepted{background:#28a745;color:white}
.Rejected{background:#dc3545;color:white}

.btn{
    padding:6px 12px;
    color:white;
    border-radius:6px;
    text-decoration:none;
    font-size:13px;
}
.accept{background:#28a745}
.accept:hover{background:#218838}
.reject{background:#dc3545}
.reject:hover{background:#c82333}
</style>
</head>

<body>

<!-- ===== SIDEBAR ===== -->
<div class="sidebar">
    <h2>Ambika Garment</h2>
    <ul class="menu">
        <li class="menu-item"><a href="dashboard.php">🏠 Dashboard</a></li>
        <li class="menu-item"><a href="design.php">🎨 Design</a></li>
        <li class="menu-item"><a href="size.php">📏 Size</a></li>
        <li class="menu-item"><a href="orders.php">🛒 Orders</a></li>
        <li class="menu-item"><a href="status.php">📊 Status</a></li>
        <li class="menu-item"><a href="bill.php">🧾 Bill</a></li>
        <li class="menu-item"><a href="logout.php">🔓 Logout</a></li>
    </ul>
</div>

<!-- ===== MAIN ===== -->
<div class="main">

<div class="card">
<h2>Orders</h2>

<table>
<tr>
<th>ID</th>
<th>Customer</th>
<th>Design</th>
<th>Qty</th>
<th>Date</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php while($o=mysqli_fetch_assoc($orders)){ ?>
<tr>
<td><?= $o['id'] ?></td>
<td><?= $o['user_id'] ?></td>
<td><?= $o['design_name'] ?><br><small><?= $o['design_code'] ?></small></td>
<td><?= $o['quantity'] ?></td>
<td><?= $o['order_date'] ?></td>

<td>
<span class="badge <?= $o['status'] ?>">
<?= $o['status'] ?>
</span>
</td>

<td>
<?php if($o['status']=="Pending"){ ?>
    <a class="btn accept"
       href="orders.php?id=<?= $o['id'] ?>&status=Accepted">Accept</a>

    <a class="btn reject"
       href="orders.php?id=<?= $o['id'] ?>&status=Rejected"
       onclick="return confirm('Reject this order?')">Reject</a>
<?php } else { ?>
    <?= $o['status'] ?>
<?php } ?>
</td>
</tr>
<?php } ?>

</table>
</div>

</div>
</body>
</html>
