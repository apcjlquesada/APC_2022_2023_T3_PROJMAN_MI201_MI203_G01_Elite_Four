<?php 

include '../components/connect.php';

session_start();

$msg = "";

if(isset($_SESSION['admin_id'])){
    $admin_id = $_SESSION['admin_id'] ;
}else{
    $admin_id = '';
    header('location:admin_reviews.php');  
}

if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];
    $delete_review= $conn->prepare("DELETE FROM `reviews` WHERE review_id = ?");
    $delete_review->execute([$delete_id]); 
    $msg = "
    <div class='alert-style'> 
         <div class='alert alert-info'>
             Review has been removed.
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
   <title> Manage Reviews</title>
    <!-- CSS LINK -->
    <link rel="stylesheet" href="../css/admin.css">
    <!-- FONT AWESOME LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
</head>
<body>

<!-- HEADER -->
<?php include '../components/admin_navigation.php'; ?>

<section class="reviews">
<?php
    // set default values
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';
    $limit = 5;
    $offset = ($page - 1) * $limit;

    // build SQL query
    $sql = "SELECT * FROM `reviews` WHERE CONCAT(`customer_name`, `description`, `rating`) LIKE :search_query ";
    $sql .= "ORDER BY `date_posted` DESC LIMIT :limit OFFSET :offset";

    $show_reviews = $conn->prepare($sql);
    $show_reviews->bindValue(':search_query', "%$search_query%", PDO::PARAM_STR);
    $show_reviews->bindValue(':limit', $limit, PDO::PARAM_INT);
    $show_reviews->bindValue(':offset', $offset, PDO::PARAM_INT);

    $show_reviews->execute();
    $total_records = $conn->prepare("SELECT COUNT(*) FROM `reviews` WHERE CONCAT(`customer_name`, `description`, `rating`) LIKE :search_query ");
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
    <h1 class="title"> Manage Reviews</h1>
    <div class="search-container">
    <form action="admin_reviews.php" method="GET" class="search" id="search-form" style="bottom: 14rem;" >
        <input id="search-input" class="search-box"type="text" name="search_query" value="<?php echo $search_query; ?>"  placeholder=" Search..."> 
        <button class="btn-search"type="submit"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
    </form>
    </div>
    <?php if ($show_reviews->rowCount() > 0): ?>
        <div class="pagination" style="margin-top: 0;">
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
                <th>Complete Name</th>
                <th>Rating</th>
                <th>Reviews</th>
                <th>Date Posted</th>
                <th>Action</th>
            </tr>
            </thead>
            <?php
                if ($show_reviews->rowCount() > 0) {
                    while ($fetch_reviews = $show_reviews->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <tr>
            <td><?=$fetch_reviews['customer_name']; ?></td>

                <td><?=$fetch_reviews['rating'];?>
                <?php  if ($fetch_reviews['rating'] == 1) { ?>
                            
                            <i class="fas fa-star" style="color:var(--yellow);"></i>
                            <i class="fas fa-star" ></i>
                            <i class="fas fa-star" ></i>
                            <i class="fas fa-star" ></i>
                            <i class="fas fa-star" ></i>
                        <?php  } ?>

                        <?php  if ($fetch_reviews['rating'] == 2) { ?>
                            
                            <i class="fas fa-star" style="color:var(--yellow);"></i>
                            <i class="fas fa-star" style="color:var(--yellow);"></i>
                            <i class="fas fa-star" ></i>
                            <i class="fas fa-star" ></i>
                            <i class="fas fa-star" ></i>
                        <?php  } ?>
                        <?php  if ($fetch_reviews['rating'] == 3) { ?>
                            
                            <i class="fas fa-star"style="color:var(--yellow);"></i>
                            <i class="fas fa-star" style="color:var(--yellow);"></i>
                            <i class="fas fa-star" style="color:var(--yellow);"></i>
                            <i class="fas fa-star" ></i>
                            <i class="fas fa-star" ></i>
                        <?php  } ?>
                        <?php  if ($fetch_reviews['rating'] == 4) { ?>
                            
                            <i class="fas fa-star"style="color:var(--yellow);"></i>
                            <i class="fas fa-star" style="color:var(--yellow);"></i>
                            <i class="fas fa-star" style="color:var(--yellow);"></i>
                            <i class="fas fa-star" style="color:var(--yellow);"></i>
                            <i class="fas fa-star" ></i>
                        <?php  } ?>

                        <?php  if ($fetch_reviews['rating'] == 5) { ?>
                            
                            <i class="fas fa-star"style="color:var(--yellow);"></i>
                            <i class="fas fa-star" style="color:var(--yellow);"></i>
                            <i class="fas fa-star" style="color:var(--yellow);"></i>
                            <i class="fas fa-star" style="color:var(--yellow);"></i>
                            <i class="fas fa-star" style="color:var(--yellow);"></i>
                        <?php  } ?>
                </td>
                <td><?=$fetch_reviews['description']; ?></td>
                <td><?=$fetch_reviews['date_posted']; ?></td>
                <td>
                <a href="admin_reviews.php?delete=<?= $fetch_reviews['review_id']; ?>" class="delete-btn"> delete </a>
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