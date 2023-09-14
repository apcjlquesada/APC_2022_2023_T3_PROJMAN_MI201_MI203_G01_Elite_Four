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
    $faq_title = $_POST['faq_title'];
    $faq_description = $_POST['faq_description'];
    $faq_status = $_POST['status'];
    $date_uploaded = $_POST['date_uploaded'];
    $update_faq = $conn->prepare("UPDATE `faqs` SET faq_title = ?, faq_description = ?, status = ?, date_uploaded = now() WHERE faq_id = ?");
    $update_faq->execute([$faq_title, $faq_description, $faq_status, $update_id]);
    header('location:admin_faqs.php'); 
    $_SESSION['msg'] =" <div class='alert-style'> <div class='alert alert-info'> FAQ has been updated.</div></div>"; 
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Frequently Asked Questions</title>
    <!-- CSS LINK -->
    <link rel="stylesheet" href="../css/admin.css">
    <!-- FONT AWESOME LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
</head>
<body>

<!-- HEADER -->
<?php include '../components/admin_navigation.php'; ?>

<section class="faq">
<a href="admin_faqs.php" class="back-btn">Back</a> 
   <div class="add-form-container">
      <form action="" method="post" enctype="multipart/form-data">
         <h1>Edit FAQ</h1>
         <?php echo $msg; ?>
            <?php
                $show_faq = $conn->prepare("SELECT * FROM `faqs` WHERE faq_id = '$update_id'");
                $show_faq->execute();
                if($show_faq->rowCount() > 0){
                    while($fetch_faq = $show_faq->fetch(PDO::FETCH_ASSOC)){  
            ?>
         <h2>FAQ Title<span>*</span></h2>
         <input type="text"  name="faq_title" class="box" maxlength='300' value="<?=$fetch_faq['faq_title']; ?>">
         <h2>FAQ Description<span>*</span></h2>
         <textarea name="faq_description" rows="4" column="10" class="box" maxlength='500'><?=$fetch_faq['faq_description']; ?></textarea>
         <input type="hidden" name="faq_id" value="<?= $fetch_faq['faq_id']; ?>">
         <h2>FAQ Status<span>*</span></h2>
                <select name="status" required>
                    <option value="SHOW">SHOW</option>
                    <option value="HIDE">HIDE</option>
                </select>
         <input type="hidden"  name="date_uploaded" class="box" maxlength='300' value="<?=$fetch_faq['date_uploaded']; ?>">
         <br><br>
         <input type="submit" class="btn" name="submit" value="Edit FAQ">
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