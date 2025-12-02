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
        $establishment = $_POST['_establishment'];
        $region = $_POST['_region'];
        if($region == 'ภาคเหนือ') {
            $region = 1;
        } else if($region == 'ภาคตะวันออกเฉียงเหนือ') {
            $region = 2;
        } else if($region == 'ภาคตะวันตก') {
            $region = 3;
        } else if($region == 'ภาคกลาง') {
            $region = 4;
        } else if($region == 'ภาคตะวันออก') {
            $region = 5;
        } else if($region == 'ภาคใต้') {
            $region = 6;
        } else {
            $region = 0;
        }
        $address = $_POST['_address'];
        $sendTo = $_POST['_sendto'];
        $coordinator = $_POST['_coordinator'];
        $scope = $_POST['_scope'];
        $year1 = !empty($_POST['_year1']) ? $_POST['_year1'] : 0;
        $count1 = $_POST['_count1'];
        $year2 = !empty($_POST['_year2']) ? $_POST['_year2'] : 0;
        $count2 = $_POST['_count2'];
        if(isset($_GET['newlocation'])){
            $location = $_GET['newlocation'];
        }
        

        $message = "บันทึกข้อมูลแล้ว";
        // เช็ค id จาก คณะและสาขา
        // เตรียมคำสั่ง SQL สำหรับการตรวจสอบค่า

        $stmt_checkid = $conn->prepare("SELECT major_subject,id FROM facuty WHERE id = ? "); //select id where id... ????
        $stmt_checkid->bind_param("s", $faculty_major);

        // รันคำสั่ง
        $stmt_checkid->execute();
        $result = $stmt_checkid->get_result();

        // ตรวจสอบผลลัพธ์
        $faculty_id = 0;
        $faculty_name = '';
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $faculty_id = $row['id'];
            $faculty_name = $row['major_subject'];

            // echo "พบคณะที่ตรงกับ ID: " . $faculty_id;
        } else {
            // header("Location: ../alert.php?func=1&message=" . urlencode("ไม่มีสาขาวิชา"."$faculty_major "." ในคณะ"."$establishment "));
            echo json_encode([
                "status" => "error",
                "message" => "ไม่มีสาขาวิชาที่เลือก",
                "data" => $_POST
            ], JSON_UNESCAPED_UNICODE);
            exit;   
            
        }

        // ตรวจสอบสถานที่ซ้ำ
        $stmt_check_duplicate_location = $conn->prepare("SELECT id FROM detail WHERE location = ? AND facuty_id = ?");
        $stmt_check_duplicate_location->bind_param("si",$location,$faculty_id);
        $stmt_check_duplicate_location->execute();
        $result_duplicate_location_facuty_major = $stmt_check_duplicate_location->get_result(); 
        if ($result_duplicate_location_facuty_major && $result_duplicate_location_facuty_major->num_rows > 0){

            echo json_encode([
                "status" => "error",
                "message" => "สถานที่: ".$location . "\n" . "สาขาวิชา " . $faculty_name . " มีข้อมูลอยู่แล้ว",
                "data" => $_POST
            ], JSON_UNESCAPED_UNICODE);
            exit;   
        }
        $stmt_checkid->close();

        // เพิ่มข้อมูลในตาราง detail
        $stmt1 = $conn->prepare("INSERT INTO detail (region_id ,facuty_id ,establishment_id ,location , department, address, sendto, coordinator, Scope_work, province, latitude, longtitude) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt1 === false) {
            echo json_encode([
                "status" => "error",
                "message" => "เกิดข้อผิดพลาดกับการเชื่อมต่อฐานข้อมูล",
            ], JSON_UNESCAPED_UNICODE);
            exit;           }

        // กำหนดค่าเริ่มต้นสำหรับ picture_path
        $picture_path = null;
        
        // ย้าย bind_param มาก่อนการ execute
        $stmt1->bind_param("sissssssssss", $region,$faculty_id,$establishment, $location, $department, $address, $sendTo, $coordinator, $scope, $province, $latitude, $longitude);

        if (!$stmt1->execute()) {
            echo json_encode([
                "status" => "error",
                "message" => htmlspecialchars($stmt1->error),
            ], JSON_UNESCAPED_UNICODE);
            exit;   
        }


        // ดึง max_id ทันทีหลังจาก execute
        $max_id = $conn->insert_id;
        
        // จัดการอัปโหลดรูปภาพ
        if (isset($_FILES['picture_']) && $_FILES['picture_']['error'] == 0) {
            
            $targetDir = "images/picture/"; // โฟลเดอร์เก็บรูปภาพ
            $fileExtension = pathinfo($_FILES["picture_"]["name"], PATHINFO_EXTENSION);
            $targetFilePath = $targetDir .'location_'. $max_id . '.' . $fileExtension;
            
            echo $targetFilePath;
            $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array($fileType, $allowedTypes)) {
                // ย้ายไฟล์ไปยังโฟลเดอร์เป้าหมาย
                if (move_uploaded_file($_FILES["picture_"]["tmp_name"],'../'. $targetFilePath)) {
                    // บันทึกข้อมูลลงในฐานข้อมูล
                    $update_stmt = $conn->prepare("UPDATE detail SET picture_path = ? WHERE id = ?");
                    $update_stmt->bind_param("si", $targetFilePath, $max_id);
                    
                    if ($update_stmt->execute() === TRUE) {
                        $message =  "The file has been uploaded and data saved.";
                    } else {
                        $message = "Database error: "; //. $conn->error;
                    }
                } else {
                    $message =  "Sorry, there was an error uploading your file.";
                }
            } else {
                $message = "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
            }
        }
        

        // เพิ่มข้อมูลในตาราง recieve_year

        $stmt2 = $conn->prepare("INSERT INTO recieve_year (location_id, year, received, term,major_subject_id) VALUES (?, ?, ?, ?, ?)");
 
        if ($stmt2 === false) {
            // die("Prepare failed: " . htmlspecialchars($conn->error));
            echo json_encode([
                "status" => "error",
                "message" => "เกิดข้อผิดพลาดกับการเชื่อมต่อฐานข้อมูล",
            ], JSON_UNESCAPED_UNICODE);
            exit;   
        }   

        // เพิ่มข้อมูลภาคการศึกษาที่ 1
        if ($year1 != 0 && $count1 != 0){
            $term1 = 1;
            $stmt2->bind_param("isiii", $max_id, $year1, $count1, $term1,$faculty_major );
            if (!$stmt2->execute()) {
            echo json_encode([
                "status" => "error",
                "message" => htmlspecialchars($stmt1->error),
            ], JSON_UNESCAPED_UNICODE);
            exit;               }
        }
        // เพิ่มข้อมูลภาคการศึกษาที่ 2
        if ($year2 != 0 && $count2 != 0){
            $term2 = 2;
            $stmt2->bind_param("isiii", $max_id, $year2, $count2, $term2,$faculty_major );
            if (!$stmt2->execute()) {
            echo json_encode([
                "status" => "error",
                "message" => htmlspecialchars($stmt2->error),
            ], JSON_UNESCAPED_UNICODE);
            exit;   
               
            } 
        }
        echo json_encode([
            "status" => "success",
            "message" => "บันทึกข้อมูลสำเร็จ",
        ], JSON_UNESCAPED_UNICODE);
        exit;   
        
        // ปิดคำสั่ง
        $stmt1->close();
        $stmt2->close();
    }

    // ปิดการเชื่อมต่อ
    $conn->close();
}
?>
