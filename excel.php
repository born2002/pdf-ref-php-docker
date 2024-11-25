<?php
require_once __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


include('condb.php'); // Database connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT * FROM tb_member";
$result = mysqli_query($conn, $sql);

$totalMembers = 0; // Variable to keep track of total members

// Create a new Spreadsheet object
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set the title of the sheet
$sheet->setCellValue('A1', 'บริษัท Crosswalk Agency');
$sheet->mergeCells('A1:F1');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

$sheet->setCellValue('A2', 'โครงการ Fifth Avenue 555/90 หมู่ที่ 2 San Sai District, Chiang Mai 50210');
$sheet->mergeCells('A2:F2');
$sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

$sheet->setCellValue('A3', 'Tel: 0-2739-5900 | Fax: 0-2739-5910 | Tax ID: 31523611000');
$sheet->mergeCells('A3:F3');
$sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

$sheet->setCellValue('A4', 'ใบวางบิล');
$sheet->getStyle('A4')->getFont()->setBold(true)->setSize(12);
$sheet->getStyle('A4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

$sheet->setCellValue('A5', 'รหัสลูกค้า ก-0002');

// Add the headers for the table
$sheet->setCellValue('A7', 'ลำดับ');
$sheet->setCellValue('B7', 'Username');
$sheet->setCellValue('C7', 'ชื่อ');
$sheet->setCellValue('D7', 'สกุล');
$sheet->setCellValue('E7', 'อีเมล์');

// Set the header row style
$sheet->getStyle('A7:E7')->getFont()->setBold(true);
$sheet->getStyle('A7:E7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A7:E7')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

$rowCount = 8; // Start from row 8 for the data
$i = 1; // Counter for the sequence
if (mysqli_num_rows($result) > 0) {
  while ($row = mysqli_fetch_assoc($result)) {
    $sheet->setCellValue('A' . $rowCount, $i);
    $sheet->setCellValue('B' . $rowCount, $row['username']);
    $sheet->setCellValue('C' . $rowCount, $row['member_name']);
    $sheet->setCellValue('D' . $rowCount, $row['member_lname']);
    $sheet->setCellValue('E' . $rowCount, $row['email']);

    $i++;
    $rowCount++;
    $totalMembers++; // Increment total members
  }
}

mysqli_close($conn); // Close database connection

// Add the total number of members at the bottom
$sheet->setCellValue('D' . $rowCount, 'จำนวน');
$sheet->setCellValue('E' . $rowCount, $totalMembers);

// Save the file as an Excel file
$writer = new Xlsx($spreadsheet);

// Send headers to prompt file download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="member_data.xlsx"');
header('Cache-Control: max-age=0');

// Write the Excel file to output
$writer->save('php://output');
exit;
