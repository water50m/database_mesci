<?php 
require_once 'querySQL.php';
require_once 'conDB.php';

if (isset($_GET['func']) && $_GET['func'] == 1) {
    $value = isset($_GET['value']) ? $_GET['value'] : null;
    $db = new connectdb();
    $conn = $db->connectPDO();

    $details_query = "SELECT d.id,d.location, d.department, d.Scope_work, d.receive_term1, d.receive_term2, 
                 f.major_subject AS majorName ,r.name AS regionName
                  FROM detail d 
                  JOIN facuty f ON f.id = d.facuty_id 
                  JOIN region r ON d.region_id = r.id
                  WHERE d.facuty_id = :value";

// เตรียม statement
$stmt = $conn->prepare($details_query);

// กำหนดค่าตัวแปรสำหรับการ bind parameter

$stmt->bindParam(':value', $value, PDO::PARAM_INT);

// Execute คำสั่ง SQL
$stmt->execute();

// ดึงข้อมูลทั้งหมด
$details = $stmt->fetchAll(PDO::FETCH_ASSOC);

   
    echo json_encode([
        
        'value' => $details
    ]);
}



if (isset($_GET['func']) && $_GET['func'] == 2 ) {
    $location = $_POST['location'] ?? null;
    $region = $_POST['region'] ?? null;
    $department = $_POST['department'] ?? null;
    $branch = $_POST['branch'] ?? null;
    

    // เริ่มสร้าง query หลัก
    $mainWordQuery = "SELECT d.id,d.location, d.department, d.Scope_work, d.receive_term1, d.receive_term2, 
                      f.major_subject AS majorName, r.name AS regionName
                      FROM detail d 
                      JOIN facuty f ON f.id = d.facuty_id 
                      JOIN region r ON d.region_id = r.id";

    // เพิ่มเงื่อนไขตามตัวแปรที่ส่งเข้ามา
    $params = [];
    if ($location) {
        
        $mainWordQuery .= " AND d.location LIKE :location";
        $params[':location'] = '%' . $location . '%';
    }
    if ($region && $region!='allr') {
        
        $mainWordQuery .= " AND r.name LIKE :region";
        $params[':region'] = '%' . $region . '%';
    }
    if ($department && $department != 'noselect') {
        
        $mainWordQuery .= " AND d.department LIKE :department";
        $params[':department'] = '%' . $department . '%';
    }
    if ($branch && $branch != 'noselect' && $branch != 'allp') {
        $mainWordQuery .= " AND f.major_subject LIKE :branch";
        $params[':branch'] = '%' . $branch . '%';
    }

    // เชื่อมต่อฐานข้อมูล
    $db = new connectdb();
    $conn = $db->connectPDO();

    // เตรียม statement
    $stmt = $conn->prepare($mainWordQuery);

    // bind ตัวแปรเพิ่มเติม
    foreach ($params as $key => $val) {
        $stmt->bindValue($key, $val);
    }

    // Execute คำสั่ง SQL
    $stmt->execute();

    // ดึงข้อมูลทั้งหมด
    $details = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'value' => $details
    ]);
}


?>