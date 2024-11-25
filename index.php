<?php
require_once __DIR__ . '/vendor/autoload.php';

$mpdf = new \mPDF();
include('condb.php'); // Database connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT * FROM tb_member";
$result = mysqli_query($conn, $sql);

$content = "";
$tablebody = "";
$totalMembers = 0; // Variable to keep track of total members
if (mysqli_num_rows($result) > 0) {
  $i = 1;
  while ($row = mysqli_fetch_assoc($result)) {
    $tablebody .= '<tr style="border:1px solid black;">
            <td style="border:1px solid black;padding:3px;text-align:center;">' . $i . '</td>
            <td style="border:1px solid black;padding:3px;text-align:center;">' . $row['username'] . '</td>
            <td style="border:1px solid black;padding:3px;text-align:center;">' . $row['member_name'] . '</td>
            <td style="border:1px solid black;padding:3px;text-align:center;">' . $row['member_lname'] . '</td>
            <td style="border:1px solid black;padding:3px;text-align:center;">' . $row['email'] . '</td>
          </tr>';
    $i++;
    $totalMembers++; // Increment total members
  }
}

mysqli_close($conn); // Close database connection

$table = '
<style>
  body {
    font-family: "Garuda";
  }
  table {
    border-collapse: collapse;
    width: 100%;
    font-size: 12pt;
  }
  th, td {
    border: 1px solid black;
    padding: 4px;
  }
  .no-border {
    border: none;
  }
  .footer-signature {
    margin-top: 20px;
    text-align: center;
    font-size: 12pt;
  }
  .footer-signature table {
    width: 100%;
    margin-top: 20px;
  }
  .footer-signature td {
    text-align: center;
    padding: 20px;
  }
</style>

<h1 style="text-align:center;">บริษัท Crosswalk Agency</h1>
<p style="text-align:center;">
    โครงการ Fifth Avenue 555/90 หมู่ที่ 2 San Sai District, Chiang Mai 50210<br>
    Tel: 0-2739-5900 | Fax: 0-2739-5910 | Tax ID: 31523611000
</p>

<h2 style="text-align:center;">ใบวางบิล</h2>
<p>รหัสลูกค้า ก-0002</p>

<table width="100%" style="border-collapse: collapse; font-size: 12pt;">
    <tr style="border:1px solid black;">
        <td style="padding: 10px; line-height: 1.8;">
            ชื่อลูกค้า: บริษัท Crosswalk Agency <br>
            ที่อยู่: โครงการ Fifth Avenue 555/90 หมู่ที่ 2 <br>
            San Sai District, Chiang Mai 50210
        </td>
        <td style="padding: 10px; line-height: 1.8;">
            เลขที่: B15311-00001 <br>
            วันที่: 20/03/2553 <br>
            วันที่นัดชำระ: 19/04/2553 <br>
            เงื่อนไขการจ่ายชำระ : รับชำระเป็นเงินสดเท่านั้น
        </td>
    </tr>
</table>

<p>รับบิลไว้ตรวจสอบตามรายการข้างล่างนี้ถูกต้องแล้ว</p>

<table width="100%" style="border-collapse: collapse; font-size: 12pt;">
    <thead>
        <tr>
            <th width="10%">ลำดับ</th>
            <th width="15%">Username</th>
            <th width="15%">ชื่อ</th>
            <th width="15%">สกุล</th>
            <th width="15%">อีเมล์</th>
        </tr>
    </thead>
    <tbody>
        ' . $tablebody . '
    </tbody>
</table>

<table width="100%" style="border-collapse: collapse; font-size: 12pt;">
    <tr>
        <td style="border:1px solid black; text-align:center;">จำนวน</td>
        <td style="border:1px solid black; text-align:center;">' . $totalMembers . '</td>
    </tr>
</table>

<p>หมายเหตุ: ข้อมูลสมาชิกทั้งหมด</p>

<div class="footer-signature">
    <table width="100%" style="text-align: center; border-collapse: collapse; font-size: 12pt;">
        <tr>
            <td><br>_______________________<br><br>ผู้วางบิล<br><br>วันที่______________</td>
            <td><br>_______________________<br><br>ผู้รับวางบิล<br><br>วันที่______________</td>
        </tr>
    </table>
</div>
';

$mpdf->WriteHTML($table);


$mpdf->Output();
