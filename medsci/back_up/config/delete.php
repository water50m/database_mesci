<?php

require 'condb.php'; // เชื่อมต่อฐานข้อมูล

$db = new connectdb();
$conn = $db->connectPDO();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าที่ต้องการลบ
    $id = $_POST['_location']; // หรือใช้ค่าอื่นๆ ตามที่คุณกำหนด
    $name = $_POST['_loName'];




    // สร้างคำสั่ง SQL สำหรับลบข้อมูล
    $sql = "DELETE FROM detail WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    if($stmt->execute()){
        $message = 'ลบข้อมูลของ '.$name.' แล้ว';
        header("Location: ../alert.php?func=3&hear=2&type=".$location."&message=". urlencode($message));
        exit;
    }  else{
        $message = 'ไม่สามารลบข้อมูลของ '.$name;
        header("Location: ../alert.php?func=3&hear=2&type=".$location."&message=". urlencode($message));
        exit;
    } 
    
}

// ปิดการเชื่อมต่อ
mysqli_close($conn);

?>