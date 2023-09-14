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

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard</title>
    <!-- CSS LINK -->
    <link rel="stylesheet" href="../css/admin.css">
    <!-- FONT AWESOME LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
</head>
<body>

<!-- HEADER -->
<?php include '../components/admin_navigation.php'; ?>
    <div class="as-of-date">
        As of <?php $current_date = date('F j, Y'); echo $current_date;?>
    </div> <br>
<section class="statistics">
   
    <div class="stats-box-container">
    <div class="summary-container">
        <div class="summary">
        <div class="summary-title"> Total Customer Accounts </div>
            <?php
                    $select_account = $conn->prepare("SELECT COUNT(*) as total_accounts FROM customers");
                    $select_account->execute();
                    $fetch_account = $select_account->fetch(PDO::FETCH_ASSOC);
                    $total_accounts = $fetch_account['total_accounts'];
                        
                ?>
        <div class="summary-result"> <?php echo $total_accounts ?> </div>
        </div>

        <div class="summary">
        <div class="summary-title"> Total Customer Reviews </div>
            <?php
                $select_reviews = $conn->prepare("SELECT COUNT(*) AS total_reviews FROM reviews");
                $select_reviews->execute();
                $fetch_reviews = $select_reviews->fetch(PDO::FETCH_ASSOC);
                $total_reviews  = $fetch_reviews['total_reviews'];
            ?>
        <div class="summary-result"> <?php echo $total_reviews ?> </div>
        </div>

        <div class="summary">
        <div class="summary-title"> Total Completed Reservations </div>
            <?php
                $select_reservation = $conn->prepare("SELECT COUNT(*) AS total_completed FROM reservations WHERE status = 'COMPLETED'");
                $select_reservation->execute();
                $fetch_reservation = $select_reservation->fetch(PDO::FETCH_ASSOC);
                $total_completed = $fetch_reservation['total_completed'];
            ?>
        <div class="summary-result"> <?php echo $total_completed ?> </div>
        </div>

        <div class="summary">
        <div class="summary-title"> Total Cancelled Reservations</div>
            <?php
                $select_reservation = $conn->prepare("SELECT COUNT(*) AS total_cancelled FROM reservations WHERE status = 'CANCELLED'");
                $select_reservation->execute();
                $fetch_reservation = $select_reservation->fetch(PDO::FETCH_ASSOC);
                $total_cancelled = $fetch_reservation['total_cancelled'];
            ?>
        <div class="summary-result"> <?php echo $total_cancelled ?> </div>
        </div>

    </div>

    <br>


<h1 class="title">Rendered Services Details </h1> <br>
<div style="display: flex;">
    <div class="chart" style="margin-right: 20px">
        <canvas id="acquiredChart"></canvas>
        <?php
            $default_month = date('m');
            if(isset($_POST['selected_month'])) {
                $selected_month = date('m', strtotime($_POST['selected_month']));
            } else {
                $selected_month = $default_month;
            }
            
            $show_reservation = $conn->prepare("SELECT MONTH(date_updated) AS month_updated, service_type, COUNT(*) AS count_completed 
                FROM reservations WHERE status = 'COMPLETED' AND MONTH(date_updated) = :selected_month GROUP BY MONTH(date_updated), service_type 
                ORDER BY month_updated ASC, count_completed DESC");
            $month_updated = array(); 
            $service_type = array();
            $count_completed = array();
            $show_reservation->bindParam(':selected_month', $selected_month);
            $show_reservation->execute();
            if($show_reservation->rowCount() > 0){
                while($fetch_reservation = $show_reservation->fetch(PDO::FETCH_ASSOC)) {
                    $month_updated[] = $fetch_reservation['month_updated'];
                    $service_type[] = $fetch_reservation['service_type'];
                    $count_completed[] = $fetch_reservation['count_completed'];
                }
            }
        ?>
        <form method="POST" >
            <h1><input name="selected_month" id="start5" type="month" min="2023-02" max="<?php echo date('Y-m'); ?>" value="<?php echo isset($_POST['selected_month']) ? $_POST['selected_month'] : date('Y-m'); ?>"> </h1>
            <button class="btn-filter" type="submit">Filter</button>
        </form>
       
        <form method="post" action="generate_csv.php">
            <input hidden name="selected_month" id="start5" type="month" min="2023-02" max="<?php echo date('Y-m'); ?>" value="<?php echo isset($_POST['selected_month']) ? $_POST['selected_month'] : date('Y-m'); ?>">
            <button class="download" type="submit">Generate CSV</button>
        </form>

        </div>

        <div style="display: block; margin: 3rem; width:50%">
            <?php
                // Define start and end dates for most and least rendered services
                $start_date = date('Y-' . $selected_month . '-01');
                $end_date = date('Y-' . $selected_month . '-t');
                //Get most rendered service
                $most_rendered_service = $conn->prepare("SELECT service_type, COUNT(*) as rendered_count FROM reservations WHERE date_updated BETWEEN :start_date AND :end_date 
                    AND status = 'COMPLETED' GROUP BY service_type ORDER BY rendered_count DESC LIMIT 1");
                $most_rendered_service->bindParam(':start_date', $start_date);
                $most_rendered_service->bindParam(':end_date', $end_date);
                $most_rendered_service->execute();
                $most_service = $most_rendered_service->fetch(PDO::FETCH_ASSOC);
                //Get least rendered service
                $least_rendered_service = $conn->prepare("SELECT service_type, COUNT(*) as rendered_count FROM reservations WHERE date_updated BETWEEN :start_date AND :end_date 
                    AND status = 'COMPLETED' GROUP BY service_type ORDER BY rendered_count ASC LIMIT 1");
                $least_rendered_service->bindParam(':start_date', $start_date);
                $least_rendered_service->bindParam(':end_date', $end_date);
                $least_rendered_service->execute(); 
                $least_service = $least_rendered_service->fetch(PDO::FETCH_ASSOC);
            ?>

            <div class="most">
                <div class="summary-title"> Most Rendered Service </div> <br>
                <?php if($most_service){ ?>
                    <div class="summary-result"><?php echo $most_service['service_type']; ?> (<?php echo $most_service['rendered_count']; ?>)</div>
                <?php } else { ?>
                    <div class="summary-result"> No Completed Reservations Yet...</div>
                <?php } ?>
            </div>

            <br>

            <div class="least">
                <div class="summary-title"> Least Rendered Service </div> <br>
                <?php if($least_service){ ?>
                    <div class="summary-result"><?php echo $least_service['service_type']; ?> (<?php echo $least_service['rendered_count']; ?>)</div>
                <?php } else { ?>
                    <div class="summary-result"> No Completed Reservations Yet...</div>
                <?php } ?>
            </div>
        </div> 
    </div>

    <br><br>

    <div style="display: flex;">
        <div class="comparison">
            <?php
                $current_month = isset($_POST['selected_month']) ? $_POST['selected_month'] : date('Y-m');
                $current_start_date = date('Y-m-01', strtotime($current_month));
                $current_end_date = date('Y-m-t', strtotime($current_month));
                $prev_month = date('Y-m', strtotime('-1 month', strtotime($current_month)));
                $prev_start_date = date('Y-m-01', strtotime($prev_month));
                $prev_end_date = date('Y-m-t', strtotime($prev_month));
                $current_total_completed = 0;
                $current_total_cancelled = 0;
                $prev_total_completed = 0;
                $prev_total_cancelled = 0;
                $show_reservation = $conn->prepare("SELECT service_type, COUNT(*) as completed_count FROM reservations WHERE status = 'COMPLETED' AND date_updated BETWEEN :start_date AND :end_date 
                GROUP BY service_type ORDER BY completed_count DESC");
                // Get totals for current month
                $show_reservation->bindParam(':start_date', $current_start_date);
                $show_reservation->bindParam(':end_date', $current_end_date);
                $show_reservation->execute();
                while ($fetch_reservation = $show_reservation->fetch(PDO::FETCH_ASSOC)) {
                    $current_total_completed += $fetch_reservation['completed_count'];
                }
                // Get totals for previous month
                $show_reservation->bindParam(':start_date', $prev_start_date);
                $show_reservation->bindParam(':end_date', $prev_end_date);
                $show_reservation->execute();
                while ($fetch_reservation = $show_reservation->fetch(PDO::FETCH_ASSOC)) {
                    $prev_total_completed += $fetch_reservation['completed_count'];
                }
                // Calculate percentages
                $current_total = $current_total_completed;
                $prev_total = $prev_total_completed;
                if ($current_total != 0) {
                    $current_completed_percentage = round(($current_total_completed / $current_total) * 100, 2);
                } else {
                    // Handle the case when $current_total is zero
                    // For example, you can set $current_completed_percentage to 0 or display an error message
                    $current_completed_percentage = 0;
                    // or
                    // echo "Error: Division by zero!";
                }
                $prev_completed_percentage = 0;
                $difference = 0;
                $comparison = '';
                // Check if there is a previous month
                if ($prev_total > 0) {
                    $prev_completed_percentage = round(($prev_total_completed / $prev_total) * 100, 2);
                    $difference = round((abs($current_total - $prev_total) / $prev_total) * 100, 2);
                    // Compare with previous month
                    if ($current_total > $prev_total) {
                        $comparison = 'higher';
                    } elseif ($current_total < $prev_total) {
                        $comparison = 'lower';
                    } else {
                        $comparison = 'equal to';
                    }
                    // Display result
                    //Style the plain text
                    $result_text = "
                    
                    <div class='compare-container'> 
                    The total services rendered this month is
                    <b>$comparison</b> by <b style='color:var(--yellow)'>$difference%</b> compared to last month.
                    </div>

                    ";
                    } else {
                    // Display result if there is no previous month
                    $result_text = "There is no previous month to compare with.";
                    }

                    $show_reservation = $conn->prepare("SELECT service_type, COUNT(*) as cancelled_count FROM reservations WHERE status = 'CANCELLED' AND date_updated BETWEEN :start_date AND :end_date 
                    GROUP BY service_type ORDER BY cancelled_count DESC");
                    // Get totals for current month
                    $show_reservation->bindParam(':start_date', $current_start_date);
                    $show_reservation->bindParam(':end_date', $current_end_date);
                    $show_reservation->execute();
                    while ($fetch_reservation = $show_reservation->fetch(PDO::FETCH_ASSOC)) {
                        $current_total_cancelled += $fetch_reservation['cancelled_count'];
                    }
                    // Get totals for previous month
                    $show_reservation->bindParam(':start_date', $prev_start_date);
                    $show_reservation->bindParam(':end_date', $prev_end_date);
                    $show_reservation->execute();
                    while ($fetch_reservation = $show_reservation->fetch(PDO::FETCH_ASSOC)) {
                        $prev_total_cancelled += $fetch_reservation['cancelled_count'];
                    }
                    // Calculate percentages
                    $current_total = $current_total_cancelled;
                    $prev_total = $prev_total_cancelled;
                    $current_cancelled_percentage = round(($current_total_cancelled / $current_total) * 100, 2);
                    $prev_cancelled_percentage = 0;
                    $difference = 0;
                    $comparison = '';
                    // Check if there is a previous month
                    if ($prev_total > 0) {
                        $prev_cancelled_percentage = round(($prev_total_cancelled / $prev_total) * 100, 2);
                        $difference = round((abs($current_total - $prev_total) / $prev_total) * 100, 2);
                        // Compare with previous month
                        if ($current_total > $prev_total) {
                            $comparison = 'higher';
                        } elseif ($current_total < $prev_total) {
                            $comparison = 'lower';
                        } else {
                            $comparison = 'equal to';
                        }
                        // Display result
                        $result_text1 = "
                        <div class='compare-container'> 
                        The total cancelled services this month is <b>$comparison</b> by <b style='color:var(--yellow)'>$difference%</b> compared to last month.</div>";
                        } else {
                        // Display result if there is no previous month
                        $result_text1 = "There is no previous month to compare with.";
                        }
                ?>

        <div class="completed">
            <div class="summary-title"><?php echo $result_text; ?> </div> <br>
        </div>

        <br>

        <div class="cancelled">
            <div class="summary-title"><?php echo $result_text1; ?> </div> <br>
        </div>

    </div>

        <div class="stats-display">
            <table class="stats-display-table">
            <thead>
                <tr>
                    <th>Service Type</th>
                    <th>Completed Service</th>
                    <th>Cancelled Service</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $selected_month = isset($_POST['selected_month']) ? $_POST['selected_month'] : date('Y-m');
                $start_date = date('Y-m-01', strtotime($selected_month));
                $end_date = date('Y-m-t', strtotime($selected_month));
                $show_reservation = $conn->prepare("SELECT service_type, SUM(CASE WHEN status = 'COMPLETED' THEN 1 ELSE 0 END) as completed_count, 
                SUM(CASE WHEN status = 'CANCELLED' THEN 1 ELSE 0 END) as cancelled_count FROM reservations WHERE date_updated BETWEEN :start_date AND :end_date 
                GROUP BY service_type ORDER BY completed_count DESC");
                $show_reservation->bindParam(':start_date', $start_date);
                $show_reservation->bindParam(':end_date', $end_date);
                $show_reservation->execute();
                if ($show_reservation->rowCount() > 0) {
                    while ($fetch_reservation = $show_reservation->fetch(PDO::FETCH_ASSOC)) {
                ?>
            <tr>
                <td><?php echo $fetch_reservation['service_type']; ?></td>
                <td><?php echo $fetch_reservation['completed_count']; ?></td>
                <td><?php echo $fetch_reservation['cancelled_count']; ?></td>
            </tr>
                <?php
                    }
                } else {
                    echo '<tr><td colspan="3">No Completed Reservations Yet...</td></tr>';
                }
                ?>
            </tbody>
            </table>
        </div>
    </div>

    <br>

    <h1 class="title"> Reservations </h1> <br>
    <div style="display: flex;"> 
        <div class="chart" style="margin-right: 20px;">
            <canvas id="completedChart"></canvas>
                <?php
                    $select_reservation = $conn->prepare("SELECT date_updated, COUNT(*) AS count_completed FROM reservations WHERE status = 'COMPLETED' 
                    GROUP BY date_updated");
                    $select_reservation->execute();
                        if($select_reservation->rowCount() > 0){
                            $date_updated = array(); 
                            $total_completed = array();
                            while($fetch_reservation = $select_reservation->fetch(PDO::FETCH_ASSOC)) {
                                $date_updated [] = $fetch_reservation['date_updated']; 
                                $total_completed[] = $fetch_reservation['count_completed']; 
                            }
                        }
                ?>

                <h1>START: <input id="start" type="date" min="2023-03-01" value="2023-03-01">
                    END:<input id="end" type="date" min="2023-03-01" max="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d'); ?>"></h1>
                <button class="btn-filter" onclick="filterDate()">Filter</button>  

                

        </div>

    <br> <br>
        
        <div class="chart">
            <canvas id="cancelledChart"></canvas>
                 <?php
                    $select_reservation = $conn->prepare("SELECT date_updated, COUNT(*) AS count_cancelled FROM reservations WHERE status = 'CANCELLED' 
                    GROUP BY date_updated");
                    $select_reservation->execute();
                    if($select_reservation->rowCount() > 0){
                        $date_updated1 = array(); 
                        $total_cancelled = array();
                        while($fetch_reservation = $select_reservation->fetch(PDO::FETCH_ASSOC)) {
                            $date_updated1 [] = $fetch_reservation['date_updated']; 
                            $total_cancelled[] = $fetch_reservation['count_cancelled']; 
                        }
                    }
                ?>

            <h1>START: <input id="start1" type="date" min="2023-03-01" value="2023-03-01">
                END:<input id="end1" type="date" min="2023-03-01 "max="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d'); ?>"></h1>
            <button class="btn-filter" onclick="filterDate1()">Filter</button>  
        </div>

    <br>
</div>

    <br> <br>

    <h1 class="title">  Reviews & Rating </h1> <br>
    <div  style="display: flex;"> 
        <div class="chart" style="margin-right:20px">
            <canvas id="reviewChart"></canvas>
                <?php
                    $select_review = $conn->prepare("SELECT MONTH(date_posted) AS month, COUNT(*) AS count_review FROM reviews GROUP BY MONTH(date_posted)");
                    $select_review->execute();
                    if($select_review->rowCount() > 0){
                        $month_posted= array(); 
                        $total_reviews = array();
                        while($fetch_review = $select_review->fetch(PDO::FETCH_ASSOC)) {
                            $month_posted[] = $fetch_review['month']; 
                            $total_reviews[] = $fetch_review['count_review']; 
                        }
                    }
                ?>

            <h1>START: <input id="start2" type="month" min="2023-02" max="<?php echo date('Y-m'); ?>" value="2023-02">
                END:<input id="end2" type="month" min="2023-02" max="<?php echo date('Y-m'); ?>" value="<?php echo date('Y-m'); ?>"></h1>
            <button class="btn-filter" onclick="filterMonth()">Filter</button>  
        </div>
  
    <br>

        <div class="chart">
            <canvas id="avgReviewChart"></canvas>
            <?php
                    $select_review = $conn->prepare("SELECT MONTH(date_posted) AS month, AVG(rating) AS avg_rating FROM reviews GROUP BY MONTH(date_posted)");
                    $select_review->execute();
                    if($select_review->rowCount() > 0){
                        $month_posted1 = array(); 
                        $avg_rating = array();
                        while($fetch_review = $select_review->fetch(PDO::FETCH_ASSOC)) {
                            $month_posted1[] = $fetch_review['month']; 
                            $avg_rating[] = $fetch_review['avg_rating']; 
                        }
                    }
                ?>

            <h1>START: <input id="start3" type="month" min="2023-02" max="<?php echo date('Y-m'); ?>" value="2023-02">
                END:<input id="end3" type="month" min="2023-02" max="<?php echo date('Y-m'); ?>" value="<?php echo date('Y-m'); ?>"></h1>
            <button class="btn-filter" onclick="filterMonth1()">Filter</button>  
        </div>
    </div>
    
     <br>
     <h1 class="title"> Customer Accounts </h1> <br>
     <div class="chart" style="margin: auto auto; width: 90%">
            <canvas id="accountChart"></canvas>
                <?php
                    $select_account = $conn->prepare("SELECT MONTH(account_created) AS month, COUNT(*) AS count_account FROM customers GROUP BY MONTH(account_created)");
                    $select_account->execute();
                    if($select_account->rowCount() > 0){
                        $month_created = array(); 
                        $total_account = array();
                        while($fetch_account =  $select_account->fetch(PDO::FETCH_ASSOC)) {
                            $month_created[] = $fetch_account['month']; 
                            $total_account[] = $fetch_account['count_account']; 
                        }
                    }
                ?>
            <h1>START: <input id="start4" type="month" min="2023-02" max="<?php echo date('Y-m'); ?>"  value="2023-02">
                    END:<input id="end4" type="month" min="2023-02" max="<?php echo date('Y-m'); ?>" value="<?php echo date('Y-m'); ?>"></h1>
            <button class="btn-filter" onclick="filterMonth2()">Filter</button>  
        </div>

    <br>

    <br>
    <h1 class="title"> Website Visits </h1> <br>
    <div class="chart" style="margin: auto auto; width: 90%">
        <canvas id="visitChart"></canvas>
            <?php
            $select_visits = $conn->prepare("SELECT MONTH(visit_time) AS visit_month, COUNT(*) AS total_visits FROM website_visits GROUP BY MONTH(visit_time)");
            $select_visits->execute();
            $total_visits = array();
            $date_visited = array();
            if($select_visits->rowCount() > 0){
                while($fetch_visits = $select_visits->fetch(PDO::FETCH_ASSOC)) {
                $total_visits[] = $fetch_visits['total_visits'];
                $date_visited[] = $fetch_visits['visit_month'];
                }
            }
            ?>
                <h1>START: <input id="start5" type="month" min="2023-02" max="<?php echo date('Y-m'); ?>"  value="2023-02">
                    END:<input id="end5" type="month" min="2023-02" max="<?php echo date('Y-m'); ?>" value="<?php echo date('Y-m'); ?>"></h1>
            <button class="btn-filter" onclick="filterMonth3()">Filter</button>  
    </div>

</div>

<br> <br>

</section>

<!-- FOOTER -->
<?php include '../components/admin_footer.php'; ?>

<!-- JAVA SCRIPT -->
<script src="../js/admin.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>

<!-- COMPLETED -->
<script>
    const dates = <?php echo json_encode($date_updated); ?>;
    const completed = <?php echo json_encode($total_completed); ?>;
    const convertedDates = dates.map(date => new Date(date).setHours(0,0,0,0));
    const dreservation = document.getElementById('completedChart');
    const completedChart = new Chart(dreservation, {
        type: 'line',
        data: {
        labels: dates,
            datasets: [{
                label: '# of Completed Reservations',
                data: completed,
                borderWidth: 2,
                borderColor: "#0AC81E"
            }]
        },
        options: {
            scales: {
                x:{
                    type: 'time',
                    time: {
                        unit: 'day'
                    }
                },
                y: {
                beginAtZero: true,
                max: 20,
                position: 'left',
                ticks: {
                stepSize: 1
                    }
                },
                y2: {
                beginAtZero: true,
                max: 20,
                position: 'right',
                ticks: {
                stepSize: 1
                    }
                }
            }
        }
    });

    function filterDate(){
        const start1 = new Date(document.getElementById('start').value);
        const start = start1.setHours(0,0,0,0);
        const end1 = new Date(document.getElementById('end').value);
        const end = end1.setHours(0,0,0,0);
        const filterDates = convertedDates.filter(date => date >= start && date <= end)
        completedChart.data.labels = filterDates;
        const startArray = convertedDates.indexOf(filterDates[0])
        const endArray = convertedDates.indexOf(filterDates[filterDates.length - 1])
        const copyCompleted  = completed.slice(startArray, endArray + 1);
        completedChart.data.datasets[0].data = copyCompleted;
        completedChart.update();
    }
</script>

<!-- CANCELLED -->
<script>
    const dates1 = <?php echo json_encode($date_updated1); ?>;
    const cancelled = <?php echo json_encode($total_cancelled); ?>;
    const convertedDates1 = dates1.map(date => new Date(date).setHours(0,0,0,0));
    const creservation = document.getElementById('cancelledChart');
    const cancelledChart = new Chart(creservation, {
        type: 'line',
        data: {
        labels: dates1,
            datasets: [{
                label: '# of Cancelled Reservations',
                data: cancelled,
                borderWidth: 2,
                borderColor: "#F00D29"
            }]
        },
        options: {
            scales: {
                x:{
                    type: 'time',
                    time: {
                        unit: 'day'
                    }
                },
                y: {
                beginAtZero: true,
                max: 20,
                position: 'left',
                ticks: {
                stepSize: 1
                    }
                },
                y2: {
                beginAtZero: true,
                max: 20,
                position: 'right',
                ticks: {
                stepSize: 1
                    }
                }
            }
        }
    });

    function filterDate1(){
        const start2 = new Date(document.getElementById('start1').value);
        const start1 = start2.setHours(0,0,0,0);
        const end2 = new Date(document.getElementById('end1').value);
        const end1 = end2.setHours(0,0,0,0);
        const filterDates1 = convertedDates1.filter(date => date >= start1 && date <= end1)
        cancelledChart.data.labels = filterDates1;
        const startArray1 = convertedDates1.indexOf(filterDates1[0])
        const endArray1 = convertedDates1.indexOf(filterDates1[filterDates1.length - 1])
        const copyCancelled = cancelled.slice(startArray1, endArray1 + 1);
        cancelledChart.data.datasets[0].data = copyCancelled;
        cancelledChart.update();
    }  
</script>

<!-- REVIEWS -->
<script>
    const dates2 = <?php echo json_encode($month_posted); ?>;
    const reviews = <?php echo json_encode($total_reviews); ?>;
    const convertedDates2 = dates2.map(month => new Date(`2023-${month}`));
    const treview = document.getElementById('reviewChart');
    const reviewChart = new Chart(treview, {
        type: 'bar',
        data: {
            labels: convertedDates2,
            datasets: [{
                label: '# of Reviews',
                data: reviews,
                borderWidth: 1,
                backgroundColor: "#616BFD"
            }]
        },
        options: {
            scales: {
                x: {
                    type: 'time',
                    time: {
                        unit: 'month',
                    }
                },
                y: {
                    beginAtZero: true,
                    position: 'left',
                    ticks: {
                        stepSize: 1
                    }
                },
            },     
            plugins: {
                tooltip: {
                    enabled: false // <-- this option disables tooltips
                }
            }
        }
    });

    function filterMonth() {
        const start3 = new Date(document.getElementById('start2').value);
        const start2 = start3.setHours(0,0,0,0);
        const end3 = new Date(document.getElementById('end2').value);
        const end2 = end3.setHours(0,0,0,0);
        const filterDates2 = convertedDates2.filter(date => date >= start2 && date <= end2)
        reviewChart.data.labels = filterDates2;
        const startArray2 = convertedDates2.indexOf(filterDates2[0])
        const endArray2 = convertedDates2.indexOf(filterDates2[filterDates2.length - 1])
        const copyReviews = reviews.slice(startArray2, endArray2 + 1);
        reviewChart.data.datasets[0].data = copyReviews;
        reviewChart.update();
    }
</script>

<!-- AVGREVIEWS -->
<script>
    const dates3 = <?php echo json_encode($month_posted1); ?>;
    const avgreviews = <?php echo json_encode($avg_rating); ?>;
    const convertedDates3 = dates3.map(month => new Date(`2023-${month}`));
    const avgreview = document.getElementById('avgReviewChart');
    const avgReviewChart = new Chart(avgreview, {
    type: 'bar',
    data: {
        labels: convertedDates3,
        datasets: [{
            label: 'Average Rating',
            data: avgreviews,
            borderWidth: 1,
            backgroundColor: "#F1BD35"
        }]
    },
    options: {
        scales: {
            x: {
                type: 'time',
                time: {
                    unit: 'month'
                }
            },
            y: {
                beginAtZero: true,
                max: 5,
                position: 'left',
                ticks: {
                    stepSize: .5
                }
            },
        },
        plugins: {
            tooltip: {
                enabled: false // <-- this option disables tooltips
            }
         }
    }
});

    function filterMonth1() {
        const start4 = new Date(document.getElementById('start3').value);
        const start3 = start4.setHours(0,0,0,0);
        const end4 = new Date(document.getElementById('end3').value);
        const end3 = end4.setHours(0,0,0,0);
        const filterDates3 = convertedDates3.filter(date => date >= start3 && date <= end3)
        avgReviewChart.data.labels = filterDates3;
        const startArray3 = convertedDates3.indexOf(filterDates3[0])
        const endArray3 = convertedDates3.indexOf(filterDates3[filterDates3.length - 1])
        const copyAvgReviews = avgreviews.slice(startArray3, endArray3 + 1);
        avgReviewChart.data.datasets[0].data = copyAvgReviews;
        avgReviewChart.update();
    }
</script>

<!-- Account Chart -->
<script>
const dates4 = <?php echo json_encode($month_created); ?>;
const totalAccount = <?php echo json_encode($total_account); ?>;
const convertedDates4 = dates4.map(month => new Date(`2023-${month}`));
 
const accounts = document.getElementById('accountChart');
const accountChart = new Chart(accounts, {
    type: 'bar',
    data: {
        labels: convertedDates4, 
        datasets: [{
            label: '# of Customer Account Created',
            data: totalAccount,
            borderWidth: 1,
            backgroundColor: "#ED9206"
        }]
    },
    options: {
        scales: {
            x: {
                type: 'time',
                time: {
                    unit: 'month'
                }
            },
            y: {
                beginAtZero: true,
                max: 26,
                position: 'left',
                ticks: {
                    stepSize: 2
                }
            },
        },
        plugins: {
            tooltip: {
                enabled: false // <-- this option disables tooltips
            }
        }
    }
});

function filterMonth2() {
    const start5 = new Date(document.getElementById('start4').value);
    const start4 = start5.setHours(0,0,0,0);
    const end5 = new Date(document.getElementById('end4').value);
    const end4 = end5.setHours(0,0,0,0);
    const filterDates4 = convertedDates4.filter(date => date >= start4 && date <= end4);
    accountChart.data.labels = filterDates4;
    const startArray4 = convertedDates4.indexOf(filterDates4[0]);
    const endArray4 = convertedDates4.indexOf(filterDates4[filterDates4.length - 1]);
    const copyTotalAccount = totalAccount.slice(startArray4, endArray4 + 1); 
    accountChart.data.datasets[0].data = copyTotalAccount;
    accountChart.update();
}
</script>

<!-- SHOW SERVICES -->
<script>
const countCompleted = <?php echo json_encode($count_completed); ?>;
const serviceType = <?php echo json_encode($service_type); ?>;
const convertedDates5 = dates4.map(month => new Date(`2023-${month}`));

const acquired = new Chart(document.getElementById('acquiredChart'), {
    type: 'pie',
    data: {
        labels: serviceType,
        datasets: [{
            label: 'Service Acquired',
            data: countCompleted,
            backgroundColor: [
                '#FF6384',
                '#36A2EB',
                '#FFCE56',
                '#FF8C00',
                '#FFD700',
                '#8F00FF',
                '#00CED1',
                '#00FF00',
                '#B22222',
                '#6B8E23',
                '#FF69B4',
                '#4B0082'
            ],
            borderWidth: 1
        }]
    },
    options: {
        legend: {
            position: 'bottom',
        },
    }
});
</script>

<!-- Total Visits -->
<script>
    const dates5 = <?php echo json_encode($date_visited); ?>;
    const totalVisits = <?php echo json_encode($total_visits); ?>;
    const convertedDates6 = dates5.map(month => new Date(`2023-${month}`));
    const visits = document.getElementById('visitChart');
    const visitChart = new Chart(visits, {
    type: 'bar',
    data: {
        labels: convertedDates6,
        datasets: [{
            label: '# of Total Visits',
            data: totalVisits,
            borderWidth: 1,
            backgroundColor: "#F1BD35"
        }]
    },
    options: {
        scales: {
            x: {
                type: 'time',
                time: {
                    unit: 'month'
                }
            },
            y: {
                beginAtZero: true,
                position: 'left',
                ticks: {
                    stepSize: 5
                }
            }
        },
        plugins: {
            tooltip: {
                enabled: false // <-- this option disables tooltips
            }
         }
    }
});

function filterMonth3() {
    const start6 = new Date(document.getElementById('start5').value);
    const start5 = start6.setHours(0,0,0,0);
    const end6 = new Date(document.getElementById('end5').value);
    const end5 = end6.setHours(0,0,0,0);
    const filterDates5 = convertedDates6.filter(date => date >= start5 && date <= end5);
    visitChart.data.labels = filterDates5;
    const startArray5 = convertedDates6.indexOf(filterDates5[0]);
    const endArray5 = convertedDates6.indexOf(filterDates5[filterDates5.length - 1]);
    const copyVisits = totalVisits.slice(startArray5, endArray5 + 1); 
    visitChart.data.datasets[0].data = copyVisits;
    visitChart.update();
}
</script>



</body>
</html>