<?php
session_start();
include "../db.php";

if(!isset($_SESSION['user_id'])){
    header("Location: ulogin.php");
    exit();
}


$designs = mysqli_query($conn, "SELECT * FROM designs");
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Design | Ambika Garment</title>

    <style>
        *{
            box-sizing:border-box;
            font-family:'Segoe UI', sans-serif;
        }
        body{
            margin:0;
            background:#f4f6f9;
        }

       
        .layout{ display:flex; min-height:100vh; }

        
.sidebar{
    position: fixed;        
    top: 0;
    left: 0;
    width:240px;
    height:100vh;           
    background:#1c2b6f;
    color:#fff;
    padding:20px;
    box-sizing: border-box;

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

        
        .content{
            flex:1;
            padding:30px;
            margin-left:240px;   /* Same width as sidebar */
        
        }

        .topbar{
            background:#fff;
            padding:15px 20px;
            border-radius:12px;
            box-shadow:0 5px 15px rgba(0,0,0,.08);
            margin-bottom:25px;
            display:flex;
            justify-content:space-between;
            align-items:center;
        }

            .grid{
                display:grid;
                grid-template-columns:repeat(3, 1fr); /* 3 per row */
                gap:25px;
            }


        .card{
            background:#fff;
            border-radius:15px;
            overflow:hidden;
            box-shadow:0 8px 20px rgba(0,0,0,.1);
            transition:.3s;
        }
        .card:hover{
            transform:translateY(-6px);
        }

        .card img{
            width:100%;
            height:220px;          /* perfect card image height */
            object-fit:contain;    /* SHOW FULL IMAGE */
            background:#f8f8f8;    /* clean background */
            padding:10px;
            }


        .card-body{
            padding:18px;
            text-align:center;
        }

        .card-body h3{
            margin:0;
            color:#243c8f;
            font-size:18px;
        }

        .card-body p{
            margin:6px 0;
            color:#555;
            font-size:14px;
        }

        .price{
            font-size:20px;
            font-weight:bold;
            color:#000;
            margin:10px 0;
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
    <!-- CONTENT -->
    <div class="content">

        <div class="topbar">
            <div>Select Your Design</div>
            <div>Cloth Factory Management System</div>
        </div>

        <div class="grid">

            <?php if(mysqli_num_rows($designs) > 0){ ?>
                <?php while($row = mysqli_fetch_assoc($designs)){ ?>

                    <div class="card">
                        <img src="../admin/uploads/<?= htmlspecialchars($row['image']) ?>"
                             onerror="this.src='../admin/uploads/no-image.png';"
                             alt="Design">

                        <div class="card-body">
                            <h3><?= htmlspecialchars($row['design_name']) ?></h3>
                            <p>Style: <?= htmlspecialchars($row['style']) ?></p>
                            <div class="price">₹ <?= htmlspecialchars($row['price']) ?></div>

                       <a href="usize.php?design_id=<?= $row['id'] ?>" class="btn">Select Design</a>


                        </div>
                    </div>

                <?php } ?>
            <?php } else { ?>
                <p>No designs available</p>
            <?php } ?>

        </div>

    </div>

</div>

</body>
</html>
