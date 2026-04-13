<?php  
include "../db.php";

if(isset($_POST['add_sizes'])){
    $size_name = $_POST['size_name'];
    $length    = $_POST['length'];
    $chest     = $_POST['chest'];
    $shoulder  = $_POST['shoulder'];
    $sleeve    = $_POST['sleeve'];

    mysqli_query($conn,"
        INSERT INTO sizes (size_name,length,chest,shoulder,sleeve)
        VALUES ('$size_name','$length','$chest','$shoulder','$sleeve')
    ");
}

$sizeData = [];
$result = mysqli_query($conn,"SELECT * FROM sizes ORDER BY id DESC");
while($row = mysqli_fetch_assoc($result)){
    $sizeData[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Size Management</title>

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

.sidebar h2{
    text-align:center;
    margin-bottom:25px;
    font-weight:bold;
    font-size: H2;
}

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

.container{
    display:flex;
    gap:30px;
}

.card{
    background:#fff;
    padding:25px;
    border-radius:12px;
    box-shadow:0 0 10px rgba(0,0,0,0.1);
    width:50%;
}

input,select,button{
    width:100%;
    padding:10px;
    margin:8px 0;
    border-radius:6px;
    border:1px solid #ccc;
}

button{
    background:#1c2b6f;
    color:white;
    border:none;
    font-size:15px;
    cursor:pointer;
}
button:hover{background:#16215a}

</style>
</head>

<body>

<!-- ===== SIDEBAR ===== -->
<div class="sidebar">
   <h2>Ambika Grament</h2>
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

<div class="container">


<div class="card">
<h2>➕ Add Size</h2>

<form method="post">
<select name="size_name" required>
    <option value="">Select Size</option>
    <option>S</option>
    <option>M</option>
    <option>L</option>
    <option>XL</option>
    <option>XXL</option>
</select>

<input name="length" placeholder="Length (inch)" required>
<input name="chest" placeholder="Chest (inch)" required>
<input name="shoulder" placeholder="Shoulder (inch)" required>
<input name="sleeve" placeholder="Sleeve (inch)" required>

<button name="add_sizes">Add Size</button>
</form>
</div>

<!-- ===== VIEW SIZE ===== -->
<div class="card">
<h2>👕 View Size Details</h2>

<select onchange="fillSize(this.value)">
<option value="">-- Select Size --</option>
<?php foreach($sizeData as $s){ ?>
<option value="<?= $s['id'] ?>"><?= $s['size_name'] ?></option>
<?php } ?>
</select>

<input id="length_val" placeholder="Length" readonly>
<input id="chest" placeholder="Chest" readonly>
<input id="shoulder" placeholder="Shoulder" readonly>
<input id="sleeve" placeholder="Sleeve" readonly>
</div>

</div>
</div>

<script>
let sizes = <?= json_encode($sizeData) ?>;

function fillSize(id){
    let s = sizes.find(x => x.id == id);
    if(s){
        length_val.value = s.length;
        chest.value = s.chest;
        shoulder.value = s.shoulder;
        sleeve.value = s.sleeve;
    }else{
        length_val.value = chest.value = shoulder.value = sleeve.value = "";
    }
}
</script>

</body>
</html>
