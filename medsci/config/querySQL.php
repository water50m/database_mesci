
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
            $stmt = $this->conn->query("SELECT p.id AS province_id,p.name AS province_name,g.name AS region_name, g.id AS region_id FROM province p Join region g ON p.region_id = g.id"); // ใช้ $this->conn เพื่อเข้าถึง connection
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
}


?>