<?php
require 'condb.php';

$db = new connectdb();
// เชื่อมต่อฐานข้อมูล MySQL
$conn = $db->connectMySQL();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าจากฟอร์มและทำความสะอาดข้อมูล
    $location = $_POST['_location'];
    $department = $_POST['_department'];
    $faculty = $_POST['_faculty'];
    $region = $_POST['_region'];
    $address = $_POST['_address'];
    $sendTo = $_POST['_sendto'];
    $coordinator = $_POST['_coordinator'];
    $scope = $_POST['_scope'];
    $year1 = $_POST['_year1'];
    $count1 = $_POST['_count1'];
    $year2 = $_POST['_year2'];
    $count2 = $_POST['_count2'];

    // เตรียมคำสั่ง SQL สำหรับการเพิ่มข้อมูลในตาราง detail
    $stmt1 = $conn->prepare("INSERT INTO detail (region_id, facuty_id, location, department, address, sendto, coordinator, Scope_work) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    
    // ตรวจสอบการเตรียมคำสั่ง
    if ($stmt1 === false) {
        die("Prepare z: " . htmlspecialchars($conn->error));
    }

    // ผูกค่ากับคำสั่ง
    $stmt1->bind_param("ssssssss", $region, $faculty, $location, $department, $address, $sendTo, $coordinator, $scope);
    
    // รันคำสั่ง
    if (!$stmt1->execute()) {
        echo "Error executing query: " . htmlspecialchars($stmt1->error);
    }

    // ดึง max id หลังจากการเพิ่มข้อมูล
    $max_id = $conn->insert_id; // ใช้ insert_id เพื่อดึง ID ของแถวล่าสุดที่เพิ่ม

    // เตรียมคำสั่ง SQL สำหรับเพิ่มข้อมูลในตาราง recieve_year
    $stmt2 = $conn->prepare("INSERT INTO recieve_year (location_id, year, received, term) VALUES (?, ?, ?, ?)");
    
    // ตรวจสอบการเตรียมคำสั่ง
    if ($stmt2 === false) {
        die("Prepare failed: " . htmlspecialchars($conn->error));
    }

    // เพิ่มข้อมูลภาคการศึกษาที่ 1
    $term1 = 1;
    $stmt2->bind_param("isii", $max_id, $year1, $count1, $term1);
    if (!$stmt2->execute()) {
        echo "Error executing query: " . htmlspecialchars($stmt2->error);
    }

    // เพิ่มข้อมูลภาคการศึกษาที่ 2
    $term2 = 2;
    $stmt2->bind_param("isii", $max_id, $year2, $count2, $term2);
    if (!$stmt2->execute()) {
        echo "Error executing query: " . htmlspecialchars($stmt2->error);
    } else {
        header("Location: ../alert.php?func=1&message=". urlencode("บันทึกข้อมูลสำเร็จ"));
        exit;
    }

    // ปิดคำสั่ง
    $stmt1->close();
    $stmt2->close();
    
    // ปิดการเชื่อมต่อ
    $conn->close();
}
?>
