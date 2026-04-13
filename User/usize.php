<?php
session_start();
include "../db.php";

if(!isset($_SESSION['user_id'])){
    header("Location: ulogin.php");
    exit();
}

if(!isset($_GET['design_id'])){
    header("Location: udesign.php");
    exit();
}

$design_id = (int)$_GET['design_id'];
$_SESSION['design_id'] = $design_id; // save for next steps
?>



<!DOCTYPE html>
<html>
<head>
<title>Select Size | Ambika Garment</title>

<style>
body{
    margin:0;
    font-family:Segoe UI, sans-serif;
    background:#f4f6f9;
}



.layout{ display:flex; min-height:100vh; }

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
.sidebar a:hover{
    background:rgba(255,255,255,.2);
}



.main{
    flex:1;
    padding:30px;
}
h2{
    margin-bottom:20px;
}


.grid{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:20px;
}


.card{
    background:#fff;
    border-radius:14px;
    box-shadow:0 8px 20px rgba(0,0,0,.1);
    padding:20px;
    text-align:center;
}
.size{
    font-size:26px;
    font-weight:bold;
    color:#0d6efd;
}
.details{
    margin:12px 0;
    font-size:14px;
    color:#444;
}
.details div{
    margin:4px 0;
}
.btn{
            display:inline-block;
            padding:10px 20px;
            background:#243c8f;
            color:#fff;
            text-decoration:none;
            border-radius:8px;
            transition:.3s;
        }
        .btn:hover{
            background:#1a2f6e;
        }

@media(max-width:900px){
    .grid{grid-template-columns:repeat(2,1fr);}
}
@media(max-width:600px){
    .grid{grid-template-columns:1fr;}
    .sidebar{display:none;}
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
        <h2>Select Your Size</h2>

        <div class="grid">

        <?php
        $q = mysqli_query($conn,"SELECT * FROM sizes ORDER BY id ASC");
        while($row = mysqli_fetch_assoc($q)){
        ?>
            <div class="card">
                <div class="size"><?php echo $row['size_name']; ?></div>

                <div class="details">
                    <div>Length: <?php echo $row['length']; ?> inch</div>
                    <div>Chest: <?php echo $row['chest']; ?> inch</div>
                    <div>Shoulder: <?php echo $row['shoulder']; ?> inch</div>
                    <div>Sleeve: <?php echo $row['sleeve']; ?> inch</div>
                </div>

                <form method="post" action="uorders.php">
                    <input type="hidden" name="size_id" value="<?php echo $row['id']; ?>">
                   <a href="uorders.php?design_id=<?= $design_id ?>&size_id=<?= $row['id'] ?>" class="btn">
                        Select Size
                    </a>
        </form>
            </div>
        <?php } ?>

        </div>
    </div>

</div>

</body>
</html>
