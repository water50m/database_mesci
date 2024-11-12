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
            JOIN region g ON p.region_id = g.id";
            
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
            $sql = "SELECT 
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
            $params = [':id' => $id];
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
                d.longtitude
            FROM detail d 
            JOIN facuty f ON f.id = d.facuty_id 
            JOIN region r ON d.region_id = r.id
            JOIN recieve_year y ON d.id = y.location_id
            WHERE d.id = :id" . $mainWordQuery;

            $stmt = $this->prepareAndCache($sql);
            $stmt->execute($params);
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
}

?>
