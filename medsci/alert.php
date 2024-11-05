<?php 
$message = isset($_GET['message']) ? $_GET['message'] : '';
$func =  isset($_GET['func']) ? $_GET['func'] : '';
if($func == 1){
    $redirectUrl = 'addData.php';

}else if($fucn == 2){
    $redirectUrl = 'modify_data.php';
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