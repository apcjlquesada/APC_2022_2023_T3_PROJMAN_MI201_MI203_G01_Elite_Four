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

if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];
    $delete_service = $conn->prepare("DELETE FROM `car_models` WHERE car_model_id = ? ");
    $delete_service->execute([$delete_id]);
    $msg = "
    <div class='alert-style'> 
         <div class='alert alert-info'>
             Car model has been removed.
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
<?php
// set default values
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';
$limit = 5;
$offset = ($page - 1) * $limit;

// build SQL query
$sql = "SELECT * FROM `car_models` WHERE CONCAT(`car_model`, `status`) LIKE :search_query ";
$sql .= "ORDER BY `date_uploaded` DESC LIMIT :limit OFFSET :offset";

$show_car_models = $conn->prepare($sql);
$show_car_models->bindValue(':search_query', "%$search_query%", PDO::PARAM_STR);
$show_car_models->bindValue(':limit', $limit, PDO::PARAM_INT);
$show_car_models->bindValue(':offset', $offset, PDO::PARAM_INT);

$show_car_models->execute();
$total_records = $conn->prepare("SELECT COUNT(*) FROM `car_models` WHERE CONCAT(`car_model`, `status`) LIKE :search_query");
$total_records->bindValue(':search_query', "%$search_query%", PDO::PARAM_STR);
$total_records->execute();

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
<h1 class="title">Add Car Model</h1>
<a href="admin_services.php" class="back-btn" style="top:-5em; right:30rem">
   <i class="fa-solid fa-circle-arrow-left"></i>    Back </a>  
   <a href="admin_car_model_add.php" class="btn-add" style="position:relative; right:0;">
        <i class="fa-solid fa-plus"></i>   Add Car Model</a>
    <div class="search-container" style="margin-bottom: 3rem; margin-left: -27rem; margin-top:8rem;">
    <form action="admin_car_model.php" method="GET" class="search" id="search-form" >
        <input id="search-input" class="search-box"type="text" name="search_query" value="<?php echo $search_query; ?>"  placeholder=" Search..."> 
        <button class="btn-search"type="submit"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
    </form>
    </div>

    <?php if ($show_car_models->rowCount() > 0): ?>
        <div class="pagination">
            <a href="?page=<?php echo $page-1; ?>&search_query=<?php echo $search_query; ?>" class="prev" <?php if($page == 1) echo 'style="display:none;"'; ?>>Prev</a>
            <ul class="pages">
                <?php echo $page_links; ?>
            </ul>
            <a href="?page=<?php echo $page+1; ?>&search_query=<?php echo $search_query; ?>" class="next" <?php if($page == $total_pages) echo 'style="display:none;"'; ?>>Next</a>
        </div> 
    <?php endif; ?>
   <div class="table-display">
    <?php echo $msg; ?>
      <table class="table-display-table">
        <thead>
        <tr>
            <th>Car Model</th>
            <th>Status</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
        </thead>
            <?php
            if($show_car_models->rowCount() > 0){
                while($fetch_car_models = $show_car_models->fetch(PDO::FETCH_ASSOC)){  
            ?>
        <tr>
            <td><?= $fetch_car_models['car_model']; ?></td>
            <td><?= $fetch_car_models['status']; ?></td>
            <td><?= $fetch_car_models['date_uploaded']; ?></td>
            <td style="width:150px;">
               <a href="admin_car_model_edit.php?update=<?= $fetch_car_models['car_model_id']; ?>" class="update-btn"> edit </a>
               <a href="admin_car_model.php?delete=<?= $fetch_car_models['car_model_id']; ?>" class="delete-btn"> delete </a>
            </td>
            <?php
                }
            }else{
                echo '<p class="empty">No Car Model Added Yet...</p>';
            }
            ?>
        </tr>
      </table>
    </div>
</section>

<!-- FOOTER -->
<?php include '../components/admin_footer.php'; ?>

<!-- JAVA SCRIPT -->
<script src="../js/admin.js"></script>

</body>
</html>