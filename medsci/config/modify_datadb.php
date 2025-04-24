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
        
        $faculty_major = $_POST['_facultymajor']; // เพิ่มตัวแปรที่ขาดหาย
        $establishment = $_POST['_establishment']; // เพิ่มตัวแปรที่ขาดหาย
        echo $establishment;
        echo "---";
        
        $region = $_POST['_region'];
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
        $message = "อัพเดตข้อมูลสำเร็จ";
        
        // ตรวจสอบว่ามีการส่งค่าจาก modal `_addfacultymajor` หรือไม่
        if (isset($_POST['_addfacultyname1']) && !empty($_POST['_addfacultyname1'])) {
            $addfacultyname1 = $_POST['_addfacultyname1'];

            // เพิ่มข้อมูลเฉพาะในตาราง facuty
            $stmt_addfac1 = $conn->prepare("INSERT INTO facuty (facuty) VALUES (?)");
            if ($stmt_addfac1 === false) {
                die("Prepare failed: " . htmlspecialchars($conn->error));
            }
            $stmt_addfac1->bind_param("s", $addfacultyname1);
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
                header("Location: ../alert.php?func=2&type=".$location. "type2=". $faculty_major. "&message=" . urlencode("ข้อมูลซ้ำ: มีชื่อสาขาวิชาและคณะนี้อยู่ในระบบแล้ว"));
                exit;
            }
            $stmt_checkrepeat->close();

            // เพิ่มข้อมูลเฉพาะในตาราง facuty
            $stmt_addfac2 = $conn->prepare("INSERT INTO facuty (facuty, major_subject) VALUES (?, ?)");
            if ($stmt_addfac2 === false) {
                die("Prepare failed: " . htmlspecialchars($conn->error));
            }
            $stmt_addfac2->bind_param("ss", $addfacultyname2, $addfacultymajor);
        }
        
            if(isset($faculty_major) ) {
                $stmt_checkid = $conn->prepare("SELECT id FROM facuty WHERE id = ? ");
                $stmt_checkid->bind_param("i", $faculty_major);

                // รันคำสั่ง
                $stmt_checkid->execute();
                $result = $stmt_checkid->get_result();

                // ตรวจสอบผลลัพธ์
                $faculty_id = 0;
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $faculty_id = $row['id'];

                    // อัพเดทตารางหลัก
                    $stmt1 = $conn->prepare("UPDATE detail 
                    SET region_id = ?, department = ?, establishment_id = ?,
                        address = ?, sendto = ?, 
                        coordinator = ?, Scope_work = ?, province = ?, latitude = ?, longtitude = ?
                    WHERE id = ?");
                    
                    $stmt1->bind_param("isisssssdds", $region, $department, $establishment,$address, $sendTo,
                    $coordinator, $scope, $province, $latitude, $longitude, $location);
                    
                    // ตรวจสอบค่า $year1 และ $count1 ก่อนทำการอัปเดต
                    if ($year1 != 'dontChange' && $year1) {
                        $stmt2 = $conn->prepare("UPDATE recieve_year 
                                                SET received = ?, year = ?, term = ?
                                                WHERE year = ? AND term = ? AND location_id = ?");
                        $stmt2->bind_param("iiissi", $count1, $year1, $term1, $_year1_before, $_term1_before, $location);
                    }

                    // ตรวจสอบ $year2 ก่อนทำการเพิ่มข้อมูลใหม่
                    if ($year2) {
                        $chechYear2 = $conn->prepare("SELECT * FROM recieve_year WHERE year = ? AND term = ? AND location_id = ?");
                        $chechYear2->bind_param("iii", $year2, $term2, $location);
                        $chechYear2->execute();
                        $result = $chechYear2->get_result();
                        
                        if ($result->num_rows > 0) {
                            $stmt4 = $conn->prepare("UPDATE recieve_year 
                                                    SET received = ? ,major_subject_id = ?
                                                    WHERE year = ? AND term = ? AND location_id = ?");
                            $stmt4->bind_param("iiiii", $count2,$faculty_major, $year2, $term2, $location);
                        } else {
                            $stmt3 = $conn->prepare("INSERT INTO recieve_year(location_id, year, received, term) 
                                                        VALUES (?, ?, ?, ?)");
                            $stmt3->bind_param("iiii", $location, $year2, $count2, $term2);
                        }
                        $chechYear2->close();
                    }

                   
                   

                    
                } else {
                   header("Location: ../alert.php?func=2&type=".$location. "type2=". $faculty_major. "&message=" . urlencode("ไม่มีสาขาวิชา ".$faculty_major." กรุณาเพิ่มข้อมูลก่อน"));
                    exit;
                    
                }
                $stmt_checkid->close();
            }
        }
    
       
    // picture update
    if (isset($_FILES['picture_']) && $_FILES['picture_']['error'] == 0) {
        $targetDir = "images/picture/"; // โฟลเดอร์เก็บรูปภาพ
        $fileExtension = pathinfo($_FILES["picture_"]["name"], PATHINFO_EXTENSION);
        $targetFilePath = $targetDir . 'location_' . $location . '.' . $fileExtension;
        
        // ตรวจสอบและลบไฟล์เก่า
        $old_picture_query = $conn->prepare("SELECT picture_path FROM detail WHERE id = ?");
        $old_picture_query->bind_param("i", $location);
        $old_picture_query->execute();
        $old_picture_result = $old_picture_query->get_result();
        
        if ($old_picture_result->num_rows > 0) {
            $old_picture = $old_picture_result->fetch_assoc()['picture_path'];
            if ($old_picture && file_exists('../' . $old_picture)) {
                unlink('../' . $old_picture);
            }
        }
        
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["picture_"]["tmp_name"], '../' . $targetFilePath)) {
                $update_stmt = $conn->prepare("UPDATE detail SET picture_path = ? WHERE id = ?");
                $update_stmt->bind_param("si", $targetFilePath, $location);
                $update_stmt->execute();
                $update_stmt->close();
            }
        }
        $old_picture_query->close();
    }

    //Execute all statements
    if (isset($stmt_addfac1)) {
        if (!$stmt_addfac1->execute()) {
            echo "Error executing query: " . htmlspecialchars($stmt_addfac1->error);
        }
    }

    if (isset($stmt_addfac2)) {
        if (!$stmt_addfac2->execute()) {
            echo "Error executing query: " . htmlspecialchars($stmt_addfac2->error);
        }
    }

    try {
        // Execute statements
        if (isset($stmt1)) {
        
            if (!$stmt1->execute()) {
                
                throw new Exception("Error executing detail update: " . $stmt1->error);
            }
  
        }

        if (isset($stmt2)) {
            if (!$stmt2->execute()) {
                throw new Exception("Error executing year1 update: " . $stmt2->error);
            }
        }

        if (isset($stmt3)) {
            if (!$stmt3->execute()) {
                throw new Exception("Error executing year2 insert: " . $stmt3->error);
            }
        }

        if (isset($stmt4)) {
            if (!$stmt4->execute()) {
                throw new Exception("Error executing year2 update: " . $stmt4->error);
            }
        }

        // ปิด statements ทั้งหมด
        if (isset($stmt1)) $stmt1->close();
        if (isset($stmt2)) $stmt2->close();
        if (isset($stmt3)) $stmt3->close();
        if (isset($stmt4)) $stmt4->close();

        // redirect หลังจาก execute สำเร็จ
        header("Location: ../alert.php?func=2&type=".$location."&type2=".$faculty_major."&message=".urlencode($message));
        exit;
        
    } catch (Exception $e) {
        header("Location: ../alert.php?message=" . urlencode("เกิดข้อผิดพลาด: " . $e->getMessage()));
        exit;
    }

?>