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
    $service_picture = $_FILES['service_picture']['name'];
    $service_picture_size = $_FILES['service_picture']['size'];
    $service_picture_tmp_name = $_FILES['service_picture']['tmp_name'];
    $service_picture_folder = '../services/' . $service_picture;
    $service_name = $_POST['service_name'];
    $service_description = $_POST['service_description'];
    $service_status = $_POST['status'];
    $date_uploaded = $_POST['date_uploaded'];
    $select_service = $conn->prepare("SELECT * FROM `services` WHERE service_picture = ?");
    $select_service->execute([$service_picture]);

    if (!empty($service_picture)) {
        if($service_picture_size > 2000000){
            $msg = "<div class='alert alert-danger'>Image is too large.</div>";
        }else{
            $update_profile = $conn->prepare("UPDATE `services` SET service_picture = ? WHERE service_id  = ?");
            $update_profile->execute([$service_picture, $update_id]);
            move_uploaded_file($service_picture_tmp_name, $service_picture_folder);
            $msg = "<div class='alert alert-success'>Service has been updated.</div>";
        }
    }

    $update_services = $conn->prepare("UPDATE `services` SET service_name = ?, service_description = ?, status = ?, date_uploaded = now() WHERE service_id = ?");
    $update_services->execute([$service_name, $service_description, $service_status, $update_id]);
    header('location:admin_services.php');
    $_SESSION['msg'] = "<div class='alert-style'><div class='alert alert-info'>Service has been updated</div></div>";

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Manage Services</title>
    <!-- CSS LINK -->
    <link rel="stylesheet" href="../css/admin.css">
    <!-- FONT AWESOME LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
</head>
<body>

<!-- HEADER -->
<?php include '../components/admin_navigation.php'; ?>
<section class="services">
<a href="admin_services.php" class="back-btn">
   <i class="fa-solid fa-circle-arrow-left"></i>    Back </a> 
     <div class="add-form-container">
      <form action="" method="post" enctype="multipart/form-data">
         <h1>Edit Services</h1>
         <?php echo $msg; ?>
            <?php
                $show_services = $conn->prepare("SELECT * FROM `services` WHERE service_id ='$update_id'");
                $show_services->execute();
                if($show_services->rowCount() > 0){
                    while($fetch_services = $show_services->fetch(PDO::FETCH_ASSOC)){  
            ?>
         <h2>Upload New Service Icon<span>*</span></h2>
         <input type="file" name="service_picture" class="box" accept="service_picture/jpg, service_picture/jpeg, service_picture/png, service_picture/webp">
         <input type="hidden" name="old_image" value="<?= $fetch_services['service_picture']; ?>">
         <h2>Service Title<span>*</span></h2>
         <input type="text"  name="service_name" class="box" maxlength='300' value="<?=$fetch_services['service_name']; ?>">
         <h2>Service Description<span>*</span></h2>
         <textarea name="service_description" rows="4" column="10" class="box" maxlength='500'><?=$fetch_services['service_description']; ?></textarea>
         <input type="hidden" name="service_id" value="<?= $fetch_services['service_id']; ?>">
         <h2>Service Status<span>*</span></h2>
                <select name="status" required>
                    <option value="AVAILABLE">AVAILABLE</option>
                    <option value="UNAVAILABLE">UNAVAILABLE</option>
                </select>
         <input type="hidden"  name="date_uploaded" class="box" maxlength='300' value="<?=$fetch_services['date_uploaded']; ?>">
         <br><br>
         <input type="submit" class="btn-add" name="submit" value="Add Service" style="width:100%;">
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