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

if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];
    $delete_service = $conn->prepare("DELETE FROM `support_tab` WHERE support_id = ? ");
    $delete_service->execute([$delete_id]);
    $msg = "
    <div class='alert-style'> 
         <div class='alert alert-danger'>
             Thread has been removed.
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

 if(isset($_POST['reply'])){
    $support_id = $_POST['support_id'];
    $thread_response = $_POST['thread_response'];
    $complete_name = $_POST['complete_name'];
    $profile_picture = $_POST['profile_picture'];
    $insert_thread = $conn->prepare("INSERT INTO `support_reply` (support_id, admin_id, profile_picture, complete_name, thread_response) VALUE (?,?,?,?,?)");
    $insert_thread->execute([$support_id, $admin_id, $profile_picture, $complete_name, $thread_response]);
    //
    header('location:admin_d7cares.php');
    $msg = "
    <div class='alert-style'> 
         <div class='alert alert-info'>
             Reply sent.
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
 
 $search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title> Manage D7 Community </title>
    <!-- CSS LINK -->
    <link rel="stylesheet" href="../css/admin.css">
    <!-- FONT AWESOME LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
</head>
<body>

<!-- HEADER -->
<?php include '../components/admin_navigation.php'; ?>

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
    function generatePageLink($i, $search_query) {
        $params = http_build_query([
            'page' => $i,
            'search_query' => $search_query
        ]);
        return '<li><a href="?' . $params . '">' . $i . '</a></li>';
    }
    for ($i = 1; $i <= $total_pages; $i++) {
        $page_links .= generatePageLink($i, $search_query);
    }
    ?>

<h1 class="title"> The D7 Community </h1>
<div class="search-container">
    <form action="admin_d7cares.php" method="GET" class="search" id="search-form" style="bottom: 14rem;" >
        <input id="search-input" class="search-box"type="text" name="search_query" value="<?php echo $search_query; ?>"  placeholder=" Search..."> 
        <button class="btn-search"type="submit"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
    </form>
    </div>
            <?php if ($show_all_thread->rowCount() > 0): ?>
        <div class="pagination" style="margin-top: 0;">
            <a href="?page=<?php echo $page-1; ?>&search_query=<?php echo $search_query; ?>" class="prev" <?php if($page == 1) echo 'style="display:none;"'; ?>>Prev</a>
            <ul class="pages">
                <?php echo $page_links; ?>
            </ul>
            <a href="?page=<?php echo $page+1; ?>&search_query=<?php echo $search_query; ?>" class="next" <?php if($page == $total_pages) echo 'style="display:none;"'; ?>>Next</a>
        </div> 
    <?php endif; ?>
      
<div class="box-container">  
      <?php
        
            if($show_all_thread->rowCount() > 0){
                while($fetch_all_thread = $show_all_thread->fetch(PDO::FETCH_ASSOC)){  
        ?>

    <div class="box">
        <img src="../profile/<?= $fetch_all_thread['profile_picture']; ?>" alt="">     
        <h3> <?= $fetch_all_thread['thread_title']; ?> </h3>
        <span style="position: relative; bottom:1rem; opacity: 0.6; font-size: 1.5rem;">
            <i> posted by <?= $fetch_all_thread['complete_name']?></i>
            <i> <?= $fetch_all_thread['date_posted'] ?> </i>
        </span>
        
        <div class="dropdown3">
            <button class="dropbtn3"><i class="fas fa-ellipsis-v"></i></button>
            <div class="dropdown-content3">
                <a href="#" class="reply-link" data-support-id="<?= $fetch_all_thread['support_id']; ?>">Reply</a>    
                <a href="admin_d7cares.php?support_id=<?= $fetch_all_thread['support_id']; ?>" class="see-link" data-support-id="<?= $fetch_all_thread['support_id']; ?>"> See Thread</a>
                    
                <a href="admin_d7cares.php?delete=<?=$fetch_all_thread['support_id']; ?>" onclick="return confirm('Are you sure to delete review?');">Delete</a> 
            </div>
        </div>
        <p><?= $fetch_all_thread['thread_description']; ?> </p>
    </div>
    <?php  } } ?>
</div> <br> <br> <br>
</section>

<!-- Reply Modal -->
<div id="ReplyModal" class="modal-admin">
<?php
   $show_thread = $conn->prepare("SELECT * FROM `support_tab`");
   $show_thread->execute();
   if($show_thread->rowCount() > 0){
     $fetch_thread = $show_thread->fetch(PDO::FETCH_ASSOC)
   ?>
   <div class="modal-content-admin">
      <span id="reply-close" class="close">&times;</span>   
      <form action="" method="post">
      <?php echo $msg; ?>
      <h1 style="margin: auto auto;"> Reply to customer</h1> 
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
<div id="SeeModal" class="modal-admin">
   <div class="modal-content-admin">
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
         <img class="profile_pic_admin" src="../profile/<?= $fetch_thread['profile_picture']; ?>" alt="">
          <h2 class="see-thread-title"><?= $fetch_thread['thread_title']; ?></h2> <br>
            <b class="see-thread-name"> <?= $fetch_thread['complete_name']?></b>
            <i class="see-thread-date"> <?= $fetch_thread['date_posted'] ?> </i> <br><br>
            <p class="see-thread-desc"><?= $fetch_thread['thread_description']; ?></p>
         </div>
         <?php } ?>
         
         <span class="comment"> Comments: </span>
         <div class="box-thread-reply">
         <?php
               $show_thread = $conn->prepare("SELECT * FROM `support_reply` WHERE support_id = " . $fetch_thread['support_id']);
               $show_thread->execute();
                  if($show_thread->rowCount() > 0){
                    while($fetch_reply = $show_thread->fetch(PDO::FETCH_ASSOC))   {                           
                  if( $fetch_reply['support_id'] == $fetch_thread['support_id'] ) {   
         ?>  <br>
         <img class="profile_pic_reply" src="../profile/<?= $fetch_reply['profile_picture']; ?>" alt=""> 
         <b class="see-thread-reply-name"><?= $fetch_reply['complete_name'];  ?>  </b> 
         <p class="see-thread-reply-datetime"> <?= $fetch_reply['date_posted'];  ?>  </p> 
         <div class="box-reply">
         <p class="see-thread-reply-desc"> <?= $fetch_reply['thread_response'];  ?>  </p>  
         </div>            
      <?php }}} ?>  
      </div>   
   </div>
</div>

<!-- FOOTER -->
<?php include '../components/admin_footer.php'; ?>

<!-- JAVA SCRIPT -->
<script src="../js/admin.js"></script>
<script src="https://code.jquery.com/jquery-3.6.3.slim.min.js" integrity="sha256-ZwqZIVdD3iXNyGHbSYdsmWP//UBokj2FHAxKuSBKDSo=" 
crossorigin="anonymous"></script>

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



</body>
</html>