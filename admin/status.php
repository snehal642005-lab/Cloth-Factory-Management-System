<?php
session_start();   
include "../db.php";

if(isset($_POST['update'])){

    $order_id = (int)$_POST['order_id'];
    $production_status = mysqli_real_escape_string($conn, $_POST['production_status']);
    $delivery_date = mysqli_real_escape_string($conn, $_POST['delivery_date']);

    $today = date("Y-m-d");
    if($delivery_date < $today){
        $_SESSION['error_msg'] = "Delivery date cannot be in the past!";
        header("Location: status.php");
        exit;
    }

    $check = mysqli_query($conn, "SELECT id FROM order_status WHERE order_id = $order_id");

    if(mysqli_num_rows($check) > 0){
        mysqli_query($conn, "
            UPDATE order_status SET
                production_status = '$production_status',
                delivery_date = '$delivery_date'
            WHERE order_id = $order_id
        ");
    } else {
        mysqli_query($conn, "
            INSERT INTO order_status
                (order_id, production_status, delivery_date)
            VALUES
                ($order_id, '$production_status', '$delivery_date')
        ");
    }

    $_SESSION['update_msg'] = $order_id;

    header("Location: status.php");
    exit;
}

$orders = mysqli_query($conn,"
SELECT 
    o.id,
    d.design_name,
    os.production_status,
    os.delivery_date
FROM orders o
LEFT JOIN designs d ON o.design_id=d.id
LEFT JOIN order_status os ON o.id=os.order_id
WHERE o.status='Accepted'
ORDER BY o.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Production Status</title>

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

select,input[type=date],button{
    padding:6px;
    border-radius:6px;
}
button{
    background:#1c2b6f;
    color:white;
    border:none;
    cursor:pointer;
}
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


<div class="main">

<div class="card">
<?php if(isset($_SESSION['error_msg'])){ ?>
    <p style="color:red; font-weight:bold;">
        <?= $_SESSION['error_msg']; ?>
    </p>
<?php unset($_SESSION['error_msg']); } ?>
<h2>Production Status</h2>
<table>
<tr>
<th>Order ID</th>
<th>Design</th>
<th>Status</th>
<th>Delivery Date</th>
<th>Update</th>
</tr>

<?php while($o=mysqli_fetch_assoc($orders)){ ?>
<tr>
<form method="post">
<td>
<?= $o['id'] ?>
<input type="hidden" name="order_id" value="<?= $o['id'] ?>">
</td>

<td><?= $o['design_name'] ?></td>

<td>
<select name="production_status">
<?php 
$st = $o['production_status'] ?? 'Pending';
$statuses = ["Pending","Cutting","Stitching","Iron","Packing","Ready","Delivered"];
foreach($statuses as $s){
    echo "<option ".($st==$s?"selected":"").">$s</option>";
}
?>
</select>
</td>

<td>
<input type="date" name="delivery_date" value="<?= $o['delivery_date'] ?>">
</td>

<td>
<button type="submit" name="update">Update</button>

<?php if(isset($_SESSION['update_msg']) && $_SESSION['update_msg'] == $o['id']){ ?>
    <span style="color:green; margin-left:8px;">Updated ✓</span>
<?php }
 ?>
</td>
</form>
</tr>
<?php } ?>
</table>
</div>
</div>
</body>
</html>
