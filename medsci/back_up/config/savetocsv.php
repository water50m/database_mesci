<?php 
// require 'condb.php';
// $new = new connectdb();
// $conn = $new->connectPDO();

// $stmt = $conn->query("SELECT d.id, r.name AS region,f.major_subject,d.location,d.department,d.address FROM detail d JOIN region r ON d.region_id = r.id  JOIN facuty f ON d.facuty_id = f.id");
// $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
// $file = fopen("detail.csv", "w");
// fwrite($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
// fputcsv($file, ["ID", "region", "type","location","department"]);
// foreach ($results as $result){
//     // print_r($result);
//     // echo '--------------------';
//     if (!$result['department']){
//         $result['department'] = 'no data';
//     }

//     if (!$result['address']){
//         $result['address'] = 'no data';
//     }

//     if ($result['region'] == 'พิษณุโลก' || $result['region'] == 'ม.นเรศวร' || $result['region'] == 'กรุงเทพ ปริมณฑล'){
//         $result['region'] = 'ภาคกลาง';
//     }
   
//     fputcsv($file, $result);
// }
// อ่านข้อมูลจากไฟล์ CSV

$host = 'localhost';
$dbname = 'internship_medsci1';
$username = 'root';
$password = '';

try {
    // สร้างการเชื่อมต่อ
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // อ่านและแปลงไฟล์ JSON
    $jsonData = file_get_contents('coordinate.json');
    $data = json_decode($jsonData, true);

    // เตรียมคำสั่ง SQL สำหรับอัพเดตข้อมูล
    $sql = "UPDATE detail 
            SET province = :province, latitude = :latitude, longtitude = :longtitude 
            WHERE id = :id";

    $stmt = $conn->prepare($sql);

    // วนลูปอัพเดตข้อมูลในตาราง
    foreach ($data as $row) {
        $stmt->execute([
            ':id' => $row['id'],
            ':province' => $row['province'],
            ':latitude' => $row['latitude'],
            ':longtitude' => $row['longtitude']
        ]);
        echo "Data for province {$row['province']} updated successfully.<br>";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn = null;
?>

?>