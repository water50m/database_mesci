<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'internship_medsci1');
define('DB_USER', 'root');
define('DB_PASS', '');

// Class for database connection and operations
class connectdb {
    private $conn_mysql = null;
    private $conn_pdo = null;

    // Method for MySQL connection
    public function connectMySQL() {
        try {
            $this->conn_mysql = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            
            if (!$this->conn_mysql) {
                throw new Exception("MySQL Connection failed: " . mysqli_connect_error());
            }
            
            // Set charset to handle special characters correctly
            mysqli_set_charset($this->conn_mysql, "utf8mb4");
            
            return $this->conn_mysql;
            
        } catch (Exception $e) {
            die("Connection error: " . $e->getMessage());
        }
    }

    // Method for PDO connection
    public function connectPDO() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $this->conn_pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
            
            return $this->conn_pdo;
            
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    // Method to close MySQL connection
    public function closeMySQL() {
        if ($this->conn_mysql) {
            mysqli_close($this->conn_mysql);
        }
    }

    // Method to close PDO connection
    public function closePDO() {
        $this->conn_pdo = null;
    }

    // Example query method using MySQL
    public function queryMySQL($sql) {
        try {
            $result = mysqli_query($this->conn_mysql, $sql);
            if (!$result) {
                throw new Exception("Query failed: " . mysqli_error($this->conn_mysql));
            }
            return $result;
        } catch (Exception $e) {
            die("Query error: " . $e->getMessage());
        }
    }

    // Example query method using PDO
    public function queryPDO($sql, $params = []) {
        try {
            $stmt = $this->conn_pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            die("Query error: " . $e->getMessage());
        }
    }
}

// // Example usage:
// try {
//     // Create database instance
//     $db = new Database();

//     // Example using MySQL
//     $mysql_conn = $db->connectMySQL();
//     // Your MySQL queries here
//     // $result = $db->queryMySQL("SELECT * FROM Student");
    
//     // Example using PDO
//     $pdo_conn = $db->connectPDO();
//     // Your PDO queries here
//     // $stmt = $db->queryPDO("SELECT * FROM Student WHERE studentID = ?", [1]);
    
//     // Close connections when done
//     $db->closeMySQL();
//     $db->closePDO();
    
// } catch (Exception $e) {
//     echo "Error: " . $e->getMessage();
// }
?>