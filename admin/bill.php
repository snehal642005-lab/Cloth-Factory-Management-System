<?php 
include "../db.php";

if(isset($_POST['create_bill'])){

    $order_id  = intval($_POST['order_id']);
    $design    = mysqli_real_escape_string($conn,$_POST['design_name']);
    $size      = mysqli_real_escape_string($conn,$_POST['size']);
    $quantity  = intval($_POST['quantity']);
    $price     = floatval($_POST['price']);

    $total = ($price * $quantity);

    // fetch user_id from orders
    $getUser = mysqli_query($conn,"SELECT user_id FROM orders WHERE id=$order_id");
    $rowUser = mysqli_fetch_assoc($getUser);
    $user_id = $rowUser['user_id'];

    // prevent duplicate bill
    $check = mysqli_query($conn,"SELECT id FROM bill WHERE order_id=$order_id");

    if(mysqli_num_rows($check)==0){
        mysqli_query($conn,"
        INSERT INTO bill
        (order_id, user_id, design_name, size, quantity, price, total_amount,
         bill_date, payment_status, payment_mode)
        VALUES
        ($order_id, $user_id, '$design', '$size', $quantity, $price,
          $total,
         CURDATE(), 'Unpaid', 'Cash')
        ");
    }

    header("Location: bill.php");
    exit;
}

$orders = mysqli_query($conn,"
SELECT 
    o.id AS order_id,
    d.design_name,
    s.size_name,
    o.quantity,
    d.price
FROM orders o
LEFT JOIN designs d ON o.design_id = d.id
LEFT JOIN sizes s ON o.size_id = s.id
LEFT JOIN bill b ON o.id = b.order_id
WHERE o.status='Accepted' AND b.id IS NULL
ORDER BY o.id DESC
");

$bills = mysqli_query($conn,"SELECT * FROM bill ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin | Bill</title>

<style>
body{
    margin:0;
    font-family:Arial, Helvetica, sans-serif;
    background:#f4f6fb;
}
.sidebar{
    width:240px;
    height:100vh;
    position:fixed;
    left:0;top:0;
    background:linear-gradient(180deg,#1c2b6f,#121858);
    color:white;
    padding-top:20px;
}
.sidebar h2{
    text-align:center;
    margin-bottom:25px;
}
.menu{
    list-style:none;
    padding:0;
}
.menu li{
    margin:6px 12px;
}
.menu a{
    display:block;
    padding:12px 16px;
    color:white;
    text-decoration:none;
    border-radius:10px;
}
.menu a:hover,
.menu .active{
    background:rgba(255,255,255,.18);
}

.main{
    margin-left:240px;
    padding:30px;
}
.card{
    background:white;
    padding:25px;
    border-radius:12px;
    box-shadow:0 0 15px rgba(0,0,0,.08);
}
table{
    width:100%;
    border-collapse:collapse;
    margin-bottom:35px;
}
th,td{
    border:1px solid #ddd;
    padding:10px;
    text-align:center;
}
th{
    background:#1c2b6f;
    color:white;
}
input{
    width:80px;
    padding:4px;
}
button{
    padding:6px 14px;
    background:#1c2b6f;
    color:white;
    border:none;
    border-radius:6px;
    cursor:pointer;
}
.badge-paid{color:green;font-weight:bold}
.badge-unpaid{color:red;font-weight:bold}
</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h2>Ambika Garment</h2>
    <ul class="menu">
        <li><a href="dashboard.php">🏠 Dashboard</a></li>
        <li><a href="design.php">🎨 Design</a></li>
        <li><a href="size.php">📏 Size</a></li>
        <li><a href="orders.php">🛒 Orders</a></li>
        <li><a href="status.php">📊 Status</a></li>
        <li><a href="bill.php" class="active">🧾 Bill</a></li>
        <li><a href="logout.php">🔓 Logout</a></li>
    </ul>
</div>

<!-- MAIN -->
<div class="main">
<div class="card">

<h2>🧾 Generate Bill (Accepted Orders)</h2>

<table>
<tr>
<th>Order</th><th>Design</th><th>Size</th><th>Qty</th>
<th>Price</th><th>Action</th>
</tr>

<?php while($o=mysqli_fetch_assoc($orders)){ ?>
<tr>
<form method="post">
<td><?= $o['order_id'] ?></td>
<td><?= $o['design_name'] ?></td>
<td><?= $o['size_name'] ?></td>
<td><?= $o['quantity'] ?></td>
<td><?= $o['price'] ?></td>
<td>
<input type="hidden" name="order_id" value="<?= $o['order_id'] ?>">
<input type="hidden" name="design_name" value="<?= $o['design_name'] ?>">
<input type="hidden" name="size" value="<?= $o['size_name'] ?>">
<input type="hidden" name="quantity" value="<?= $o['quantity'] ?>">
<input type="hidden" name="price" value="<?= $o['price'] ?>">
<button name="create_bill">Create</button>
</td>
</form>
</tr>
<?php } ?>
</table>

<h2>💳 All Bills (Cash)</h2>

<table>
<tr>
<th>Bill ID</th>
<th>Order</th>
<th>Design</th>
<th>Total</th>
<th>Payment</th>
<th>Status</th>
</tr>

<?php while($b=mysqli_fetch_assoc($bills)){ ?>
<tr>
<td><?= $b['id'] ?></td>
<td><?= $b['order_id'] ?></td>
<td><?= $b['design_name'] ?></td>
<td>₹<?= $b['total_amount'] ?></td>
<td><?= $b['payment_mode'] ?></td>
<td>
<?= $b['payment_status']=='Paid'
    ? '<span class="badge-paid">Paid</span>'
    : '<span class="badge-unpaid">Unpaid</span>' ?>
</td>
</tr>
<?php } ?>
</table>

</div>
</div>

</body>
</html>
