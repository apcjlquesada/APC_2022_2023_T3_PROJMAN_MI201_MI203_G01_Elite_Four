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

if(isset($_GET['user_delete'])){
    $delete_id = $_GET['user_delete'];
    $delete_account= $conn->prepare("DELETE FROM `customers` WHERE customer_id = ?");
    $delete_account->execute([$delete_id]); 
    $msg = "<div class='alert-style'> <div class='alert alert-danger'>
             Account removed.</div></div>

    <script>
        setTimeout(function() {
            var element = document.querySelector('.alert-style');
            element.classList.add('hide');
            setTimeout(function() {
                element.parentNode.removeChild(element);
            }, 500);
        }, 1500);
    </script>";
}
$search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';

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
<?php
// set default values
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';
$limit = 5;
$offset = ($page - 1) * $limit;

// build SQL query
$sql = "SELECT * FROM `customers` WHERE CONCAT(`complete_name`, `email_address`, `phone_number`) LIKE :search_query ";
$sql .= "ORDER BY `account_created` DESC LIMIT :limit OFFSET :offset";

$show_accounts = $conn->prepare($sql);
$show_accounts->bindValue(':search_query', "%$search_query%", PDO::PARAM_STR);
$show_accounts->bindValue(':limit', $limit, PDO::PARAM_INT);
$show_accounts->bindValue(':offset', $offset, PDO::PARAM_INT);

$show_accounts->execute();
$total_records = $conn->prepare("SELECT COUNT(*) FROM `customers` WHERE CONCAT(`complete_name`, `email_address`, `phone_number`) LIKE :search_query");
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
    <h1 class="title">Customer Accounts</h1>
    <div class="search-container">
    <form action="admin_accounts-c.php" method="GET" class="search" id="search-form" style="bottom: 14rem;" >
        <input id="search-input" class="search-box"type="text" name="search_query" value="<?php echo $search_query; ?>"  placeholder=" Search..."> 
        <button class="btn-search"type="submit"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
    </form>
    </div>
    <?php if ($show_accounts->rowCount() > 0): ?>
        <div class="pagination" style="margin-top: -5rem;">
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
                <th>Profile Picture</th>
                <th>Complete Name</th>
                <th>Email Address</th>
                <th>Phone Number</th>
                <th>Account Created</th>
                <th>Action</th>
            </tr>
            </thead>
                <?php
                    if($show_accounts->rowCount() > 0){
                        while($fetch_accounts = $show_accounts->fetch(PDO::FETCH_ASSOC)){  
                ?>
                <tr>
                <td><img src="../profile/<?=$fetch_accounts['profile_picture']; ?>" style="max-width: 10rem; text-align: center;" ></td>
                <td><?=$fetch_accounts['complete_name']; ?></td>
                <td class="email"><?=$fetch_accounts['email_address']; ?></td>
                <td><?=$fetch_accounts['phone_number']; ?></td>
                <td><?=$fetch_accounts['account_created']; ?></td>
                <td>
                <a href="admin_accounts-c.php?user_delete=<?= $fetch_accounts['customer_id']; ?>" class="delete-btn"> delete </a>
                </td>
            </tr>
                <?php
                }
                }else{
                    echo '<p class="empty">No Customers Account Yet...</p>';
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