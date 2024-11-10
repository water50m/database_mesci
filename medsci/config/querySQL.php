
<?php
require_once 'condb.php';

// เรียกใช้ connection



// ตัวอย่างการใช้งาน
class SQLquery {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new connectdb();
        $this->conn = $this->db->connectPDO(); // เข้าถึงเมธอด connectPDO() ของคลาส connectdb
    }

    public function testquery(){
        try {
            $stmt = $this->conn->query("SELECT * FROM region"); // ใช้ $this->conn เพื่อเข้าถึง connection
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function selectProvince(){
        try {
            $stmt = $this->conn->query("SELECT 
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
                                    JOIN region g ON p.region_id = g.id");

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function selectRegion(){
        try{
            $stmt = $this->conn->query("SELECT * FROM region");
            $result = $stmt-> fetchALL(PDO::FETCH_ASSOC);
            return $result;
        }
        catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function selectFacuty(){
        try{
            $stmt = $this->conn->query("SELECT f.id AS fid ,f.major_subject AS f_major, d.location AS location ,CASE WHEN d.location IS NULL THEN 0 ELSE COUNT(*) END AS total  FROM facuty f LEFT JOIN detail d  ON d.facuty_id = f.id GROUP BY f.id");
            $result = $stmt-> fetchALL(PDO::FETCH_ASSOC);
            return $result;
        }
        catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function selectDetail(){
        try{
            $stmt = $this->conn->query("SELECT location, department, Scope_work, receive_term1, receive_term2 FROM detail");
            $result = $stmt-> fetchALL(PDO::FETCH_ASSOC);
            return $result;
        }
        catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function selectRecieveYear(){
        try{
            $stmt = $this->conn->query("SELECT * FROM recieve_year");
            $result = $stmt-> fetchALL(PDO::FETCH_ASSOC);
            return $result;
        }
        catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function selectCoordinate(){
        try{
            $stmt = $this->conn->prepare("SELECT d.id,d.location,f.id AS fid, 
                      f.major_subject AS majorName, r.name AS regionName ,r.id AS rid , d.province, d.latitude, d.longtitude
                      FROM detail d 
                      JOIN facuty f ON f.id = d.facuty_id 
                      JOIN region r ON d.region_id = r.id");
            $stmt->execute();          
            $result = $stmt-> fetchALL(PDO::FETCH_ASSOC);
            return $result;
        }
        catch(PDOEException $e) {
            echo "Error: " .$e->getMessage();
        }
    }


    public function selectAllDetail($id,$region,$province){
        try {
            // เตรียมคำสั่ง SQL
            
        
            // ตรวจสอบว่า $_POST['region_id'] หรือ $_POST['province'] ถูกตั้งค่าหรือไม่
            $params = [];
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
        
            // เพิ่มเงื่อนไขเพิ่มเติมในคำสั่ง SQL
            $sql = "SELECT d.id,d.location, d.department, d.Scope_work, d.receive_term1, d.receive_term2,
                    f.id AS fid, f.major_subject AS majorName, r.name AS regionName, r.id AS rid,
                    d.address, d.sendto, d.coordinator, d.province, d.latitude, d.longtitude
                    FROM detail d 
                    JOIN facuty f ON f.id = d.facuty_id 
                    JOIN region r ON d.region_id = r.id
                    JOIN recieve_year y ON d.id = y.location_id
                    WHERE d.id = :id" . $mainWordQuery; // เพิ่มคำสั่งเงื่อนไข
        
            // เตรียมการ query ใหม่
            $stmt = $this->conn->prepare($sql);
            $params[':id'] = $id;  // กำหนดค่า id
            $stmt->execute($params);
        
            // ดึงผลลัพธ์
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        
        catch(PDOEException $e) {
            echo "Error: " .$e->getMessage();
        }
    }
    public function selectAllreceive_year($id){
        try{
            $stmt = $this->conn->prepare("SELECT  *
                      FROM recieve_year
                      WHERE location_id = ?
                      ");
            $stmt->execute([$id]);          
            $result = $stmt-> fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        catch(PDOEException $e) {
            echo "Error: " .$e->getMessage();
        }
    }
    public function selectToMap($region,$province){
        
        $params = [];
        $mainWordQuery = "";

        if (isset($id) && $id != "") {
            $mainWordQuery .= " WHERE d.id = :id";
            $params[':id'] = 5;
        }

        if (isset($region) && $region != "" && $region!= 'allRegion') {
            // ถ้ามีเงื่อนไขก่อนหน้า ใช้ AND ต่อ ไม่งั้นใช้ WHERE
            $mainWordQuery .= (empty($mainWordQuery) ? " WHERE" : " AND") . " d.region_id = ". "'$region'";
            
        }

        if (isset($province) && $province != "" && $province != "allProvince") {
            $mainWordQuery .= (empty($mainWordQuery) ? " WHERE" : " AND") . " d.province = " . "'$province'";
        }

        // เพิ่มเงื่อนไขเพิ่มเติมในคำสั่ง SQL
        $sql = "SELECT d.id, d.location,
                f.id AS fid, f.major_subject AS majorName, r.name AS regionName, r.id AS rid , d.province, d.latitude, d.longtitude
                FROM detail d 
                JOIN facuty f ON f.id = d.facuty_id 
                JOIN region r ON d.region_id = r.id" . $mainWordQuery;
       
        // เตรียมการ query ใหม่
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

    
        // ดึงผลลัพธ์
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
}


?>
