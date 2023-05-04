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
    $car_model = $_POST['car_model'];
    $select_car_model = $conn->prepare("SELECT * FROM `car_models` WHERE car_model = ?");
    $select_car_model->execute([$car_model]);
 
    if($select_car_model->rowCount() > 0){
        $msg = "<div class='alert alert-danger'>Car type already exist.</div>";
    }else{
        $insert_service = $conn->prepare("INSERT INTO `car_models` (car_model) VALUES (?)");
        $insert_service->execute([$car_model]);
        $msg = "<div class='alert alert-success'>Car type successfully added.</div>";
    }
}
 
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Car Model</title>
    <!-- CSS LINK -->
    <link rel="stylesheet" href="../css/admin.css">
    <!-- FONT AWESOME LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
</head>
<body>

<!-- HEADER -->
<?php include '../components/admin_navigation.php'; ?>

<section class="car_model">
<a href="admin_car_model.php" class="back-btn">
   <i class="fa-solid fa-circle-arrow-left"></i>    Back </a> 
   <div class="add-form-container">
      <form action="" method="post" enctype="multipart/form-data">
         <h1>Add Car Model</h1>
         <?php echo $msg; ?>
         <h2>Car Model<span>*</span></h2>
         <input type="text"  name="car_model" class="box" maxlength='300' required>
         <br><br>
         <input type="submit" class="btn-add" name="submit" value="add car model" style="width:100%;">
      </form>
   </div>
</section>

<!-- FOOTER -->
<?php include '../components/admin_footer.php'; ?>

<!-- JAVA SCRIPT -->
<script src="../js/admin.js"></script>

</body>
</html>