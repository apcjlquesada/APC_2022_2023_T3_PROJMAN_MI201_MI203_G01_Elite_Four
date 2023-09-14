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

if(isset($_GET['admin_delete'])){
    $delete_id = $_GET['admin_delete'];
    if($delete_id == '18'){
        //$msg = "<div class='alert alert-danger'>You can't delete this account.</div>";
    }else {
        $delete_account= $conn->prepare("DELETE FROM `admins` WHERE admin_id = ?");
        $delete_account->execute([$delete_id]); 
        //$msg = "<div class='alert alert-success'>Successfully deleted.</div>";
    }  
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Customer Accounts</title>
    <!-- CSS LINK -->
    <link rel="stylesheet" href="../css/admin.css">
    <!-- FONT AWESOME LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
</head>
<body>

<!-- HEADER -->
<?php include '../components/admin_navigation.php'; ?>

<section class="accounts">
    <h1 class="title">Admin Accounts</h1>
    <a href="admin_register.php" class="btn-add">
        <i class="fa-solid fa-plus"></i>    Add Account</a> <!-- palitan yong class="btn" gawan ng ibang CSS. temporary placeholder lang yan then lagyan ng 3rem margin top -->
    
    <div class="table-display">
        <?php echo $msg; ?>
        <table class="table-display-table">
            <thead>
            <tr>
                <th>Profile Picture</th>
                <th>Complete Name</th>
                <th>Email Address</th>
                <th>Phone Number</th>
                <th>Account Created</th>
                <th>Action</th>
            </tr>
            </thead>
                <?php
                    $show_accounts = $conn->prepare("SELECT * FROM `admins`");
                    $show_accounts->execute();
                    if($show_accounts->rowCount() > 0){
                        while($fetch_accounts = $show_accounts->fetch(PDO::FETCH_ASSOC)){  
                ?>
            <tr>
            <td><img src="../profile/<?=$fetch_accounts['profile_picture']; ?>" style="max-width: 20rem;"></td>
                <td><?=$fetch_accounts['complete_name']; ?></td>
                <td class="email"><?=$fetch_accounts['email_address']; ?></td>
                <td><?=$fetch_accounts['phone_number']; ?></td>
                <td><?=$fetch_accounts['account_created']; ?></td>
                <td>
                <a href="admin_accounts-a.php?admin_delete=<?= $fetch_accounts['admin_id']; ?>" class="delete-btn"> delete </a>
                </td>
            </tr>
                <?php
                } }
                ?>
            </table>
    </div>
</section>

<!-- FOOTER -->
<?php include '../components/admin_footer.php'; ?>

<!-- JAVA SCRIPT -->
<script src="../js/admin.js"></script>

</body>
</html>