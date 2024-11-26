<?php


include('condb.php'); // เชื่อมต่อฐานข้อมูล
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}



$sql = "SELECT * FROM tb_member";
$result = mysqli_query($conn, $sql);


header("Content-Type: application/vnd.ms-excel");
header('Content-Disposition: attachment; filename="MyXls.xls"'); #ชื่อไฟล์


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Export Excel</title>
  <style>
    body {
      font-family: "Garuda";
    }

    table {
      border-collapse: collapse;
      width: 100%;
      font-size: 12pt;
    }

    th,
    td {

      border: 1px solid black;
      padding: 4px;
      text-align: center;
    }

    .footer-signature {
      width: 100%;
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

    h1,
    h2,
    p {
      text-align: center;
    }

    .header-info td {
      width: 50%;
      text-align: left;
      padding: 10px;
      line-height: 1.8;
    }

    .note {
      text-align: left;
      margin: 10px 0;
    }
  </style>
</head>

<body>
  <!-- ส่วนหัวของเอกสาร -->
  <h1>บริษัท Crosswalk Agency</h1>
  <p>โครงการ Fifth Avenue 555/90 หมู่ที่ 2 San Sai District, Chiang Mai 50210<br>
    Tel: 0-2739-5900 | Fax: 0-2739-5910 | Tax ID: 31523611000
  </p>
  <h2>ใบวางบิล</h2>
  <p>รหัสลูกค้า ก-0002</p>

  <!-- รายละเอียดบิล -->
  <table class="header-info">
    <tr>
      <td>
        ชื่อลูกค้า: บริษัท Crosswalk Agency<br>
        ที่อยู่: โครงการ Fifth Avenue 555/90 หมู่ที่ 2<br>
        San Sai District, Chiang Mai 50210
      </td>
      <td>
        เลขที่: B15311-00001<br>
        วันที่: 20/03/2553<br>
        วันที่นัดชำระ: 19/04/2553<br>
        เงื่อนไขการจ่ายชำระ: รับชำระเป็นเงินสดเท่านั้น
      </td>
    </tr>
  </table>

  <!-- ตารางข้อมูลสมาชิก -->
  <button id="export" onclick="exportTableToExcel('table-excel', 'data')">Export</button>
  <table id="table-excel">
    <thead>
      <tr>
        <th>No</th>
        <th>username</th>
        <th>member_name</th>
        <th>member_lname</th>
        <th>email</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if (mysqli_num_rows($result) > 0) {
        $i = 1;
        while ($row = mysqli_fetch_assoc($result)) {
          echo "<tr>
                        <td>{$i}</td>
                        <td>{$row['username']}</td>
                        <td>{$row['member_name']}</td>
                        <td>{$row['member_lname']}</td>
                        <td>{$row['email']}</td>
                    </tr>";
          $i++;
        }
      } else {
        echo "<tr><td colspan='5'>ไม่มีข้อมูล</td></tr>";
      }
      ?>
    </tbody>
  </table>

  <!-- หมายเหตุ -->
  <p class="note">รับบิลไว้ตรวจสอบตามรายการข้างล่างนี้ถูกต้องแล้ว</p>
  <p class="note">หมายเหตุ: ข้อมูลสมาชิกทั้งหมด</p>

  <!-- ลายเซ็น -->
  <div class="footer-signature">
    <table>
      <tr>
        <td>
          _______________________<br>
          ผู้วางบิล<br>
          วันที่______________
        </td>
        <td>
          _______________________<br>
          ผู้รับวางบิล<br>
          วันที่______________
        </td>
      </tr>
    </table>
  </div>

  <!-- สคริปต์ Export Excel -->
  <script>
    function exportTableToExcel(tableID, filename = 'excel_data') {
      const table = document.getElementById(tableID);
      const rows = table.rows;

      let csvContent = '';

      // เพิ่มข้อมูลส่วนหัวของเอกสารในไฟล์ Excel
      const headerContent = [
        ['บริษัท Crosswalk Agency'],
        ['โครงการ Fifth Avenue 555/90 หมู่ที่ 2 San Sai District, Chiang Mai 50210'],
        ['Tel: 0-2739-5900 | Fax: 0-2739-5910 | Tax ID: 31523611000'],
        ['ใบวางบิล'],
        ['รหัสลูกค้า ก-0002'],
        ['']
      ];
      headerContent.forEach(row => {
        csvContent += row.join(',') + '\n';
      });

      // เพิ่มข้อมูลรายละเอียดบิล
      const billDetails = [
        ['ชื่อลูกค้า: บริษัท Crosswalk Agency', 'เลขที่: B15311-00001'],
        ['ที่อยู่: โครงการ Fifth Avenue 555/90 หมู่ที่ 2', 'วันที่: 20/03/2553'],
        ['San Sai District, Chiang Mai 50210', 'วันที่นัดชำระ: 19/04/2553'],
        ['', 'เงื่อนไขการจ่ายชำระ: รับชำระเป็นเงินสดเท่านั้น'],
        ['']
      ];
      billDetails.forEach(row => {
        csvContent += row.join(',') + '\n';
      });

      // เพิ่มข้อมูลตารางสมาชิก
      csvContent += '\n'; // เว้นบรรทัดก่อนเพิ่มข้อมูลตาราง
      for (let i = 0; i < rows.length; i++) {
        const row = Array.from(rows[i].cells).map(cell => `"${cell.textContent.trim()}"`);
        csvContent += row.join(',') + '\n';
      }

      // เพิ่มส่วนท้าย
      csvContent += '\n';
      const footerContent = [
        ['รับบิลไว้ตรวจสอบตามรายการข้างล่างนี้ถูกต้องแล้ว'],
        ['หมายเหตุ: ข้อมูลสมาชิกทั้งหมด'],
        [''],
        ['_______________________,ผู้วางบิล,,,,_______________________,ผู้รับวางบิล'],
        [',วันที่______________,,,,,วันที่______________']
      ];
      footerContent.forEach(row => {
        csvContent += row.join(',') + '\n';
      });

      // สร้างไฟล์ CSV
      const blob = new Blob([csvContent], {
        type: 'text/csv;charset=utf-8;'
      });
      const link = document.createElement('a');

      if (navigator.msSaveBlob) {
        navigator.msSaveBlob(blob, filename + '.csv');
      } else {
        link.href = URL.createObjectURL(blob);
        link.setAttribute('download', filename + '.csv');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
      }
    }
  </script>

</body>

</html>