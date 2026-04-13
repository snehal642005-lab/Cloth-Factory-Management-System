<?php 
session_start();
include "../db.php";

if(!isset($_SESSION['user_id'])){
    header("Location: ulogin.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];


if(isset($_POST['pay_bill'])){
    $bill_id = (int)$_POST['bill_id'];

    mysqli_query($conn,"
        UPDATE bill 
        SET payment_status='Paid', payment_mode='Cash'
        WHERE id=$bill_id
    ");

    header("Location: ubill.php");
    exit;
}

$bills = mysqli_query($conn,"
SELECT 
    b.id AS bill_id,
    b.design_name,
    b.size,
    b.quantity,
    d.price,               
    b.total_amount,
    b.bill_date,
    b.payment_status
FROM bill b
INNER JOIN orders o ON b.order_id = o.id
INNER JOIN designs d ON o.design_id = d.id   
WHERE o.user_id = $user_id
ORDER BY b.id DESC
");
?>
<!DOCTYPE html>
<html>
<head>
<title>User | Bill</title>

<style>
body{
    margin:0;
    font-family:Segoe UI, Arial;
    background:#f4f6fb;
}
.layout{
    display:flex;
    min-height:100vh;
}

/* ===== SIDEBAR (AS YOU GAVE – NO CHANGE) ===== */
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
/* ===== MAIN ===== */
.main{
    flex:1;
    padding:30px;
}
.card{
    background:#fff;
    padding:25px;
    border-radius:12px;
    box-shadow:0 6px 15px rgba(0,0,0,.1);
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
.paid{color:green;font-weight:bold}
.unpaid{color:red;font-weight:bold}
button{
    padding:6px 14px;
    background:#198754;
    color:white;
    border:none;
    border-radius:6px;
    cursor:pointer;
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

<!-- MAIN CONTENT -->
<div class="main">
    <div class="card">

        <h2>🧾 My Bills (Cash Only)</h2>

        <table>
            <tr>
                <th>Bill ID</th>
                <th>Design</th>
                <th>Size</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php 
            if(mysqli_num_rows($bills)==0){
                echo "<tr><td colspan='7'>No bills found</td></tr>";
            }else{
                while($b=mysqli_fetch_assoc($bills)){
            ?>
            <tr>
                <td><?= $b['bill_id'] ?></td>
                <td><?= $b['design_name'] ?></td>
                <td><?= $b['size'] ?></td>
                <td><?= $b['quantity'] ?></td>
                <td>₹<?= $b['price'] ?></td>
                <td>₹<?= $b['total_amount'] ?></td>
                <td class="<?= $b['payment_status']=='Paid'?'paid':'unpaid' ?>">
                    <?= $b['payment_status'] ?>
                </td>
                <td>
                    <?php if($b['payment_status']!='Paid'){ ?>
                        <form method="post">
                            <input type="hidden" name="bill_id" value="<?= $b['bill_id'] ?>">
                            <button name="pay_bill">Pay (Cash)</button>
                        </form>
                    <?php } else { echo "—"; } ?>
                </td>
            </tr>
            <?php }} ?>
        </table>

    </div>
</div>

</div>
</body>
</html>
