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
$search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>View Reservation</title>
    <!-- CSS LINK -->
    <link rel="stylesheet" href="../css/admin.css">
    <!-- FONT AWESOME LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
</head>
<body>

<!-- HEADER -->
<?php include '../components/admin_navigation.php'; ?>

<section class="reservations">

<?php
    // set default values
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';
    $limit = 5;
    $offset = ($page - 1) * $limit;

    // build SQL query
    $sql = "SELECT * FROM `reservations` WHERE CONCAT(`customer_name`, `service_type`, `car_model`, `customer_number`) LIKE :search_query AND `status` = 'COMPLETED' ";
    $sql .= "ORDER BY `date_updated` DESC LIMIT :limit OFFSET :offset";

    $show_reservation = $conn->prepare($sql);
    $show_reservation->bindValue(':search_query', "%$search_query%", PDO::PARAM_STR);
    $show_reservation->bindValue(':limit', $limit, PDO::PARAM_INT);
    $show_reservation->bindValue(':offset', $offset, PDO::PARAM_INT);

    $show_reservation->execute();
    $total_records = $conn->prepare("SELECT COUNT(*) FROM `reservations` WHERE CONCAT(`customer_name`, `service_type`, `car_model`, `customer_number`) LIKE :search_query AND `status` = 'COMPLETED' ");
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
<h1 class="title">Completed Table</h1>  
<div class="search-container">
    <form action="admin_reservations-d.php" method="GET" class="search" id="search-form" style="bottom: 14rem;" >
        <input id="search-input" class="search-box"type="text" name="search_query" value="<?php echo $search_query; ?>"  placeholder=" Search..."> 
        <button class="btn-search"type="submit"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
    </form>
    </div>
    <?php if ($show_reservation->rowCount() > 0): ?>
        <div class="pagination" style="margin-top: 0;">
            <a href="?page=<?php echo $page-1; ?>&search_query=<?php echo $search_query; ?>" class="prev" <?php if($page == 1) echo 'style="display:none;"'; ?>>Prev</a>
            <ul class="pages">
                <?php echo $page_links; ?>
            </ul>
            <a href="?page=<?php echo $page+1; ?>&search_query=<?php echo $search_query; ?>" class="next" <?php if($page == $total_pages) echo 'style="display:none;"'; ?>>Next</a>
        </div> 
    <?php endif; ?> <br> <br> <br> <br>
    <div class="reservations-display" style="margin-top:-5rem">
        <table class="reservations-display-table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Service Type</th>
                <th>Car Model</th>
                <th>Schedule</th>
                <th>Phone Number</th>
                <th>Email Address</th>
                <th>Date Placed
                <div class="dropdown">
                    <select onchange="sortTable()">
                        <option value="" disabled selected>Filter</option>
                        <option value="recent">Recent</option>
                        <option value="older">Older</option>
                    </select>
                </div>
                </th>
                <th>Date Updated</th>
                <th>Status</th>
            </tr>
            </thead>
            <?php
                if($show_reservation->rowCount() > 0){
                    while($fetch_reservation = $show_reservation->fetch(PDO::FETCH_ASSOC)){
                 ?>
            <tr>
            <form action="" method="POST">
                <td><input name="customer_name" value="<?= $fetch_reservation['customer_name']; ?>"></td>
                <td><input name="service_type" value="<?= $fetch_reservation['service_type']; ?>"></td>
                <td><input name="car_model'" value="<?= $fetch_reservation['car_model']; ?>"></td>
                <td><input name="schedule" value="<?= $fetch_reservation['schedule']; ?>"></td>
                <td><input name="customer_number" value="<?= $fetch_reservation['customer_number']; ?>"></td>
                <td class="email"><input name="customer_email" value="<?= $fetch_reservation['customer_email']; ?>"></td>
                <td><input name="date_placed" value="<?= $fetch_reservation['date_placed']; ?>"> </td>
                <td><input name="date_updated" value="<?= $fetch_reservation['date_updated']; ?>"> </td>
                <td><input name="status" value="<?= $fetch_reservation['status']; ?>"></td>
            </form>
            </tr>
                <?php
                    }
                }else{
                    echo '<tr><td colspan="9" style="width: 100%; font-size: 2rem; text-align: center; padding: 1rem; color: red;">No Completed Reservations Yet...</td></tr>';
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