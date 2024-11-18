<?php
require 'condb.php';

$db = new connectdb();
$conn = $db->connectMySQL();
 
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // ตรวจสอบว่ามีการส่งค่าจาก modal `_addfacultymajor` หรือไม่
    if (isset($_POST['_addfacultyname1']) && !empty($_POST['_addfacultyname1'])) {
        $addfacultyname1 = $_POST['_addfacultyname1'];

        // เพิ่มข้อมูลเฉพาะในตาราง facuty
        $stmt_addfac1 = $conn->prepare("INSERT INTO facuty (facuty) VALUES (?)");
        if ($stmt_addfac1 === false) {
            die("Prepare failed: " . htmlspecialchars($conn->error));
        }
        $stmt_addfac1->bind_param("s", $addfacultyname1);

        if (!$stmt_addfac1->execute()) {
            echo "Error executing query: " . htmlspecialchars($stmt_addfac1->error);
        } else {
            
            header("Location: ../alert.php?func=1&message=" . urlencode("บันทึกข้อมูลสำเร็จ"));
            exit;
        }

        // ปิดคำสั่ง
        $stmt_addfac1->close();

    } 

    if(isset($_POST['_addfacultyname2']) && !empty($_POST['_addfacultyname2']) && isset($_POST['_addfacultymajor']) && !empty($_POST['_addfacultymajor'])){
        $addfacultyname2 = $_POST['_addfacultyname2'];
        $addfacultymajor = $_POST['_addfacultymajor'];

        $stmt_checkrepeat = $conn->prepare("SELECT id FROM facuty WHERE major_subject = ? AND facuty = ?");
        $stmt_checkrepeat->bind_param("ss", $addfacultymajor, $addfacultyname2);

        // รันคำสั่ง
        $stmt_checkrepeat->execute();
        $result_checkrepeat = $stmt_checkrepeat->get_result();

        if ($result_checkrepeat->num_rows > 0){
            header("Location: ../alert.php?func=1&message=" . urlencode("ข้อมูลซ้ำ: มีชื่อสาขาวิชาและคณะนี้อยู่ในระบบแล้ว"));
            exit;
        }
        $stmt_checkrepeat->close();

        // เพิ่มข้อมูลเฉพาะในตาราง facuty
    $stmt_addfac2 = $conn->prepare("INSERT INTO facuty (facuty, major_subject) VALUES (?, ?)");
    if ($stmt_addfac2 === false) {
        die("Prepare failed: " . htmlspecialchars($conn->error));
    }
    $stmt_addfac2->bind_param("ss", $addfacultyname2, $addfacultymajor);

    if (!$stmt_addfac2->execute()) {
        echo "Error executing query: " . htmlspecialchars($stmt_addfac2->error);
    } else {
        header("Location: ../alert.php?func=1&message=" . urlencode("บันทึกข้อมูลสำเร็จ"));
        exit;
    }
    $stmt_addfac2->close();

    } else {
        $province = $_POST['_province'];
        $latitude = $_POST['_latitude'];
        $longitude = $_POST['_longitude'];
        $location = $_POST['_location'];
        $department = $_POST['_department'];
        $faculty_major = $_POST['_facultymajor'];
        $faculty_name = $_POST['_facultyname'];
        $region = $_POST['_region'];
        $address = $_POST['_address'];
        $sendTo = $_POST['_sendto'];
        $coordinator = $_POST['_coordinator'];
        $scope = $_POST['_scope'];
        $year1 = $_POST['_year1'];
        $count1 = $_POST['_count1'];
        $year2 = $_POST['_year2'];
        $count2 = $_POST['_count2'];
        if(isset($_GET['newlocation'])){
            $location = $_GET['newlocation'];
        }
        // เช็ค id จาก คณะและสาขา
        // เตรียมคำสั่ง SQL สำหรับการตรวจสอบค่า
        $stmt_checkid = $conn->prepare("SELECT id FROM facuty WHERE major_subject = ? AND facuty = ?");
        $stmt_checkid->bind_param("ss", $faculty_major, $faculty_name);

        // รันคำสั่ง
        $stmt_checkid->execute();
        $result = $stmt_checkid->get_result();

        // ตรวจสอบผลลัพธ์
        $faculty_id = 0;
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $faculty_id = $row['id'];
            // echo "พบคณะที่ตรงกับ ID: " . $faculty_id;
        } else {
            header("Location: ../alert.php?func=1&message=" . urlencode("ไม่มีสาขาวิชา"."$faculty_major "." ในคณะ"."$faculty_name "));
            exit;
        }
        $stmt_checkid->close();

        // เพิ่มข้อมูลในตาราง detail
        $stmt1 = $conn->prepare("INSERT INTO detail (region_id, facuty_id, location, department, address, sendto, coordinator, Scope_work, province, latitude, longtitude) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt1 === false) {
            die("Prepare failed: " . htmlspecialchars($conn->error));
        }
        $stmt1->bind_param("sssssssssss", $region, $faculty_id, $location, $department, $address, $sendTo, $coordinator, $scope, $province, $latitude, $longitude);

        if (!$stmt1->execute()) {
            echo "Error executing query: " . htmlspecialchars($stmt1->error);
        }

        // ดึง max id หลังจากการเพิ่มข้อมูล
        $max_id = $conn->insert_id;

        // เพิ่มข้อมูลในตาราง recieve_year
        $stmt2 = $conn->prepare("INSERT INTO recieve_year (location_id, year, received, term) VALUES (?, ?, ?, ?)");
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
            header("Location: ../alert.php?func=1&message=" . urlencode("บันทึกข้อมูลสำเร็จ"));
            exit;
        }

        // ปิดคำสั่ง
        $stmt1->close();
        $stmt2->close();
    }

    // ปิดการเชื่อมต่อ
    $conn->close();
}
?>
