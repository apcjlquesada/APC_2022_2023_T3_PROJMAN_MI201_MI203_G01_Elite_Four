<?php 

include '../components/connect.php';

session_start();

if(isset($_SESSION['admin_id'])){
    $admin_id = $_SESSION['admin_id'] ;
}else{
    $admin_id = '';
     header('location:admin_login.php');  
}

if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];
    $delete_account = $conn->prepare("DELETE FROM `admins` WHERE admin_id = ? ");
    $delete_account->execute([$delete_id]);
    header('location:admin_login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Profile</title>
    <!-- CSS LINK -->
    <link rel="stylesheet" href="../css/admin.css">
    <!-- FONT AWESOME LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
</head>
<body>

<!-- HEADER -->
<?php include '../components/admin_navigation.php'; ?>

<section class="view-profile">
   <div class="user">
      <h1>Your Profile</h1>
      <img src="../profile/<?= $fetch_profile['profile_picture']; ?>" alt="">
      <h2>Admin Name </h2>
      <p><?= $fetch_profile['complete_name']; ?></p>
      <h2>Email Address </h2>
      <p><?= $fetch_profile['email_address']; ?></p>
      <h2>Phone Number </h2>
      <p><?= $fetch_profile['phone_number']; ?></p>
      <br>
      <a href="admin_update_profile.php" class="btn">update info</a>
      <br> <br>
      <a href="admin_view_profile.php?delete=<?= $fetch_profile['admin_id']; ?>" 
            class="delete-btn" onclick="return confirm('Are you sure you want to delete your account?');">delete</a>
   </div>
</section>

<!-- FOOTER -->
<?php include '../components/admin_footer.php'; ?>

<!-- JAVA SCRIPT -->
<script src="../js/admin.js"></script>

</body>
</html>