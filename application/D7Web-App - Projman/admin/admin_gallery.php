<?php 

include '../components/connect.php';

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

if(isset($_SESSION['admin_id'])){
    $admin_id = $_SESSION['admin_id'] ;
}else{
    $admin_id = '';
    header('location:admin_login.php');  
}

if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];
    $delete_gallery_picture = $conn->prepare("DELETE FROM `gallery` WHERE gallery_id = ? ");
    $delete_gallery_picture->execute([$delete_id]);
    $msg = "
    <div class='alert-style'> 
         <div class='alert alert-info'>
             Image has been removed.
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
   <title>Manage Gallery</title>
    <!-- CSS LINK -->
    <link rel="stylesheet" href="../css/admin.css">
    <!-- FONT AWESOME LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">

</head>
<body>

<!-- HEADER -->
<?php include '../components/admin_navigation.php'; ?>

<section class="gallery">
<?php
    // set default values
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';
    $limit = 5;
    $offset = ($page - 1) * $limit;

    // build SQL query
    $sql = "SELECT * FROM `gallery` WHERE CONCAT(`gallery_picture_name`, `status`) LIKE :search_query ";
    $sql .= "ORDER BY `date_uploaded` DESC LIMIT :limit OFFSET :offset";

    $show_gallery = $conn->prepare($sql);
    $show_gallery->bindValue(':search_query', "%$search_query%", PDO::PARAM_STR);
    $show_gallery->bindValue(':limit', $limit, PDO::PARAM_INT);
    $show_gallery->bindValue(':offset', $offset, PDO::PARAM_INT);

    $show_gallery->execute();
    $total_records = $conn->prepare("SELECT COUNT(*) FROM `gallery` WHERE  CONCAT(`gallery_picture_name`, `status`) LIKE :search_query ");
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
    <h1 class="title">Manage Gallery</h1>
    <a href="admin_gallery_add.php" class="btn-add">  <i class="fa-solid fa-plus"></i>  Upload Image </a> 
    <div class="search-container" style="margin-top: 8rem; margin-left: -27rem;">
    <form action="admin_gallery.php" method="GET" class="search" id="search-form" style="bottom: 14rem;" >
        <input id="search-input" class="search-box"type="text" name="search_query" value="<?php echo $search_query; ?>"  placeholder=" Search..."> 
        <button class="btn-search"type="submit"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
    </form>
    </div>
    <?php if ($show_gallery->rowCount() > 0): ?>
        <div class="pagination" >
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
                <th>Image</th>
                <th>Image Name</th>
                <th>Status</th>
                <th>Date Uploaded</th>
                <th>Action</th>
            </tr>
            </thead>
                <?php
                    
                    if($show_gallery->rowCount() > 0){
                        while($fetch_gallery = $show_gallery->fetch(PDO::FETCH_ASSOC)){  
                ?>
            <tr>
                <td><img src="../gallery/<?=$fetch_gallery['gallery_picture']; ?>" style="max-width: 20rem;"></td>
                <td><?=$fetch_gallery['gallery_picture_name']; ?></td>
                <td><?=$fetch_gallery['status']; ?></td>
                <td><?=$fetch_gallery['date_uploaded']; ?></td>
                <td style="width:150px;">
                <a href="admin_gallery_edit.php?update=<?= $fetch_gallery['gallery_id']; ?>" class="update-btn"> edit </a>
                <a href="admin_gallery.php?delete=<?= $fetch_gallery['gallery_id']; ?>" class="delete-btn"> delete </a>
                </td>
            </tr>
                <?php
                }
                }else{
                    echo '<p class="empty">No gallery added yet...</p>';
                }
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