<?php 
include '../components/connect.php';
require_once '../PHPExcel/Classes/PHPExcel.php';

session_start();

if(isset($_SESSION['admin_id'])){
    $admin_id = $_SESSION['admin_id'] ;
}else{
    $admin_id = '';

    $selected_month = isset($_POST['selected_month']) ? $_POST['selected_month'] : date('Y-m');
    $start_date = date('Y-m-01', strtotime($selected_month));
    $end_date = date('Y-m-t', strtotime($selected_month));
    $show_reservation = $conn->prepare("SELECT service_type, SUM(CASE WHEN status = 'COMPLETED' THEN 1 ELSE 0 END) as completed_count, 
                    SUM(CASE WHEN status = 'CANCELLED' THEN 1 ELSE 0 END) as cancelled_count FROM reservations WHERE date_updated BETWEEN :start_date AND :end_date 
                    GROUP BY service_type ORDER BY completed_count DESC");
    $show_reservation->bindParam(':start_date', $start_date);
    $show_reservation->bindParam(':end_date', $end_date);
    $show_reservation->execute();

    // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();

    // Set document properties
    $objPHPExcel->getProperties()->setCreator('Your Name')
                                ->setLastModifiedBy('Your Name')
                                ->setTitle('Reservation Report')
                                ->setSubject('Reservation Report')
                                ->setDescription('Reservation Report')
                                ->setKeywords('reservation report')
                                ->setCategory('Report');

    // Add data to worksheet
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Service Type')
                ->setCellValue('B1', 'Completed Service')
                ->setCellValue('C1', 'Cancelled Service');

    if ($show_reservation->rowCount() > 0) {
        $i = 2;
        while ($fetch_reservation = $show_reservation->fetch(PDO::FETCH_ASSOC)) {
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $i, $fetch_reservation['service_type'])
                        ->setCellValue('B' . $i, $fetch_reservation['completed_count'])
                        ->setCellValue('C' . $i, $fetch_reservation['cancelled_count']);
            $i++;
        }
    } else {
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A2', 'No Completed Reservations Yet...');
    }

    // Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle('Reservation Report');

    // Set header and footer
    $objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&CReservation Report');
    $objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPAGE &P of &N');

    // Set column widths
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);

    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);

    // Output the Excel file to browser for download
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="reservation_report.xls"');
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');


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

<!-- FOOTER -->
<?php include '../components/admin_footer.php'; ?>

<!-- JAVA SCRIPT -->
<script src="../js/admin.js"></script>
</body>
</html>