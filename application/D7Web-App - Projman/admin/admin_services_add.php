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
    $service_picture = $_FILES['service_picture']['name'];
    $service_picture_size = $_FILES['service_picture']['size'];
    $service_picture_tmp_name = $_FILES['service_picture']['tmp_name'];
    $service_picture_folder = '../services/' . $service_picture;
    $service_name = $_POST['service_name'];
    $service_description = $_POST['service_description'];
    $select_service = $conn->prepare("SELECT * FROM `services` WHERE service_name = ?");
    $select_service->execute([$service_name]); 
    if($select_service->rowCount() > 0){
        $msg = "<div class='alert alert-danger'>Service already exists.</div>";
    }else{
        $select_picture = $conn->prepare("SELECT * FROM `services` WHERE service_picture = ?");
        $select_picture ->execute([$service_picture]);
        if($select_picture->rowCount() > 0) {
            $msg = "<div class='alert alert-danger'>Service icon aldready exist.</div>";
        }else{
            if($service_picture_size > 2000000){
                $msg = "<div class='alert alert-danger'>Image is too large.</div>";
            }else{
                move_uploaded_file($service_picture_tmp_name, $service_picture_folder);
                $insert_service = $conn->prepare("INSERT INTO `services` (service_picture, service_name, service_description) VALUES(?,?,?)");
                $insert_service->execute([$service_picture, $service_name, $service_description]);
                header('location:admin_services.php');
                $_SESSION['msg'] ="<div class='alert-style'> <div class='alert alert-info'> Service successfully added. <i class='fa-solid fa-circle-check'></i></div></div>";
            }
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
         <h1>Add Services</h1>
         <?php echo $msg; ?>
         <h2>Service Icon<span>*</span></h2>
        <input type="file" name="service_picture" class="box" accept="service_picture/jpg, service_picture/jpeg, service_picture/png, service_picture/webp" required>
         <h2>Service Name<span>*</span></h2>
         <input type="text"  name="service_name" class="box" maxlength='300'  value="<?php if (isset($_POST['submit'])) { echo $service_name; } ?>"  required>
         <h2>Service Description<span>*</span></h2>
         <textarea name="service_description" rows="4" column="10"  class="box" maxlength='500' required><?php if (isset($_POST['submit'])) { echo $service_description; } ?></textarea>
         <br><br>
         <input type="submit" class="btn-add"  name="submit" value="Add Services"  style="width:100%;">
      </form>
    </div>
</section>

<!-- FOOTER -->
<?php include '../components/admin_footer.php'; ?>

<!-- JAVA SCRIPT -->
<script src="../js/admin.js"></script>


</body>
</html>