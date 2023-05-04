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
    $car_model = $_POST['car_model'];
    $model_status = $_POST['status'];
    $date_uploaded = $_POST['date_uploaded'];
    $update_faq = $conn->prepare("UPDATE `car_models` SET car_model = ?, status  = ?, date_uploaded = now() WHERE car_model_id = ?");
    $update_faq->execute([$car_model, $model_status, $update_id]);
    header('location:admin_car_model.php'); 
    $_SESSION['msg'] =" <div class='alert-style'> <div class='alert alert-info'>Car model has been updated.</div></div>"; 
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

<section class="car_model">
<a href="admin_car_model.php" class="back-btn">
   <i class="fa-solid fa-circle-arrow-left"></i>    Back </a> 
    <div class="add-form-container">
      <form action="" method="post">
         <h1>Edit Model</h1>
         <?php echo $msg; ?>
            <?php
                $show_models = $conn->prepare("SELECT * FROM `car_models` WHERE car_model_id ='$update_id'");
                $show_models->execute();
                if($show_models->rowCount() > 0){
                    while($fetch_model = $show_models->fetch(PDO::FETCH_ASSOC)){  
            ?>
         <h2>Model<span>*</span></h2>
         <input type="text"  name="car_model" class="box" maxlength='300' value="<?=$fetch_model['car_model']; ?>">
         <input type="hidden" name="carl_model_id" value="<?= $fetch_model['car_model_id']; ?>">
         <h2>Model Status<span>*</span></h2>
                <select name="status" required>
                    <option value="AVAILABLE">AVAILABLE</option>
                    <option value="UNAVAILABLE">UNAVAILABLE</option>
                </select>
         <input type="hidden"  name="date_uploaded" class="box" maxlength='300' value="<?=$fetch_model['date_uploaded']; ?>">
         <br><br>
         <input type="submit" class="btn-add" name="submit" value="Add Car Model" style="width:100%;">
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