<?php 
require_once 'querySQL.php';
require_once 'conDB.php';

if (isset($_GET['func']) && $_GET['func'] == 1) {
    $value = isset($_GET['value']) ? $_GET['value'] : null;
    $db = new connectdb();
    $conn = $db->connectPDO();

    $details_query = "SELECT d.id,d.location, d.department, d.Scope_work, d.receive_term1, d.receive_term2, 
                 f.major_subject AS majorName ,r.name AS regionName,d.picture_path
                  FROM detail d 
                  LEFT JOIN facuty f ON f.id = d.facuty_id 
                  LEFT JOIN region r ON d.region_id = r.id
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
    $query = new SQLquery();
    
    $location = $_POST['location'] ?? null;
    $region = $_POST['region'] ?? null;
    $facuty_Select = $_POST['facuty_Select'] ?? null;
    $branch = $_POST['branch'] ?? null;
    $data = $query->getFilteredDetails($location,$region,$facuty_Select,$branch);

    
    echo json_encode([
        
        'value' => $data 
    ]);
    // // เริ่มสร้าง query หลัก
    // $mainWordQuery = "SELECT d.id,d.location, d.department, d.Scope_work, d.receive_term1, d.receive_term2, 
    //                   f.major_subject AS majorName, r.name AS regionName,d.picture_path
    //                   FROM detail d 
    //                   LEFT JOIN facuty f ON f.id = d.facuty_id 
    //                   LEFT JOIN region r ON d.region_id = r.id";

    // // เพิ่มเงื่อนไขตามตัวแปรที่ส่งเข้ามา
    // $params = [];
    // if ($location) {
    //     if($location == ""){
    //         $mainWordQuery .= " WHERE LOWER(d.location) LIKE '%%'";
    //     }
    //     else{
    //         $mainWordQuery .= " WHERE LOWER(d.location) LIKE LOWER(:location)";
    //     }
    //     $params[':location'] = '%' . strtolower($location) . '%';
    // }
    // if ($region && $region!='allr' && $region != 'noselect') {
        
    //     $mainWordQuery .= " AND r.name LIKE :region";
    //     $params[':region'] = '%' . $region . '%';
    // }
    // if ($facuty_Select && $facuty_Select != 'allf' && $facuty_Select != 'noselect') {
        
    //     $mainWordQuery .= " AND f.facuty LIKE :facuty";
    //     $params[':facuty'] = '%' . $facuty_Select . '%';
    // }
    // if ($branch && $branch != 'noselect' && $branch != 'allp') {
    //     $mainWordQuery .= " AND f.major_subject LIKE :branch";
    //     $params[':branch'] = '%' . $branch . '%';
    // }
    
    // // เชื่อมต่อฐานข้อมูล
    // $db = new connectdb();
    // $conn = $db->connectPDO();

    // // เตรียม statement
    // $stmt = $conn->prepare($mainWordQuery);

    // // bind ตัวแปรเพิ่มเติม
    // foreach ($params as $key => $val) {
    //     $stmt->bindValue($key, $val);
    // }

    // // Execute คำสั่ง SQL
    // $stmt->execute();

    // // ดึงข้อมูลทั้งหมด
    // $details = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // echo json_encode([
    //     'sql' => $mainWordQuery,
    //     'value' => $details
    // ]);
}

function func3($locationName){
    
    $db = new connectdb();
    $conn = $db->connectPDO();

    $details_query = "SELECT d.id,d.location, d.department, d.Scope_work, d.receive_term1, d.receive_term2, 
                 f.major_subject AS majorName ,r.name AS regionName,d.picture_path
                  FROM detail d 
                  LEFT JOIN facuty f ON f.id = d.facuty_id 
                  LEFT JOIN region r ON d.region_id = r.id
                  WHERE d.location LIKE :locationName";

    // เตรียม statement
    $stmt = $conn->prepare($details_query);

    // เพิ่ม % ที่ค่าที่จะ bind แทน
    $locationNameWithWildcard = '%' . $locationName . '%';
    $stmt->bindParam(':locationName', $locationNameWithWildcard, PDO::PARAM_STR);

    // Execute คำสั่ง SQL
    $stmt->execute();

    // ดึงข้อมูลทั้งหมด
    $details = $stmt->fetchAll(PDO::FETCH_ASSOC);

    
    return $details;
}


if (isset($_GET['func']) && $_GET['func']==4){
    $id =  $_GET['value'] ?? null;

    $newQuery = new SQLquery();
    $data = $newQuery->selectAllreceive_year($id);
    echo json_encode([
        'value' => $data
    ]);
}


if (isset($_GET['func']) && $_GET['func']==5){
    $province = $_POST['province'] ?? null;
    $region = $_POST['region'] ?? null;
    $major_subject = $_POST['major_subject'] ?? null;
    $facuty = $_POST['facuty'] ?? null;
    $newQuery = new SQLquery();
    $provinceWithoutSuffix = explode(' (', $province)[0]; 
    if($region=='north'){$region=1;}
    else if($region=='northeast'){$region=2;}
    else if($region=='central'){$region=4;}
    else if($region=='south'){$region=6;}
    else if($region=='east'){$region=5;}
    else if($region=='west'){$region=3;}


    echo json_encode([
        'value' => $newQuery->selectToMap($region,$provinceWithoutSuffix,$facuty,$major_subject)
    ]);;

}
?>