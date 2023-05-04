<?php
    include '../components/connect.php';
    session_start();

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
    } else {
        echo "No data found in the selected month.";
        exit;
    }

    // Generate CSV content
    $csvContent = "Month, Service Type, Count Completed" . PHP_EOL;
for ($i = 0; $i < count($month_updated); $i++) {
    $month_name = '';
    if ($month_updated[$i] == 1) {
        $month_name = 'January';
    } elseif ($month_updated[$i] == 2) {
        $month_name = 'February';
    } elseif ($month_updated[$i] == 3) {
        $month_name = 'March';
    } elseif ($month_updated[$i] == 4) {
        $month_name = 'April';
    } elseif ($month_updated[$i] == 5) {
        $month_name = 'May';
    } elseif ($month_updated[$i] == 6) {
        $month_name = 'June';
    } elseif ($month_updated[$i] == 7) {
        $month_name = 'July';
    } elseif ($month_updated[$i] == 8) {
        $month_name = 'August';
    } elseif ($month_updated[$i] == 9) {
        $month_name = 'September';
    } elseif ($month_updated[$i] == 10) {
        $month_name = 'October';
    } elseif ($month_updated[$i] == 11) {
        $month_name = 'November';
    } elseif ($month_updated[$i] == 12) {
        $month_name = 'December';
    }

    $csvContent .= $month_name . "," . $service_type[$i] . "," . $count_completed[$i] . PHP_EOL;
}


    // Set headers for download
    header("Content-type: text/csv");
    header("Content-Disposition: attachment; filename=data.csv");
    header("Pragma: no-cache");
    header("Expires: 0");

    // Output CSV content
    echo $csvContent;
    exit;
?>
