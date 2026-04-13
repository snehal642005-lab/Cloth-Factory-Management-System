<?php 
include "../db.php";

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $img = mysqli_fetch_assoc(mysqli_query($conn,"SELECT image FROM designs WHERE id=$id"));
    if ($img && file_exists("uploads/".$img['image'])) {
        unlink("uploads/".$img['image']);
    }
    mysqli_query($conn,"DELETE FROM designs WHERE id=$id");
    header("Location: design.php");
    exit;
}

$edit = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $edit = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM designs WHERE id=$id"));
}

if (isset($_POST['add_design'])) {

    $design_name = ($_POST['design_name']=="Other")
        ? $_POST['other_design']
        : $_POST['design_name'];

    $design_id = $_POST['design_id'];
    $style = $_POST['style'];
    $price = $_POST['price'];

    if (!is_dir("uploads")) {
        mkdir("uploads",0777,true);
    }

    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $img = time().rand(1000,9999).".".$ext;
    move_uploaded_file($_FILES['image']['tmp_name'], "uploads/".$img);

    mysqli_query($conn,"INSERT INTO designs
    (design_name,design_id,style,image,price)
    VALUES
    ('$design_name','$design_id','$style','$img','$price')");

    header("Location: design.php");
    exit;
}

if (isset($_POST['update_design'])) {

    $id = $_POST['id'];
    $design_name = ($_POST['design_name']=="Other")
        ? $_POST['other_design']
        : $_POST['design_name'];

    $design_id = $_POST['design_id'];
    $style = $_POST['style'];
    $price = $_POST['price'];

    if (!empty($_FILES['image']['name'])) {

        $old = mysqli_fetch_assoc(mysqli_query($conn,"SELECT image FROM designs WHERE id=$id"));
        if ($old && file_exists("uploads/".$old['image'])) {
            unlink("uploads/".$old['image']);
        }

        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $img = time().rand(1000,9999).".".$ext;
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/".$img);

        mysqli_query($conn,"UPDATE designs SET
            design_name='$design_name',
            design_id='$design_id',
            style='$style',
            price='$price',
            image='$img'
            WHERE id=$id");

    } else {
        mysqli_query($conn,"UPDATE designs SET
            design_name='$design_name',
            design_id='$design_id',
            style='$style',
            price='$price'
            WHERE id=$id");
    }

    header("Location: design.php");
    exit;
}

$data = mysqli_query($conn,"SELECT * FROM designs ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Design Management</title>

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

.btn{
    padding:10px 18px;
    border-radius:8px;
    background:#1c2b6f;
    color:white;
    text-decoration:none;
    font-weight:bold;
}
.btn:hover{background:#16215a}

.card{
    background:#fff;
    padding:25px;
    border-radius:12px;
    box-shadow:0 6px 20px rgba(0,0,0,.06);
    margin-bottom:20px;
}

table{width:100%;border-collapse:collapse}
th,td{border:1px solid #ddd;padding:10px;text-align:center}
th{background:#1c2b6f;color:white}
img{width:65px;border-radius:6px}

.action-btn{
    padding:6px 10px;
    border-radius:6px;
    color:white;
    text-decoration:none;
}
.edit{background:#28a745}
.delete{background:#dc3545}
</style>
</head>

<body>

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

<a href="design_add.php" class="btn">➕ Add New Design</a>

<div class="card">
<h2>All Designs</h2>

<table>
<tr>
<th>ID</th><th>Name</th><th>Code</th><th>Style</th><th>Price</th><th>Image</th><th>Action</th>
</tr>

<?php while($r=mysqli_fetch_assoc($data)){ ?>
<tr>
<td><?= $r['id'] ?></td>
<td><?= $r['design_name'] ?></td>
<td><?= $r['design_id'] ?></td>
<td><?= $r['style'] ?></td>
<td>₹<?= $r['price'] ?></td>
<td><img src="uploads/<?= $r['image'] ?>"></td>
<td>
<a class="action-btn edit" href="design_add.php?edit=<?= $r['id'] ?>">Edit</a>
<a class="action-btn delete" href="?delete=<?= $r['id'] ?>" onclick="return confirm('Delete?')">Delete</a>
</td>
</tr>
<?php } ?>
</table>
</div>

</div>
</body>
</html>
