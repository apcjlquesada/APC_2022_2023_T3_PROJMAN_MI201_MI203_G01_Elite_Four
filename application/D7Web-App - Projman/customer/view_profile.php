<?php 

include '../components/connect.php';

session_start();

if(isset($_SESSION['customer_id'])){
    $customer_id = $_SESSION['customer_id'] ;
}else{
    $customer_id = '';
    header('location:login.php');
}

if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];
    $delete_account = $conn->prepare("DELETE FROM `customers` WHERE customer_id = ? ");
    $delete_account->execute([$delete_id]);
    header('location:login.php');
    $msg = "
    <div class='alert-style'> 
         <div class='alert alert-danger'>
             Account has been removed.
         </div>
    </div>

    <script>
        setTimeout(function() {
            var element = document.querySelector('.alert-style');
            element.classList.add('hide');
            setTimeout(function() {
                element.parentNode.removeChild(element);
            }, 500);
        }, 1500);
    </script>

    ";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Customer Profile</title>
    <!-- CSS LINK -->
    <link rel="stylesheet" href="../css/customer.css">
    <!-- FONT AWESOME LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
</head>
<body>

<!-- HEADER -->
<?php include '../components/user_header.php'; ?>

<!-- PROFILE -->
<section class="view-profile">
   <div class="user">
      <h1>Your Profile</h1>
      <img src="../profile/<?= $fetch_profile['profile_picture']; ?>" alt="">
      <h2>Customer Name </h2>
      <p><?= $fetch_profile['complete_name']; ?></p>
      <h2>Email Address </h2>
      <p><?= $fetch_profile['email_address']; ?></p>
      <h2>Phone Number </h2>
      <p><?= $fetch_profile['phone_number']; ?></p>
      <br>
      <a href="update_profile.php" class="btn">update info</a>
      <br><br>
      <a href="view_profile.php?delete=<?= $fetch_profile['customer_id']; ?>" 
            class="delete-btn" onclick="return confirm('Are you sure you want to delete your account?');">delete</a>
   </div>
</section>  

<!-- FOOTER -->
<?php include '../components/user_footer.php'; ?>

<!-- JAVA SCRIPT -->
<script src="../js/customer.js"></script>

</body>
</html>