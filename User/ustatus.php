<?php  
session_start();
include "../db.php";

if(!isset($_SESSION['user_id'])){
    header("Location: ulogin.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];

$orders = mysqli_query($conn,"
SELECT 
    o.id,
    d.design_name,
    s.size_name,
    o.quantity,
    os.production_status,
    os.delivery_date
FROM orders o
LEFT JOIN designs d ON o.design_id = d.id
LEFT JOIN sizes s ON o.size_id = s.id
LEFT JOIN order_status os ON o.id = os.order_id
WHERE o.user_id = $user_id
AND os.production_status != 'Rejected'
ORDER BY o.id DESC
");

?>
<!DOCTYPE html>
<html>
<head>
<title>Order Status | Ambika Garment</title>

<style>
body{
    margin:0;
    font-family:Segoe UI;
    background:#f4f6fb;
}
.layout{
    display:flex;
    min-height:100vh;
}

.sidebar{
    width:200px;
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
.card{
    background:#fff;
    padding:25px;
    border-radius:12px;
    box-shadow:0 6px 20px rgba(0,0,0,.08);
}
table{
    width:100%;
    border-collapse:collapse;
}
th,td{
    padding:12px;
    border:1px solid #ddd;
    text-align:center;
}
th{
    background:#1c2b6f;
    color:#fff;
}
.status{
    font-weight:bold;
}
</style>
</head>

<body>

<div class="layout">

<div class="sidebar">
    <h2>Ambika Garment</h2>
    
    <a href="udashboard.php">🏠 Dashboard</a>
    <a href="udesign.php">🎨 Design</a>
    <a href="usize.php">📏 Size</a>
    <a href="uorders.php">🛒 Place Order</a>
    <a href="ustatus.php">📊 Track Order</a>
    <a href="ubill.php">🧾 Bill</a>
    <a href="ulogout.php">🔓 Logout</a>

</div>

<div class="main">
<div class="card">
<h2>My Order Status</h2>

<table>
<tr>
    <th>Order ID</th>
    <th>Design</th>
    <th>Size</th>
    <th>Qty</th>
    <th>Status</th>
    <th>Delivery Date</th>
</tr>

<?php
if(mysqli_num_rows($orders)==0){
    echo "<tr><td colspan='6'>No orders found</td></tr>";
}else{
    while($o=mysqli_fetch_assoc($orders)){
?>
<tr>
<td><?= $o['id'] ?></td>
<td><?= $o['design_name'] ?></td>
<td><?= $o['size_name'] ?></td>
<td><?= $o['quantity'] ?></td>
<td class="status"><?= $o['production_status'] ?? 'Pending' ?></td>
<td><?= $o['delivery_date'] ?? 'Not set' ?></td>
</tr>
<?php }} ?>
</table>

</div>
</div>

</div>
</body>
</html>
