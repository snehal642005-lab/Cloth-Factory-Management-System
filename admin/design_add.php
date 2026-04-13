<?php
include "../db.php";

$editData = null;

if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $res = mysqli_query($conn, "SELECT * FROM designs WHERE id = $edit_id");
    $editData = mysqli_fetch_assoc($res);
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
        $image = time().rand(1000,9999).".".$ext;
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/".$image);

        mysqli_query($conn,"UPDATE designs SET
            design_name='$design_name',
            design_id='$design_id',
            style='$style',
            price='$price',
            image='$image'
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
    $image = time().rand(1000,9999).".".$ext;

    move_uploaded_file($_FILES['image']['tmp_name'], "uploads/".$image);

    mysqli_query($conn,"INSERT INTO designs
    (design_name,design_id,style,image,price)
    VALUES
    ('$design_name','$design_id','$style','$image','$price')");

    
    header("Location: design.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Design</title>
<style>
body{font-family:Arial;background:#f4f6fb;padding:30px}


.card{ background:#fff;
    padding:25px;
    border-radius:12px;
    box-shadow:0 6px 20px rgba(0,0,0,.06);
    margin-bottom:20px;}
input,select,button{width:100%;padding:10px;margin:8px 0}
button{background:#1c2b6f;color:#fff;border:none}
</style>
</head>
<body>


<div class="card">
<h2>Add Design</h2>

<form method="post" enctype="multipart/form-data">
    <?php if($editData){ ?>
<input type="hidden" name="id" value="<?= $editData['id'] ?>">
<?php } ?>

<select name="design_name" onchange="toggleOther()" id="d" required>
<option value="">Select Design</option>

<?php
$designs = ["Formal","Casual","Party","Other"];
foreach($designs as $d){
    $sel = ($editData && $editData['design_name']==$d) ? "selected" : "";
    echo "<option $sel>$d</option>";
}
?>
</select>

<input type="text" name="other_design" id="o"
value="<?= $editData['design_name'] ?? '' ?>"
style="display:none" placeholder="Enter Design Name">

<input name="design_id" value="<?= $editData['design_id'] ?? '' ?>" placeholder="Enter Design Id" required>
<input name="style" value="<?= $editData['style'] ?? '' ?>" placeholder="Enter Style Name"required>
<input name="price" type="number" value="<?= $editData['price'] ?? '' ?>" placeholder="Enter price" required>

<input type="file" name="image" <?= $editData ? '' : 'required' ?>>
<button name="<?= $editData ? 'update_design' : 'add_design' ?>">
<?= $editData ? 'Update Design' : 'Save Design' ?>
</button>

</form>
</div>

<script>
function toggleOther(){
 let d=document.getElementById("d");
 let o=document.getElementById("o");
 if(d.value==="Other"){o.style.display="block";o.required=true;}
 else{o.style.display="none";o.required=false;o.value="";}
}
</script>

</body>
</html>

