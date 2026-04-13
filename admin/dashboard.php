<?php 
include "../db.php";
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>

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
    background:linear-gradient(180deg,#1c2b6f,#121858);
    color:white;
    padding-top:20px;
}

.sidebar h2{
    text-align:center;
    margin-bottom:25px;
    font-weight:bold;
}

.menu{
    list-style:none;
    padding:0;
    margin:0;
}

/* Menu item */
.menu-item{
    margin:6px 12px;
}

/* Menu links & buttons */
.menu-item a,
.menu-btn{
    display:flex;
    align-items:center;
    gap:10px;
    width:100%;
    padding:12px 16px;
    color:white;
    text-decoration:none;
    font-size:15px;
    font-weight:500;
    border-radius:10px;
    cursor:pointer;
    transition:0.3s ease;
}


.menu-item a:hover,.menu-item .active{
    background:rgba(255,255,255,.18);
}
.menu-btn:hover{
    background:rgba(255,255,255,0.15);
    transform:translateX(6px);
}





/* ===== Main ===== */
.main{
    margin-left:240px;
    padding:30px;
}

.card{
    background:white;
    padding:30px;
    border-radius:12px;
    box-shadow:0 0 15px rgba(0,0,0,0.08);
}
</style>
</head>

<body>

<!-- ===== Sidebar ===== -->
<div class="sidebar">
    <h2>Ambika Grament</h2>

    <ul class="menu">

        <li class="menu-item">
            <div class="menu-btn" onclick="toggleDashboard()">🏠 Dashboard</div>
        </li>

        <li class="menu-item">
            <a href="design.php">🎨 Design</a>
        </li>
        <li class="menu-item">
            <a href="size.php">📏 Size</a>
        </li>

        

        <li class="menu-item">
            <a href="orders.php">🛒 Orders</a>
        </li>

        <li class="menu-item">
            <a href="status.php">📊 Status</a>
        </li>

        <li class="menu-item">
            <a href="bill.php">🧾 Bill</a>
        </li>

        <li class="menu-item ">
            <a href="logout.php"> 🔓 Logout</a>
        </li>

    </ul>
</div>

<!-- ===== Main Content ===== -->
<div class="main">
    <div class="card">
        <h2>Welcome Manohar Mahindrakar 👋</h2>
        <p>Select an option from the sidebar to manage designs, sizes, orders and more.</p>
    </div>
</div>

<script>
function toggleDashboard(){
    let menu = document.getElementById("dashboardMenu");
    if(menu.style.maxHeight){
        menu.style.maxHeight = null;
    }else{
        menu.style.maxHeight = "200px";
    }
}
</script>

</body>
</html>
