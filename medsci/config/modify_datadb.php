<?php
    require 'condb.php';

    $db = new connectdb();
    // เชื่อมต่อฐานข้อมูล MySQL
    $conn = $db->connectMySQL();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // รับค่าจากฟอร์ม
        $location = $_POST['_location'];
        $term = $_POST['_term'];
        $department = $_POST['_department'];
        $faculty = $_POST['_faculty'];
        $region =$_POST['_region'];
        $address = $_POST['_address'];
        $sendTo = $_POST['_sendto'];
        $coordinator = $_POST['_coordinator'];
        $scope = $_POST['_scope'];
        $year1 = $_POST['_year1'];
        $count1 = $_POST['_count1'];
        $year2 = $_POST['_year2'];
        $count2 = $_POST['_count2'];
    
        mysqli_query($conn, "UPDATE detail 
        SET region_id = '$region', facuty_id = '$faculty', department = '$department',
            address = '$address', sendto = '$sendTo', 
            coordinator = '$coordinator', Scope_work = '$scope' 
        WHERE id = '$location'");


        if($year1 == 'dontChange' || !$year1){}else{
        // อัปเดตภาคการศึกษาที่ 1
        mysqli_query($conn, "UPDATE recieve_year 
                            SET received = '$count1' 
                            WHERE year = '$year1' AND term = '1'");
        }
        // ตรวจสอบว่ามีข้อมูลใน recieve_year สำหรับภาคการศึกษาที่ 2 อยู่หรือยังตามปี

            // เพิ่มข้อมูลใหม่ถ้าไม่มี
            if(!$year2){}else{    
        mysqli_query($conn, "INSERT INTO recieve_year(location_id, year, received, term) 
                                VALUES ('$location', '$year2', '$count2', '2')");
        }
    }
    
?>