<?php 

include '../components/connect.php';
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

session_start();

$msg = "";


if(isset($_SESSION['msg'])) {
   echo $_SESSION['msg'];
   unset($_SESSION['msg']);
   echo "<script>
       setTimeout(function() {
           var element = document.querySelector('.alert-style');
           element.classList.add('hide');
           setTimeout(function() {
               element.parentNode.removeChild(element);
           }, 500);
       }, 2000);
   </script>";
}

if(isset($_SESSION['customer_id'])){
    $customer_id = $_SESSION['customer_id'] ;
}else{
    $customer_id = '';
}

if(isset($_POST['submit'])){
   if($customer_id == ''){
      $msg = '<div class="alert alert-danger alert-dismissible fade show" role="alert"> 
        Login to your account first. </div>';
   }else {
      $thread_title = $_POST['thread_title'];
      $thread_description = $_POST['thread_description'];
      $complete_name = $_POST['complete_name'];
      $profile_picture = $_POST['profile_picture'];
      $email_address = $_POST['email_address'];
      $admin_address = $_POST['admin_address'];
      $insert_thread = $conn->prepare("INSERT INTO `support_tab` (customer_id, profile_picture, complete_name, thread_title, thread_description) VALUE (?,?,?,?,?)");
      $insert_thread->execute([$customer_id, $profile_picture, $complete_name, $thread_title, $thread_description]);

      echo "<div style='display: none;'>";
      //Create an instance; passing `true` enables exceptions
      $mail = new PHPMailer(true);
      try {
          //Server settings       
          $mail->SMTPDebug = SMTP::DEBUG_SERVER; //Enable verbose debug output
          $mail->isSMTP(); //Send using SMTP
          $mail->Host = 'smtp.gmail.com'; //Set the SMTP server to send through
          $mail->SMTPAuth = true; //Enable SMTP authentication
          $mail->Username = 'dmc.mir4.1@gmail.com'; //SMTP username
          $mail->Password = 'mgafrzprnbgvleph'; //SMTP password
          $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
          $mail->Port = 465; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
          //Recipients
          $mail->setFrom('dmc.mir4.1@gmail.com');
          $mail->addAddress($admin_address);
          //Content
          $mail->isHTML(true); //Set email format to HTML
          $mail->Subject = 'Thread Notification';
          $mail->Body = 'Hi Admin! this is an email that serves as a notification that a new thread has been posted by ' .$complete_name;
          $mail->send();
          echo 'Message has been sent';
      } catch (Exception $e) {
          echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
      }
      echo "</div>";

      header('location:d7cares.php');
      $_SESSION['msg'] = "<div class='alert-style'> <div class='alert alert-success'>
      Thread posted.
      </div></div>"; 
   }
}

if(isset($_POST['edit'])){
   $thread_title = $_POST['thread_title'];
   $thread_description = $_POST['thread_description'];
   $date_posted = $_POST['date_posted'];  
   $get_support_id = $_POST['support_id'];
   $update_thread = $conn->prepare("UPDATE `support_tab` SET thread_title = ?, thread_description = ?, date_posted = now() WHERE support_id = ?");
   $update_thread->execute([$thread_title, $thread_description, $get_support_id ]);
   header('location:d7cares.php');
   $_SESSION['msg'] = "<div class='alert-style'> <div class='alert alert-success'>Post Updated.</div></div>";
}

if(isset($_POST['editreply'])){
   $thread_title = $_POST['thread_title'];
   $thread_description = $_POST['thread_description'];
   $date_posted = $_POST['date_posted'];  
   $support_id = $_POST['support_id'];  // Get support_id from form
   $update_thread = $conn->prepare("UPDATE `support_reply` SET thread_title = ?, thread_description = ?, date_posted = now() WHERE support_id = ?");
   $update_thread->execute([$thread_title, $thread_description, $support_id]);  // Use $support_id in query
   header('location:d7cares.php');
   $_SESSION['msg'] = "<div class='alert-style'> <div class='alert alert-success'>Reply Updated.</div></div>";
}

if(isset($_GET['delete'])){
   $get_id = $_GET['delete'];
   $delete_thread = $conn->prepare("DELETE FROM `support_tab` WHERE support_id = ? ");
   $delete_thread->execute([$get_id]);
   header('location:d7cares.php');
   $_SESSION['msg'] = "<div class='alert-style'> <div class='alert alert-danger'>Post Deleted.</div></div>";
} 


if(isset($_POST['reply'])){
   if($customer_id == ''){
     //  $msg = "<div class='alert alert-danger'> Login to your account </div>";
   }else {
      $support_id = $_POST['support_id'];
      $thread_response = $_POST['thread_response'];
      $complete_name = $_POST['complete_name'];
      $profile_picture = $_POST['profile_picture'];
      $insert_thread = $conn->prepare("INSERT INTO `support_reply` (support_id, customer_id, profile_picture, complete_name, thread_response) VALUE (?,?,?,?,?)");
      $insert_thread->execute([$support_id, $customer_id, $profile_picture, $complete_name, $thread_response]);
      header('location:d7cares.php');
      $_SESSION['msg'] = "<div class='alert-style'> <div class='alert alert-success'>Reply has been posted.<i class='fa-solid fa-circle-check'></i></div></div>";
   }
}
$search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title> D7 Community </title>
    <!-- CSS LINK -->
    <link rel="stylesheet" href="../css/customer.css">
    <!-- FONT AWESOME LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
</head>
<body>

<!-- HEADER -->
<?php include '../components/user_header.php'; ?>

<section class="d7cares">
<?php
    // set default values
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';
   $limit = 6;
    $offset = ($page - 1) * $limit;

    // build SQL query
    $sql = "SELECT * FROM `support_tab` WHERE CONCAT(`complete_name`, `thread_title`, `thread_description`) LIKE :search_query ";
    $sql .= "ORDER BY `date_posted` DESC LIMIT :limit OFFSET :offset";

    $show_all_thread = $conn->prepare($sql);
    $show_all_thread->bindValue(':search_query', "%$search_query%", PDO::PARAM_STR);
    $show_all_thread->bindValue(':limit', $limit, PDO::PARAM_INT);
    $show_all_thread->bindValue(':offset', $offset, PDO::PARAM_INT);
    
    $show_all_thread->execute();
    $total_records = $conn->prepare("SELECT COUNT(*) FROM `support_tab` WHERE CONCAT(`complete_name`, `thread_title`, `thread_description`) LIKE :search_query");
    $total_records->bindValue(':search_query', "%$search_query%", PDO::PARAM_STR);
    $show_all_thread->execute();

    $total_pages = ceil($total_records->fetchColumn() / $limit);
    $page_links = '';
        // generate page links
    function generatePageLink($i, $search_query, $status_filter) {
        $params = http_build_query([
            'page' => $i,
            'search_query' => $search_query,
            'status_filter' => $status_filter,
        ]);
        return '<li><a href="?' . $params . '">' . $i . '</a></li>';
    }
    for ($i = 1; $i <= $total_pages; $i++) {
        $page_links .= generatePageLink($i, $search_query, $status_filter);
    }
    ?>

   <h1 class="title"> The D7 Community </h1>
    <div class="d7cares-comm">
   <b> A gentle reminder: </b> <br> 
   To all our valued clients, we would like to remind you to please be mindful of your words in the D7 community.
   Creating questions in the forum that are answered by our specialists are monitored. Banning of accounts will
   be an act of reprimanding to whomever violates the D7 community guidelines.
   </div>


   <form action="d7cares.php" method="GET" class="search" id="search-form" style="bottom: 14rem;" >
        <input id="search-input" class="search-box"type="text" name="search_query" value="<?php echo $search_query; ?>"  placeholder=" Search..."> 
        <button class="btn-search"type="submit"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
    </form>
   <div class="pagination" style="margin-top: -5rem;">
        <a href="?page=<?php echo $page-1; ?>&search_query=<?php echo $search_query; ?>" class="prev" <?php if($page == 1) echo 'style="display:none;"'; ?>>Prev</a>
            <ul class="pages">
                <?php echo $page_links; ?>
            </ul>
        <a href="?page=<?php echo $page+1; ?>&search_query=<?php echo $search_query; ?>" class="next" <?php if($page == $total_pages) echo 'style="display:none;"'; ?>>Next</a>
    </div>

   <div class="box-container">    
                <?php
                     if($show_all_thread->rowCount() > 0){
                        while($fetch_all_thread = $show_all_thread->fetch(PDO::FETCH_ASSOC)){  
                 ?>
      <div class="box">
      <img src="../profile/<?= $fetch_all_thread['profile_picture']; ?>" alt="">     
         <h3> <?= $fetch_all_thread['thread_title']; ?> </h3>
         <span class="thread-view-namedate">
         <i> posted by <?= $fetch_all_thread['complete_name']?></i>
         <i> <?= $fetch_all_thread['date_posted'] ?> </i>
         </span>

         <?php  if($fetch_all_thread['customer_id'] == $customer_id) {  ?>
          <!-- Dropdown when replying to a thread  made by user --> 
         
          <div class="dropdown3">
            <button class="dropbtn3"><i class="fas fa-ellipsis-v"></i></button>
            <div class="dropdown-content3" >
            <a href="#" class="reply-link" data-support-id="<?= $fetch_all_thread['support_id']; ?>">Reply</a>
            <a href="d7cares.php?support_id=<?= $fetch_all_thread['support_id']; ?>" class="see-link" data-support-id="<?= $fetch_all_thread['support_id']; ?>">See Thread</a>
               <a href="d7cares.php?edit_support_id=<?= $fetch_all_thread['support_id']; ?>" class="edit-link" data-support-id="<?= $fetch_all_thread['support_id']; ?>">Edit</a>
               <a href="d7cares.php?delete=<?=$fetch_all_thread['support_id']; ?>" 
                onclick="return confirm('Are you sure to delete review?');"> Delete </a>
            </div>
         </div>
         <?php }else{ ?> 
            <!-- Dropdown when replying to a thread NOT made by user -->      
            <div class="dropdown3">
            <button class="dropbtn3"><i class="fas fa-ellipsis-v"></i></button>
            <div class="dropdown-content3" style="bottom:5rem">
            <a href="#" class="reply-link" data-support-id="<?= $fetch_all_thread['support_id']; ?>">Reply</a>
            <a href="d7cares.php?support_id=<?= $fetch_all_thread['support_id']; ?>" class="see-link" data-support-id="<?= $fetch_all_thread['support_id']; ?>"> See Thread</a>
            </div>
         </div>
         <?php } ?>
         
      <div class="desc-thread">
            <div class="d7-thread-desc"><?= $fetch_all_thread['thread_description']; ?></div>
       </div>
      
      </div>

      <?php  } } ?>
      
   </div>
   <a href="#" class="write-btn">
      <i class="fa-solid fa-pen"></i> 
   </a>

   <!-- Write Modal -->
   <div id="AddModal" class="modal-customer">
      <div class="modal-content-customer">
         <span id="add-close" class="close">&times;</span>
            <?php
               $select_admin = $conn->prepare("SELECT email_address FROM `admins`");
               $select_admin->execute();
               if($select_admin->rowCount() > 0){
                  $fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC);
               }
            ?>
         <form action="" method="post">
         <h1> Ask Us </h1>
         <input type="hidden" name="complete_name" class="box" maxlength="32" value="<?=$fetch_profile['complete_name']; ?>">  
         <input type="hidden" name="profile_picture" class="box" value="<?=$fetch_profile['profile_picture']; ?>" > 
         <input type="hidden" name="email_address" class="box" maxlength="32" value="<?=$fetch_profile['email_address']; ?>"> 
         <input type="hidden" name="admin_address" class="box" maxlength="32" value="<?=$fetch_admin['email_address']; ?>">
         <h2> What seems to be the problem? <span style="color:red;">*</span></h2> <span> (Limit: 50 characters) </span>
         <input type="text" name="thread_title" placeholder="Type your question here..." class="box" maxlength='50' style="padding:1rem;" required>
         <h2> Kindly provide description <span style="color:red;">*</span></h2>
         <textarea name="thread_description" rows="4" placeholder="Explain here..." column="10"  class="box" maxlength='500' style="padding:1rem;" required></textarea>  
         <input type="submit" value="Submit Question" name="submit" class="btn-submit" style="width: 100%">
      </form>
      </div>
   </div>

   <!-- Edit Modal  -->
   <div id="EditModal" class="modal-customer">
         <?php
            $get_support_id = isset($_GET['edit_support_id']) ? $_GET['edit_support_id'] : 0;
            $show_thread = $conn->prepare("SELECT * FROM `support_tab` WHERE support_id = " . $get_support_id);
            $show_thread->execute();
               if($show_thread->rowCount() > 0){
                  $fetch_thread = $show_thread->fetch(PDO::FETCH_ASSOC)
         ?>
      <div class="modal-content-customer"><span id="edit-close" class="close">&times;</span>
         <form action="" method="POST">
         <h1> Edit Post </h1>
         <input type="hidden" name="customer_name" class="box" maxlength="32" value="<?=$fetch_profile['complete_name']; ?>">  
         <input type="hidden" name="profile_picture" class="box" maxlength="32" value="<?=$fetch_profile['profile_picture']; ?>">  
         <input type="hidden" name="date_posted" class="box" maxlength="32" value="<?=$fetch_thread['date_posted']; ?>">
         <input id="support_id" type="hidden" name="support_id" class="box" maxlength="32" value="<?=$fetch_thread['support_id']; ?>">
         <h2> What seems to be the problem? <span style="color:red;">*</span></h2>
         <textarea  name="thread_title" rows="4" column="10"  class="box" maxlength='500' required> <?=$fetch_thread['thread_title']; ?></textarea> 
         <h2> Kindly provide description <span style="color:red;">*</span></h2>  
         <textarea  name="thread_description" rows="4" column="10"  class="box" maxlength='500' required> <?=$fetch_thread['thread_description']; ?></textarea>   
         <input type="submit" class="btn-add" name="edit" value="Edit Post" style="width:100%;">
      </form>
      </div>
      <?php } ?>
   </div>


      <!-- Edit Reply -->
      <div id="EditReply" class="modal-customer">
         <?php
            $get_support_id = isset($_GET['editreply_support_id']) ? $_GET['editreply_support_id'] : 0;
            $show_thread = $conn->prepare("SELECT * FROM `support_reply` WHERE support_id = " . $get_support_id);
            $show_thread->execute();
               if($show_thread->rowCount() > 0){
                  $fetch_thread = $show_thread->fetch(PDO::FETCH_ASSOC)
         ?>
      <div class="modal-content-customer"><span id="edit-close" class="close">&times;</span>
         <form action="" method="POST">
         <h1> Edit Reply </h1>
         <input type="hidden" name="customer_name" class="box" maxlength="32" value="<?=$fetch_profile['complete_name']; ?>">  
         <input type="hidden" name="profile_picture" class="box" maxlength="32" value="<?=$fetch_profile['profile_picture']; ?>">  
         <input type="hidden" name="date_posted" class="box" maxlength="32" value="<?=$fetch_thread['date_posted']; ?>">
         <input id="support_id" type="hidden" name="support_id" class="box" maxlength="32" value="<?=$fetch_thread['support_id']; ?>">
         <!-- reply -->
         <textarea  name="thread_description" rows="4" column="10"  class="box" maxlength='500' required> <?=$fetch_thread['thread_description']; ?></textarea>   
         <input type="submit" class="btn-add" name="editreply" value="Send Reply" style="width:100%;">
      </form>
      </div>
      <?php } ?>
   </div>




   <!-- Reply Modal -->
   <div id="ReplyModal" class="modal-customer">
   <?php
      $show_thread = $conn->prepare("SELECT * FROM `support_tab`");
      $show_thread->execute();
      if($show_thread->rowCount() > 0){
      $fetch_thread = $show_thread->fetch(PDO::FETCH_ASSOC)
      ?>
      <div class="modal-content-customer">
         <span id="reply-close" class="close">&times;</span>   
         <form action="" method="post">
         <?php echo $msg; ?>
      <h1 style="margin: auto auto;"> Reply to user</h1> 
         <input type="hidden" name="complete_name" class="box" maxlength="32" value="<?=$fetch_profile['complete_name']; ?>">  
         <input type="hidden" name="profile_picture" class="box" maxlength="32" value="<?=$fetch_profile['profile_picture']; ?>">
         <input id="support_id_reply" type="hidden" name="support_id" class="box" value="<?=$fetch_thread['support_id']; ?>" readonly required>
         <textarea name="thread_response" rows="4" placeholder="Type your reply here..."column="10"  class="box" maxlength='500' style="padding:1rem;" required></textarea>  
         <input type="submit" class="btn-add" name="reply" value="Send Reply" style="width:100%;">
      </form>  
      </div>
      <?php } ?>
   </div>

   <!-- See thread Modal -->
   <div id="SeeModal" class="modal-customer">
      <div class="modal-content-customer">
         <span id="see-close" class="close">&times;</span> 
            <h1> See Thread </h1>      
            <?php
                  $get_support_id = isset($_GET['support_id']) ? $_GET['support_id'] : 0;
                  $show_thread = $conn->prepare("SELECT * FROM `support_tab` WHERE support_id = " . $get_support_id);
                  $show_thread->execute();
                     if($show_thread->rowCount() > 0){
                     ($fetch_thread = $show_thread->fetch(PDO::FETCH_ASSOC))              
            ?>
            <div class="box-thread">
            <img class="profile_pic" src="../profile/<?= $fetch_thread['profile_picture']; ?>" alt="">
            <h2 class="see-thread-title"><?= $fetch_thread['thread_title']; ?></h2> <br>
            <b class="see-thread-name"> <?= $fetch_thread['complete_name']?></b>
            <i class="see-thread-date"> <?= $fetch_thread['date_posted'] ?> </i> <br><br>
            <p class="see-thread-desc"><?= $fetch_thread['thread_description']; ?></p>
        </div>
            <?php } ?>
            <span class="comment"> Comments: </span>

            <?php
            if (isset($fetch_thread['support_id']) && !empty($fetch_thread['support_id'])) {
               $show_thread = $conn->prepare("SELECT * FROM `support_reply` WHERE support_id = " . $fetch_thread['support_id']);
               $show_thread->execute();
               if ($show_thread->rowCount() > 0) {
                  while ($fetch_reply = $show_thread->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <div class="box-thread-reply">
                <img class="profile_pic_reply" src="../profile/<?= $fetch_reply['profile_picture']; ?>" alt="">
                <b class="see-thread-reply-name"><?= $fetch_reply['complete_name']; ?></b> <br>
                <p class="see-thread-reply-datetime"><?= $fetch_reply['date_posted']; ?></p>
                <?php if ($fetch_reply['customer_id'] == $customer_id) { ?>
                    <div class="dropdown3"style="position:relative;">
                        <button class="dropbtn3"><i class="fas fa-ellipsis-v"></i></button>
                        <div class="dropdown-content3" style=" position: absolute; bottom: 0; left: 80%;">

                        <a href="d7cares.php?editreply_support_id=<?= $fetch_reply['reply_id']; ?>"
                         class="editreply-link" data-reply-id="<?= $fetch_reply['reply_id']; ?>">Edit</a>

                            <a href="d7cares.php?delete_reply_id=<?= $fetch_reply['reply_id']; ?>"
                               onclick="return confirm('Are you sure to delete your comment?');">Delete</a>
                        </div>
                    </div>
                <?php } ?>
                <div class="box-reply">
                    <p class="see-thread-reply-desc"><?= $fetch_reply['thread_response']; ?></p>
                </div>
            </div>
            <?php
        }
    }
}
?>
 

      </div>
   </div>

</section>



<!-- FOOTER -->
<?php include '../components/user_footer.php'; ?>

<!-- JAVA SCRIPT -->
<script src="../js/customer.js"></script>
<script src="https://code.jquery.com/jquery-3.6.3.slim.min.js" integrity="sha256-ZwqZIVdD3iXNyGHbSYdsmWP//UBokj2FHAxKuSBKDSo=" crossorigin="anonymous"></script>

<script>
// Reply
$('.reply-link').click(function() {
   const dataSupportId = $(this).attr('data-support-id');
   console.log(dataSupportId);
   $("#support_id_reply").val(dataSupportId);
   const replyModal = document.getElementById('ReplyModal');
   replyModal.style.display = 'block';
   var replyClose = document.getElementById("reply-close");
   replyClose.onclick = function(){
      replyModal.style.display = "none";
   }
});
</script>


<script>
// See Thread
const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);
const support_id_param = urlParams.get('support_id');
if (support_id_param) {
   const seeModal = document.getElementById('SeeModal');
   seeModal.style.display = 'block';
}
var seeModal = document.getElementById("SeeModal")
var seeClose = document.getElementById("see-close");
seeClose.onclick = function(){
   seeModal.style.display = "none";
}
</script>

<script>
 // Edit Modal
const edit_support_id = urlParams.get('edit_support_id');
if (edit_support_id) {
   const seeModal = document.getElementById('EditModal');
   seeModal.style.display = 'block';
}
var editModal = document.getElementById("EditModal")
var editClose = document.getElementById("edit-close");

if (editClose) {
   editClose.onclick = function(){
      editModal.style.display = "none";
   }
}

</script>

<script>
 // Edit Reply
const editreply_support_id = urlParams.get('editreply_support_id');
if (editreply_support_id) {
   const seeModal = document.getElementById('EditReply');
   seeModal.style.display = 'block';
}
var editreplyModal = document.getElementById("EditReply")
var editreplyClose = document.getElementById("editreply-close");

if (editreplyClose) {
   editreplyClose.onclick = function(){
      editreplyModal.style.display = "none";
   }
}

</script>


<script>
// Add Modal
var addModal = document.getElementById("AddModal");
var write = document.querySelector(".write-btn"); 
var addClose = document.getElementById("add-close");

write.onclick = function() {
   addModal.style.display = "block";
}

addClose.onclick = function() {
   addModal.style.display = "none";
}

window.onclick = function(event) {
   if (event.target == addModal) {
      addModal.style.display = "none";
   }
}
</script>

<script>
   
</script>

</body>
</html>
