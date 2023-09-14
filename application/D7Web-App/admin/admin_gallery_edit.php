<?php 

include '../components/connect.php';

session_start();

$msg = "";
$update_id = $_GET['update'];



if(isset($_SESSION['admin_id'])){
    $admin_id = $_SESSION['admin_id'] ;
}else{
    $admin_id = '';
    header('location:admin_login.php');  
}

if(isset($_POST['submit'])){
    $gallery_picture = $_FILES['gallery_picture']['name'];
    $gallery_picture_size = $_FILES['gallery_picture']['size'];
    $gallery_picture_tmp_name = $_FILES['gallery_picture']['tmp_name'];
    $gallery_picture_folder = '../gallery/' . $gallery_picture;
    $gallery_name = $_POST['gallery_picture_name'];
    $gallery_status = $_POST['status'];
    $date_uploaded = $_POST['date_uploaded'];
    $select_gallery = $conn->prepare("SELECT * FROM `gallery` WHERE gallery_picture = ?");
    $select_gallery->execute([$gallery_picture]);

    if (!empty($gallery_picture)) {
        if($gallery_picture_size > 5000000){
            $msg = "<div class='alert alert-danger'>Image size is too large. Maximum of 5MB only. </div>";
        }else{
            $update_image = $conn->prepare("UPDATE `gallery` SET gallery_picture = ? WHERE gallery_id  = ?");
            $update_image->execute([$gallery_picture, $update_id]);
            move_uploaded_file($gallery_picture_tmp_name, $gallery_picture_folder);
            $msg = "<div class='alert alert-success'>Image has been updated.</div>";
        }
    }

    $update_gallery = $conn->prepare("UPDATE `gallery` SET gallery_picture_name = ?, status = ?, date_uploaded = now() WHERE gallery_id = ?");
    $update_gallery->execute([$gallery_name, $gallery_status, $update_id]);
    header('location:admin_gallery.php');
    $_SESSION['msg'] =" <div class='alert-style'> <div class='alert alert-info'> Image has been updated.</div></div>";

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Manage Promos</title>
    <!-- CSS LINK -->
    <link rel="stylesheet" href="../css/admin.css">
    <!-- FONT AWESOME LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
</head>
<body>

<!-- HEADER -->
<?php include '../components/admin_navigation.php'; ?>

<section class="gallery">
<a href="admin_gallery.php" class="back-btn">
   <i class="fa-solid fa-circle-arrow-left"></i>    Back </a> 
    <div class="add-form-container">
      <form action="" method="post" enctype="multipart/form-data">
         <h1>Edit Gallery</h1>
         <?php echo $msg; ?>
            <?php
                $show_gallery = $conn->prepare("SELECT * FROM `gallery` WHERE gallery_id ='$update_id'");
                $show_gallery->execute();
                if($show_gallery->rowCount() > 0){
                    while($fetch_gallery = $show_gallery->fetch(PDO::FETCH_ASSOC)){  
            ?>
         <h2>Upload New gallery Icon<span>*</span></h2>
         <input type="file" name="gallery_picture" class="box" accept="gallery_picture/jpg, gallery_picture/jpeg, gallery_picture/png, gallery_picture/webp">
         <input type="hidden" name="old_image" value="<?= $fetch_gallery['gallery_picture']; ?>">
         <h2>gallery Title<span>*</span></h2>
         <input type="text"  name="gallery_picture_name" class="box" maxlength='300' value="<?=$fetch_gallery['gallery_picture_name']; ?>">
         <input type="hidden" name="gallery_id" value="<?= $fetch_gallery['gallery_id']; ?>">
         <h2>gallery Status<span>*</span></h2>
                <select name="status" required>
                    <option value="SHOW">SHOW</option>
                    <option value="HIDE">HIDE</option>
                </select>
         <input type="hidden"  name="date_uploaded" class="box" maxlength='300' value="<?=$fetch_gallery['date_uploaded']; ?>">
         <br><br>
         <input type="submit" class="btn-add" name="submit" value="Add Image" style="width:100%;">
      </form>
            <?php
                }
            }
            ?>
   </div>
</section>

<!-- FOOTER -->
<?php include '../components/admin_footer.php'; ?>

<!-- JAVA SCRIPT -->
<script src="../js/admin.js"></script>

</body>
</html>