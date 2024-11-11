<?php 
require 'condb.php';
$new = new connectdb();
$conn = $new->connectPDO();

$stmt =$conn->query("SELECT * FROM users WHERE name = 'admin8'");
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($result);
foreach ($result as $data){

    
    if(password_verify('1234', '$2y$10$zCJA.dvvJqmalGiObpQ4Jus6YZfDtWNwHaRCm5OKAFjAOtFrv9Tt2'  )){
   
        echo 'เท่ากัน';
    }else{
        echo 'ไม่เท่า';
    }
}

?>