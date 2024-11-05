<?php

// require 'condb.php'; // เชื่อมต่อฐานข้อมูล

// $db = new connectdb();
// $conn = $db->connectMySQL();

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     // รับค่าที่ต้องการลบ
//     $id = $_POST['_id']; // หรือใช้ค่าอื่นๆ ตามที่คุณกำหนด
//     $year = $_POST[''];
//     $term = $_POST[''];



//     // สร้างคำสั่ง SQL สำหรับลบข้อมูล
//     $sql = "DELETE FROM detail WHERE id = '$id'";

//     // รันคำสั่ง SQL
//     if (mysqli_query($conn, $sql)) {
//         echo "ลบข้อมูลสำเร็จ";
//     } else {
//         echo "เกิดข้อผิดพลาดในการลบข้อมูล: " . mysqli_error($conn);
//     }
// }

// // ปิดการเชื่อมต่อ
// mysqli_close($conn);

/* 
ถ้าจะลบ detail อาจจะรับ id and faculty_id 
ถ้าจะลบ recieve_year อาจจะรับ year and term
*/
?>