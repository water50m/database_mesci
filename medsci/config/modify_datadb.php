<?php
    require 'condb.php';

    $db = new connectdb();
    // เชื่อมต่อฐานข้อมูล MySQL
    $conn = $db->connectMySQL();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // รับค่าจากฟอร์ม
        $locationName = $_POST['_loName'];
        $location = $_POST['_location'];
        
        $province = $_POST['_province'];
        $latitude = $_POST['_latitude'];
        $longitude = $_POST['_longitude'];
        $department = $_POST['_department'];
        $faculty = $_POST['_faculty'];
        $region =$_POST['_region'];
        $address = $_POST['_address'];
        $sendTo = $_POST['_sendto'];
        $coordinator = $_POST['_coordinator'];
        $scope = $_POST['_scope'];


        $year1 = $_POST['_year1'];//ปีที่แก้ไข
        $count1 = $_POST['_receive'];
        $term1 = $_POST['_term1'];

        $_term1_before = $_POST['_term1_before'];//ค่าก่อนแก้ไข
        $_year1_before = $_POST['_year1_before'];
        

        $year2 = $_POST['_year2'];//ปีที่เพิ่มใหม่
        $count2 = $_POST['_count2'];
        $term2 = $_POST['_term2'];
        $message="อัพเดตข้อมูลสำเร็จ";

        $stmt1 = $conn->prepare("UPDATE detail 
        SET region_id = ?, facuty_id = ?, department = ?, 
            address = ?, sendto = ?, 
            coordinator = ?, Scope_work = ?, province = ?,
            latitude = ?, longtitude = ? 
        WHERE id = ?");
        $stmt1->bind_param("iisssssi", $region, $faculty, $department, $address, $sendTo, $coordinator, $scope, $location, $province, $latitude, $longitude);

        // รันคำสั่ง
        if (!$stmt1->execute()) {
            echo "Error executing detail update: " . htmlspecialchars($stmt1->error);
        }
        if($_term1_before == $year1){
            
            // header("Location: ../alert.php?func=2&hear=1&type=".$location."&message=". urlencode("อัพเดตข้อมูลสำเร็จ"));
            // exit;
        }

        // ตรวจสอบค่า $year1 และ $count1 ก่อนทำการอัปเดต
        if ($year1 != 'dontChange' && $year1) {
            $stmt2 = $conn->prepare("UPDATE recieve_year 
                                    SET received = ? ,year=?,term=?
                                    WHERE year = ? AND term = ? AND location_id=?");
            $stmt2->bind_param("iiisss", $count1,$year1,$term1,$_year1_before, $_term1_before,$location);
            
            if (!$stmt2->execute()) {
                echo "Error executing year1 update: " . htmlspecialchars($stmt2->error);
            }else{
                $checksuccess ='yes';
                if(!$year2){
                    // header("Location: ../alert.php?func=2&hear=2&type=".$location."&message=". urlencode("อัพเดตข้อมูลสำเร็จ"));
                    // exit;
                }
            }
        }

        // ตรวจสอบ $year2 ก่อนทำการเพิ่มข้อมูลใหม่
        $chechYear2 = $conn->prepare("SELECT * FROM recieve_year WHERE year= ? AND term=?");
        $chechYear2 ->bind_param("ii", $year2,$term2);
        $chechYear2->execute();
        $result = $chechYear2->get_result()->fetch_assoc();
        $chechYear2->close();
        
        if($year2 && $result){
            $ceheck='huh!';
            $stmt4 = $conn->prepare("UPDATE recieve_year 
                                    SET received = ? 
                                    WHERE year = ? AND term = ? AND location_id=?");
            $stmt4->bind_param("iiii", $count2, $year2,$term2,$location);
            if (!$stmt4->execute()) {
                echo "Error executing year2 insert: " . htmlspecialchars($stmt4->error);
            }
            
            // header("Location: ../alert.php?func=2&hear=4&type=".$location."&message=". urlencode($message));
            // exit;
            
        }if ($year2 && !$result) {
            $ceheck='check check!';
            $stmt3 = $conn->prepare("INSERT INTO recieve_year(location_id, year, received, term) 
                                        VALUES (?, ?, ?, ?)");
            $stmt3->bind_param("iiii", $location, $year2, $count2,$term2);
            
            if (!$stmt3->execute()) {
                echo "Error executing year2 insert: " . htmlspecialchars($stmt3->error);
            }else{
                
                // header("Location: ../alert.php?func=2&hear=3&type=".$location."&message=". urlencode($message));
                // exit;
                
            }
        }

        // ปิดคำสั่งเพื่อเพิ่มประสิทธิภาพ
        $stmt1->close();
        
        if (isset($stmt2)) $stmt2->close();
        if (isset($stmt3)) $stmt3->close();
        if (isset($stmt4)) $stmt4->close();
        header("Location: ../alert.php?func=2&hear=3&type=".$location."&message=". urlencode($message));
        exit;
    }
    
?>