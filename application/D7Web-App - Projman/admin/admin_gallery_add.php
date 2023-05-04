<?php 

include '../components/connect.php';

session_start();

$msg = "";

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
    $gallery_picture_name = $_POST['gallery_picture_name'];
    $select_gallery_picture = $conn->prepare("SELECT * FROM `gallery` WHERE gallery_picture_name = ?");
    $select_gallery_picture->execute([$gallery_picture_name]);
 
    if($select_gallery_picture->rowCount() > 0){
        $msg = "<div class='alert alert-danger'>Image aldready exist.</div>";
    }else{
        if($gallery_picture_size > 20000000){
            $msg = "<div class='alert alert-danger'>Image size is too large.</div>";
        }else{
            move_uploaded_file($gallery_picture_tmp_name, $gallery_picture_folder);
            $insert_gallery_picture = $conn->prepare("INSERT INTO `gallery` (gallery_picture, gallery_picture_name) VALUES(?,?)");
            $insert_gallery_picture->execute([$gallery_picture, $gallery_picture_name]);
            $msg = "<div class='alert alert-success'>gallery has been successfully added.</div>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Manage Gallery</title>
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
            <h1>ADD GALLERY</h1>
            <?php echo $msg; ?>
            <h2>Upload Image<span>*</span></h2>
            <input type="file" name="gallery_picture" class="box" accept="gallery_picture/jpg, gallery_picture/jpeg, gallery_picture/png, gallery_picture/webp" required>
            <h2>Picture Name<span>*</span></h2>
            <input type="text"  name="gallery_picture_name" class="box" maxlength='300'>
            <br><br>
            <input type="submit" class="btn-add"  name="submit" value="Add Gallery"  style="width:100%;" >
        </form>
    </div>
</section>

<!-- FOOTER -->
<?php include '../components/admin_footer.php'; ?>

<!-- JAVA SCRIPT -->
<script src="../js/admin.js"></script>

</body>
</html>