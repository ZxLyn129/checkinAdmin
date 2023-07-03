<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../checkinAdmin/TCPDF/tcpdf.php';
include 'dbconnect.php';

if (isset($_GET['adminName'])){
    $adminName = $_GET['adminName'];
}

// Get the start and end dates from the form submission
$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';

// Query to fetch the data based on the date range
$query = "SELECT c.*, u.*
    FROM tbl_checkin c
    JOIN tbl_users u ON c.user_id = u.user_id
    WHERE c.checkin_date BETWEEN :start_date AND :end_date";

$stmt = $conn->prepare($query);
$stmt->bindParam(':start_date', $start_date);
$stmt->bindParam(':end_date', $end_date);
$stmt->execute();

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if there are rows returned
if (count($rows) > 0) {
    // Generate the PDF report
    $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false, '/absolute/path/to/tcpdf/');
    $pdf->SetCreator('Check-in System');
    $pdf->SetAuthor('Check-in System');
    $pdf->SetTitle('Check-in Report');
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    $pdf->AddPage();

    // Add the table header
    $pdf->SetFont('helvetica', 'B', 11);
    $pdf->Cell(50, 10, 'User Name', 1, 0, 'C');
    $pdf->Cell(80, 10, 'Course', 1, 0, 'C');
    $pdf->Cell(20, 10, 'Group', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Location', 1, 0, 'C');
    $pdf->Cell(40, 10, 'State', 1, 0, 'C');
    $pdf->Cell(50, 10, 'Check-in Date', 1, 1, 'C');

    // Add the table rows
    $pdf->SetFont('helvetica', '', 12);
    $rowHeight = 10; // Minimum height of each row

    foreach ($rows as $row) {
        $checkinDate = date('F j, Y g:i A', strtotime($row['checkin_date']));

        // Calculate the maximum height required for the row
        $cellHeight = max(
            $pdf->getStringHeight(50, $row['user_name']),
            $pdf->getStringHeight(80, $row['checkin_course']),
            $pdf->getStringHeight(20, $row['checkin_group']),
            $pdf->getStringHeight(40, $row['checkin_location']),
            $pdf->getStringHeight(40, $row['checkin_state']),
            $pdf->getStringHeight(50, $checkinDate)
        );

        $currentY = $pdf->GetY(); // Get current Y position

        // Check if the row height exceeds the remaining space on the page
        if ($currentY + $cellHeight > $pdf->getPageHeight() - $pdf->getMargins()['bottom']) {
            $pdf->AddPage(); // Add a new page
            $currentY = $pdf->GetY(); // Update current Y position
        }

        // Check if the current row fits in the remaining space on the page
        if ($currentY + $cellHeight > $pdf->getPageHeight() - $pdf->getMargins()['bottom']) {
            break; // Exit the loop if there is not enough space for the current row
        }

        $pdf->MultiCell(50, $cellHeight, $row['user_name'], 1, 'C');
        $pdf->SetXY(60, $currentY); // Set X and Y position for the next cell
        $pdf->MultiCell(80, $cellHeight, $row['checkin_course'], 1, 'C');
        $pdf->SetXY(140, $currentY); // Set X and Y position for the next cell
        $pdf->MultiCell(20, $cellHeight, $row['checkin_group'], 1, 'C');
        $pdf->SetXY(160, $currentY); // Set X and Y position for the next cell
        $pdf->MultiCell(40, $cellHeight, $row['checkin_location'], 1, 'C');
        $pdf->SetXY(200, $currentY); // Set X and Y position for the next cell
        $pdf->MultiCell(40, $cellHeight, $row['checkin_state'], 1, 'C');
        $pdf->SetXY(240, $currentY); // Set X and Y position for the next cell
        $pdf->MultiCell(50, $cellHeight, $checkinDate, 1, 'C');

        $pdf->SetXY(10, $currentY + $cellHeight); // Set X and Y position for the next row
    }

    // Output the PDF as a download
    $pdf->Output('checkin_report.pdf', 'D');
} else {
    // No data available, display alert message
    echo '<script>alert("No data found for the specified date range.");</script>';
    echo "<script>window.location.replace('home.php?adminName=$adminName')</script>";
}
?>
