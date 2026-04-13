<?php
session_start();
include "../db.php";

if(!isset($_SESSION['user_id'])){
    header("Location: ulogin.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];

if(!isset($_SESSION['order_cart'])){
    $_SESSION['order_cart'] = [];
}

if(isset($_GET['design_id'], $_GET['size_id'])){
    $design_id = (int)$_GET['design_id'];
    $size_id   = (int)$_GET['size_id'];

    if($design_id > 0 && $size_id > 0){
        $key = $design_id."_".$size_id;

        if(isset($_SESSION['order_cart'][$key])){
            $_SESSION['order_cart'][$key]['qty']++;
        }else{
            $_SESSION['order_cart'][$key] = [
                'design_id'=>$design_id,
                'size_id'=>$size_id,
                'qty'=>1
            ];
        }
    }
    header("Location: uorders.php");
    exit;
}

if(isset($_POST['update_qty'])){
    $key = $_POST['key'];
    $qty = (int)$_POST['qty'];
    if($qty > 0 && isset($_SESSION['order_cart'][$key])){
        $_SESSION['order_cart'][$key]['qty'] = $qty;
        $_SESSION['update_msg'] = $key; 
    }
}


if(isset($_GET['remove'])){
    unset($_SESSION['order_cart'][$_GET['remove']]);
    header("Location: uorders.php");
    exit;
}

if(isset($_POST['place_order'])){
    if(empty($_SESSION['order_cart'])){
        echo "<script>alert('No items in cart');</script>";
        exit;
    }

    foreach($_SESSION['order_cart'] as $item){
        mysqli_query($conn,"
            INSERT INTO orders
            (user_id,design_id,size_id,quantity,status,order_date)
            VALUES
            ('$user_id','{$item['design_id']}','{$item['size_id']}','{$item['qty']}','Pending',CURDATE())
        ");
    }

    unset($_SESSION['order_cart']);
    echo "<script>alert('Order placed successfully');location.href='ustatus.php';</script>";
    exit;
}

$prev_orders = mysqli_query($conn,"
SELECT o.*,d.design_name,s.size_name
FROM orders o
LEFT JOIN designs d ON o.design_id=d.id
LEFT JOIN sizes s ON o.size_id=s.id
WHERE o.user_id=$user_id
ORDER BY o.id DESC
");
?>
<!DOCTYPE html>
<html>
<head>
<title>Place Order | Ambika Garment</title>

<style>
body{
    margin:0;
    font-family:Segoe UI;
    background:#f4f6f9;
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
    position: fixed;
    height: 100vh;
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
    margin-left:240px;   
}

table{
    width:100%;
    border-collapse:collapse;
    background:#fff;
}
th,td{
    padding:12px;
    border-bottom:1px solid #ddd;
    text-align:center;
}
th{
    background:#243c8f;
    color:#fff;
}
.qty{width:60px}
.btn{
    padding:8px 14px;
    border:none;
    border-radius:6px;
    cursor:pointer;
}
.btn-primary{
    background:#243c8f;
    color:#fff;
}
.btn-add{
    background:#198754;
    color:#fff;
    text-decoration:none;
    padding:10px 16px;
    border-radius:8px;
}
.cross{
    color:red;
    font-size:18px;
    text-decoration:none;
}
</style>
</head>

<body>

<div class="layout">

<!-- SIDEBAR -->
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

<!-- MAIN -->
<div class="main">
<h2>Your Order</h2>

<a href="udesign.php" class="btn-add">➕ Add More Items</a>
<br><br>

<table>
<tr>
    <th>Design</th>
    <th>Size</th>
    <th>Quantity</th>
    <th>Remove</th>
</tr>

<?php
if(empty($_SESSION['order_cart'])){
    echo "<tr><td colspan='4'>No items added</td></tr>";
}else{
    foreach($_SESSION['order_cart'] as $key=>$item){
        $d=mysqli_fetch_assoc(mysqli_query($conn,"SELECT design_name FROM designs WHERE id={$item['design_id']}"));
        $s=mysqli_fetch_assoc(mysqli_query($conn,"SELECT size_name FROM sizes WHERE id={$item['size_id']}"));
?>
<tr>
<td><?= $d['design_name'] ?></td>
<td><?= $s['size_name'] ?></td>
<td>
<form method="post">
<input type="hidden" name="key" value="<?= $key ?>">
<input type="number" name="qty" value="<?= $item['qty'] ?>" min="1" class="qty">
<button name="update_qty" class="btn btn-primary">Update</button>
<?php
if(isset($_SESSION['update_msg']) && $_SESSION['update_msg'] == $key){
    echo "<span style='color:green; margin-left:8px;'>Updated ✓</span>";
}
unset($_SESSION['update_msg']);
?>
</form>
</td>
<td><a href="?remove=<?= $key ?>" class="cross">❌</a></td>
</tr>
<?php }} ?>
</table>
<br>
<form method="post">
<button name="place_order" class="btn btn-primary">Place Order</button>
</form>

<h3>Previous Orders</h3>
<table>
<tr>
<th>Order ID</th>
<th>Design</th>
<th>Size</th>
<th>Qty</th>
<th>Status</th>
<th>Date</th>
</tr>

<?php
if(mysqli_num_rows($prev_orders)==0){
    echo "<tr><td colspan='6'>No previous orders</td></tr>";
}else{
    while($po=mysqli_fetch_assoc($prev_orders)){
?>
<tr>
<td><?= $po['id'] ?></td>
<td><?= $po['design_name'] ?></td>
<td><?= $po['size_name'] ?></td>
<td><?= $po['quantity'] ?></td>
<td><?= $po['status'] ?></td>
<td><?= $po['order_date'] ?></td>
</tr>
<?php }} ?>
</table>

</div>
</div>
</body>
</html>
