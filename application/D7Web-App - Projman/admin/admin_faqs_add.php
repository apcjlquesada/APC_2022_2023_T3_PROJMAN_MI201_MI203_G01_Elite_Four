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
    $faq_title = $_POST['faq_title'];
    $faq_description = $_POST['faq_description'];
    $select_faq = $conn->prepare("SELECT * FROM `faqs` WHERE faq_title = ?");
    $select_faq->execute([$faq_title]);
 
    if($select_faq->rowCount() > 0){
        $msg = "<div class='alert alert-danger'>FAQs already exist.</div>";
    }else{
        $insert_faq = $conn->prepare("INSERT INTO `faqs` (faq_title, faq_description) VALUES(?,?)");
        $insert_faq->execute([$faq_title, $faq_description]);
        header('location:admin_faqs.php');
        $_SESSION['msg'] = "<div class='alert-style'> <div class='alert alert-info'> FAQ successfully added. <i class='fa-solid fa-circle-check'></i></div></div>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Frequently Ask Questions</title>
    <!-- CSS LINK -->
    <link rel="stylesheet" href="../css/admin.css">
    <!-- FONT AWESOME LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
</head>
<body>

<!-- HEADER -->
<?php include '../components/admin_navigation.php'; ?>

<section class="faq">
       <a href="admin_faqs.php" class="back-btn">
   <i class="fa-solid fa-circle-arrow-left"></i>    Back </a> 
    <div class="add-form-container">
      <form action="" method="post" enctype="multipart/form-data">
         <h1>Add Frequently Asked Questions</h1>
         <?php echo $msg; ?>
         <h2>FAQ Title<span>*</span></h2>
         <input type="text"  name="faq_title" class="box" maxlength='300' value="<?php if (isset($_POST['submit'])) { echo $faq_title; } ?>" required>
         <h2>FAQ Description<span>*</span></h2>
         <textarea name="faq_description" rows="4" column="10"  class="box" maxlength='500' required><?php if (isset($_POST['submit'])) { echo $faq_description; } ?></textarea>
         <br><br>
         <input type="submit" class="btn-add" name="submit" value="Add FAQs"  style="width:100%;">
      </form>
   </div>
</section>

<!-- FOOTER -->
<?php include '../components/admin_footer.php'; ?>

<!-- JAVA SCRIPT -->
<script src="../js/admin.js"></script>

</body>
</html>