<?php 
$message = isset($_GET['message']) ? $_GET['message'] : '';
$func =  isset($_GET['func']) ? $_GET['func'] : '';
$type =  isset($_GET['type']) ? $_GET['type'] : '';
if($func == 1){
    $redirectUrl = 'addData.php';


}else if($func == 2){
    $redirectUrl = 'modify_data.php?func=2&type='.$type;
}
?>

<script>
        // แสดงข้อความแจ้งเตือนถ้ามีข้อความในเซสชัน
        const message = "<?= htmlspecialchars($message) ?>";
        const redirectUrl = "<?= $redirectUrl ?>";
        if (message) {
            
            alert(message); // แสดงหน้าต่างแจ้งเตือน
            window.location.href = redirectUrl;
        }
    </script>