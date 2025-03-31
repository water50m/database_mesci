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

    public function establishment(){
        try {
            $sql = "SELECT * FROM establishment";
            $stmt = $this->prepareAndCache($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error in establishment: " . $e->getMessage());
            throw $e;
        }   
    }

    public function selectMajorSubjectName($id){
        try {
            $sql = "SELECT major_subject FROM facuty WHERE id = :id";
            $stmt = $this->prepareAndCache($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error in selectMajorSubjectName: " . $e->getMessage());
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
            $sql = "SELECT 
                        f.facuty,
                        f.id AS fid,
                        f.facuty,
                        f.major_subject AS f_major,
                        
                        CASE WHEN re.major_subject_id  IS NULL THEN 0 ELSE COUNT(*) END AS total  
                    FROM facuty f
                    LEFT JOIN recieve_year re ON f.id = re.major_subject_id
                    GROUP BY f.id ";
            
            $stmt = $this->prepareAndCache($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error in selectFacuty: " . $e->getMessage());
            throw $e;
        }
    }

    public function facutyTable(){
        try {
            $sql = "SELECT * FROM facuty";
            $stmt = $this->prepareAndCache($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error in facutyTable: " . $e->getMessage());
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
            error_log("Error in selectOnlyFacuty: " . $e->getMessage());
            throw $e;
        }
    }

    public function selectMajor(){
        try {
            $sql = "SELECT *    
            FROM facuty  ";
           

            $stmt = $this->prepareAndCache($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error in selectMajor: " . $e->getMessage());
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
                d.establishment_id,
                e.id as eid,
                e.establishment as establishment_name,
                r.name AS regionName,
                r.id AS rid,
                d.province,
                d.latitude,
                d.longtitude
            FROM detail d 
            LEFT JOIN region r ON d.region_id = r.id
            LEFT JOIN establishment e ON  d.establishment_id = e.id
            ";
            
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
                d.establishment_id,
                e.id as eid,
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
            LEFT JOIN establishment e ON  d.establishment_id = e.id
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
            $sql = "SELECT 
                        re.id AS reid,
                        re.location_id AS location_id,
                        re.year As year,
                        re.received AS received,
                        re.term AS term,
                        f.id AS mid,
                        f.major_subject AS m_name
                    FROM recieve_year re
                    LEFT JOIN facuty f ON re.major_subject_id = f.id
                    WHERE location_id = ?";
            $stmt = $this->prepareAndCache($sql);
            $stmt->execute([$id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error in selectAllreceive_year: " . $e->getMessage());
            throw $e;
        }
    }

    
    public function selectToMap($region, $province, $establishment, $major_subject){
        try {
            $params = [];
            $conditions = [];
            $joun_receive = " ";
            $grou_major = " ";
            $joun_establishment = " ";
            
            if (isset($major_subject) && $major_subject != "") {

                $conditions[] = "re.major_subject_id = :major_subject";
                $params[':major_subject'] = $major_subject;

                $joun_receive = "JOIN recieve_year re ON  re.location_id = d.id ";
                $grou_major  = "GROUP BY d.id";
                
            }

            if (isset($region) && $region != "" && $region != 'allRegion') {
                $conditions[] = "d.region_id = :region";
                $params[':region'] = $region;
            }

            if (isset($province) && $province != "" && $province != "allProvince") {
                $conditions[] = "d.province = :province";
                $params[':province'] = $province;
            }

            if (isset($establishment) && $establishment != "" ) {
                $joun_establishment = "JOIN establishment e ON  d.establishment_id = e.id";
                $conditions[] = "d.establishment_id = :establishment";
                $params[':establishment'] = $establishment;

            }

            

            $whereClause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";

            $sql = "SELECT 
                d.id,
                d.location,
                -- f.id AS fid,
                -- f.major_subject AS majorName,
                -- e.id AS eid,
                -- e.establishment AS establishment_name,
                r.name AS regionName,
                r.id AS rid,
                d.province,
                d.latitude,
                d.longtitude
            FROM detail d 
            -- JOIN facuty f ON f.id = d.facuty_id 
            JOIN region r ON d.region_id = r.id 
            $joun_establishment
            $joun_receive
            $whereClause
            $grou_major ";

            $stmt = $this->prepareAndCache($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            // return $sql;
        } catch(PDOException $e) {
            error_log("Error in selectToMap: " . $e->getMessage());
            throw $e;
        }
    }
}

?>
