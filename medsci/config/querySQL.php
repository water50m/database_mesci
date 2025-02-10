<?php
require_once 'condb.php';

class SQLquery {
    private $db;
    private $conn;
    private $stmt_cache = []; // เพิ่ม cache สำหรับ prepared statements

    public function __construct() {
        $this->db = new connectdb();
        $this->conn = $this->db->connectPDO();
        $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // ปิด emulated prepares
    }

    private function prepareAndCache($sql) {
        // ใช้ cache prepared statement
        if (!isset($this->stmt_cache[$sql])) {
            $this->stmt_cache[$sql] = $this->conn->prepare($sql);
        }
        return $this->stmt_cache[$sql];
    }

    public function testquery(){
        try {
            $sql = "SELECT * FROM region";
            $stmt = $this->prepareAndCache($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error in testquery: " . $e->getMessage());
            throw $e;
        }
    }

    public function selectProvince(){
        try {
            $sql = "SELECT 
                p.id AS province_id,
                p.name AS province_name, 
                g.name AS region_name,
                g.id AS region_id,
                p.latitude,
                p.longitude,
                CASE 
                    WHEN g.id = 1 THEN 'north'
                    WHEN g.id = 2 THEN 'northeast' 
                    WHEN g.id = 3 THEN 'west'
                    WHEN g.id = 5 THEN 'east'
                    WHEN g.id = 6 THEN 'south'
                    WHEN g.id IN (4, 7, 8, 9) THEN 'central'
                    ELSE NULL
                END AS region_category
            FROM province p 
            JOIN region g ON p.region_id = g.id
            ORDER BY p.name";
            
            $stmt = $this->prepareAndCache($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error in selectProvince: " . $e->getMessage());
            throw $e;
        }
    }

    public function selectRegion(){
        try {
            $sql = "SELECT * FROM region";
            $stmt = $this->prepareAndCache($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error in selectRegion: " . $e->getMessage());
            throw $e;
        }
    }

    public function selectFacuty(){
        try {
            $sql = "SELECT f.facuty,
                f.id AS fid,
                f.facuty,
                f.major_subject AS f_major,
                facuty,
                d.location AS location,
                CASE WHEN d.location IS NULL THEN 0 ELSE COUNT(*) END AS total  
            FROM facuty f 
            LEFT JOIN detail d ON d.facuty_id = f.id 
            GROUP BY f.id";
            
            $stmt = $this->prepareAndCache($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error in selectFacuty: " . $e->getMessage());
            throw $e;
        }
    }
    public function selectOnlyFacuty(){
        try {
            $sql = "SELECT facuty    
            FROM facuty  
            GROUP BY facuty";
            
            $stmt = $this->prepareAndCache($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error in selectFacuty: " . $e->getMessage());
            throw $e;
        }
    }
    

    public function selectDetail(){
        try {
            $sql = "SELECT location, department, Scope_work, receive_term1, receive_term2 FROM detail";
            $stmt = $this->prepareAndCache($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error in selectDetail: " . $e->getMessage());
            throw $e;
        }
    }

    public function selectRecieveYear(){
        try {
            $sql = "SELECT * FROM recieve_year";
            $stmt = $this->prepareAndCache($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error in selectRecieveYear: " . $e->getMessage());
            throw $e;
        }
    }

    public function selectCoordinate(){
        try {
            $sql = "SELECT 
                d.id,
                d.location,
                f.id AS fid,
                f.facuty,
                f.major_subject AS majorName,
                r.name AS regionName,
                r.id AS rid,
                d.province,
                d.latitude,
                d.longtitude
            FROM detail d 
            JOIN facuty f ON f.id = d.facuty_id 
            JOIN region r ON d.region_id = r.id";
            
            $stmt = $this->prepareAndCache($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error in selectCoordinate: " . $e->getMessage());
            throw $e;
        }
    }

    public function selectAllDetail($id, $region, $province){
        try {
            
            $mainWordQuery = "";
            
            
            if (isset($province) || isset($region)) {
                if (isset($region) && $region != "") {
                    $mainWordQuery .= " AND LOWER(d.region_id) LIKE LOWER(:region_id)";
                    $params[':region_id'] = '%' . strtolower($_POST['region_id']) . '%';
                }

                if (isset($province) && $province != "") {
                    $mainWordQuery .= " AND LOWER(d.province) LIKE LOWER(:province)";
                    $params[':province'] = '%' . strtolower($_POST['province']) . '%';
                }
            }

            $sql = "SELECT 
                d.id,
                d.location,
                d.department,
                d.Scope_work,
                d.receive_term1,
                d.receive_term2,
                f.id AS fid,
                f.major_subject AS majorName,
                f.facuty,
                r.name AS regionName,
                r.id AS rid,
                d.address,
                d.sendto,
                d.coordinator,
                d.province,
                d.latitude,
                d.longtitude,
                d.picture_path
            FROM detail d 
            LEFT JOIN facuty f ON f.id = d.facuty_id 
            LEFT JOIN region r ON d.region_id = r.id
            LEFT JOIN recieve_year y ON d.id = y.location_id
            WHERE d.id = :id" . $mainWordQuery;
            
            $stmt = $this->prepareAndCache($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error in selectAllDetail: " . $e->getMessage());
            throw $e;
        }
    }

    public function selectAllreceive_year($id){
        try {
            $sql = "SELECT * FROM recieve_year WHERE location_id = ?";
            $stmt = $this->prepareAndCache($sql);
            $stmt->execute([$id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error in selectAllreceive_year: " . $e->getMessage());
            throw $e;
        }
    }

    public function selectToMap($region, $province, $facuty, $major_subject){
        try {
            $params = [];
            $conditions = [];

            if (isset($region) && $region != "" && $region != 'allRegion') {
                $conditions[] = "d.region_id = :region";
                $params[':region'] = $region;
            }

            if (isset($province) && $province != "" && $province != "allProvince") {
                $conditions[] = "d.province = :province";
                $params[':province'] = $province;
            }

            if (isset($facuty) && $facuty != "") {
                $conditions[] = "f.facuty = :facuty";
                $params[':facuty'] = $facuty;
            }

            if (isset($major_subject) && $major_subject != "") {
                $conditions[] = "f.major_subject = :major_subject";
                $params[':major_subject'] = $major_subject;
            }

            $whereClause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";

            $sql = "SELECT 
                d.id,
                d.location,
                f.id AS fid,
                f.major_subject AS majorName,
                r.name AS regionName,
                r.id AS rid,
                d.province,
                d.latitude,
                d.longtitude
            FROM detail d 
            JOIN facuty f ON f.id = d.facuty_id 
            JOIN region r ON d.region_id = r.id 
            $whereClause";

            $stmt = $this->prepareAndCache($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error in selectToMap: " . $e->getMessage());
            throw $e;
        }
    }

    function getFilteredDetails($location,$region,$facuty_Select,$branch) {

        // เริ่มสร้าง query หลัก
        $mainWordQuery = "SELECT d.id, d.location, d.department, d.Scope_work, d.receive_term1, d.receive_term2, 
                          f.major_subject AS majorName, r.name AS regionName, d.picture_path
                          FROM detail d 
                          LEFT JOIN facuty f ON f.id = d.facuty_id 
                          LEFT JOIN region r ON d.region_id = r.id";
    
        // สร้าง array สำหรับเก็บเงื่อนไขและพารามิเตอร์
        $conditions = [];
        $params = [];
    
        // เพิ่มเงื่อนไขตามตัวแปรที่ส่งเข้ามา
        if ($location !== null) {
            $conditions[] = "LOWER(d.location) LIKE LOWER(:location)";
            $params[':location'] = '%' . strtolower($location) . '%';
        }
    
        if ($region && $region != 'allr' && $region != 'noselect') {
            $conditions[] = "r.name LIKE :region";
            $params[':region'] = '%' . $region . '%';
        }
    
        if ($facuty_Select && $facuty_Select != 'allf' && $facuty_Select != 'noselect') {
            $conditions[] = "f.facuty LIKE :facuty";
            $params[':facuty'] = '%' . $facuty_Select . '%';
        }
    
        if ($branch && $branch != 'noselect' && $branch != 'allp') {
            $conditions[] = "f.major_subject LIKE :branch";
            $params[':branch'] = '%' . $branch . '%';
        }
    
        // รวมเงื่อนไขเข้ากับ query หลัก
        if (count($conditions) > 0) {
            $mainWordQuery .= ' WHERE ' . implode(' AND ', $conditions);
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
    
        // ส่งผลลัพธ์กลับ
        return $details;
    }
    
  
   
}

?>
